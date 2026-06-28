<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'   => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_users'    => User::where('role', 'customer')->count(),
            'revenue'        => Order::where('payment_status', 'paid')->sum('total_amount'),
        ];

        $recent_orders = Order::with('user')->latest()->limit(8)->get();

        // Grafik revenue 6 bulan terakhir
        $revenue_chart = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Produk terlaris
        $top_products = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.image', 'products.price')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Stok menipis (stok <= 10)
        $low_stock = Product::where('stock', '<=', 10)
            ->orderBy('stock')
            ->limit(8)
            ->get();

        // Laporan penjualan bulan ini (default)
        $laporanOrders = Order::with('user')
            ->where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->latest()
            ->get();

        $laporanStats = [
            'total_transaksi' => $laporanOrders->count(),
            'total_revenue'   => $laporanOrders->sum('total_amount'),
            'rata_rata'       => $laporanOrders->count() > 0 ? $laporanOrders->avg('total_amount') : 0,
        ];

        return view('admin.dashboard', compact(
            'stats',
            'recent_orders',
            'revenue_chart',
            'top_products',
            'low_stock',
            'laporanOrders',
            'laporanStats'
        ));
    }

    public function laporan(Request $request)
    {
        $dari  = $request->dari  ?? now()->startOfMonth()->format('Y-m-d');
        $sampai = $request->sampai ?? now()->format('Y-m-d');
        $status = $request->status ?? 'paid';

        $query = Order::with('user')
            ->whereBetween('created_at', [$dari . ' 00:00:00', $sampai . ' 23:59:59']);

        if ($status === 'paid') {
            $query->where('payment_status', 'paid');
        } elseif ($status === 'unpaid') {
            $query->where('payment_status', 'unpaid');
        }

        $orders = $query->latest()->get();

        $laporanStats = [
            'total_transaksi' => $orders->count(),
            'total_revenue'   => $orders->sum('total_amount'),
            'rata_rata'       => $orders->count() > 0 ? $orders->avg('total_amount') : 0,
        ];

        // Export CSV
        if ($request->export === 'csv') {
            $filename = 'laporan-penjualan-' . $dari . '-sd-' . $sampai . '.csv';
            $headers  = [
                'Content-Type'        => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function () use ($orders) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF"); // BOM UTF-8
                fputcsv($file, ['No. Order', 'Customer', 'Email', 'Total', 'Pembayaran', 'Status', 'Kurir', 'Tanggal']);
                foreach ($orders as $o) {
                    fputcsv($file, [
                        $o->order_number,
                        $o->user->name ?? '-',
                        $o->user->email ?? '-',
                        $o->total_amount,
                        $o->payment_status === 'paid' ? 'Lunas' : 'Belum Bayar',
                        $o->status,
                        $o->courier ?? '-',
                        $o->created_at->format('d/m/Y H:i'),
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return view('admin.laporan', compact('orders', 'laporanStats', 'dari', 'sampai', 'status'));
    }
}