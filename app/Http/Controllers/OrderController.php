<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;

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
            $proof = $request->file('proof_image')->store('proofs', 'public');
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
}