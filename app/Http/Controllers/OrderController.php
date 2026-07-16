<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use App\Helpers\CloudinaryHelper;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        return view('orders', compact('orders'));
    }

    public function detail(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        $items = $order->items()->with('product')->get();
        return view('order-detail', compact('order', 'items'));
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Pesanan tidak bisa dibatalkan karena sudah dikirim.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.detail', $order->id)->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function payment(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        $items = $order->items()->with('product')->get();
        return view('payment', compact('order', 'items'));
    }

    public function confirmPayment(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        $proof = '';
        if ($request->hasFile('proof_image')) {
            $proof = CloudinaryHelper::upload($request->file('proof_image')->getRealPath(), 'payments');
        }

        Payment::create([
            'order_id'    => $order->id,
            'amount'      => $order->total_amount,
            'method'      => $order->payment_method,
            'proof_image' => $proof,
            'status'      => 'pending',
        ]);

        $order->update(['payment_status' => 'paid', 'status' => 'processing']);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }

    /**
     * Ajukan return untuk sebuah pesanan.
     * Hanya bisa diajukan kalau pesanan sudah dibayar / diproses / selesai,
     * dan belum pernah diajukan return sebelumnya.
     */
    public function requestReturn(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        // hanya pesanan yang sudah dibayar/diproses/selesai yang bisa direturn
        if (!in_array($order->status, ['processing', 'completed', 'shipped', 'delivered'])) {
            return back()->with('error', 'Pesanan ini belum bisa diajukan return.');
        }

        if ($order->status === 'return_requested') {
            return back()->with('error', 'Return untuk pesanan ini sudah pernah diajukan.');
        }

        $request->validate([
            'reason' => 'nullable|string|max:1000',
        ]);

        $order->update([
            'status'        => 'return_requested',
            'return_reason' => $request->input('reason'),
        ]);

        return back()->with('success', 'Pengajuan return berhasil dikirim. Tim kami akan segera meninjaunya.');
    }
}