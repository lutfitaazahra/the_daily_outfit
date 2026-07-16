<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ReturnRequest;

class ReturnController extends Controller
{
    // Customer: tampilkan form request return
    public function create(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Return hanya bisa diajukan untuk pesanan yang sudah diterima.');
        }

        if ($order->returnRequest) {
            return back()->with('error', 'Kamu sudah mengajukan return untuk pesanan ini.');
        }

        return view('returns.create', compact('order'));
    }

    // Customer: submit request return
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Return hanya bisa diajukan untuk pesanan yang sudah diterima.');
        }

        if ($order->returnRequest) {
            return back()->with('error', 'Kamu sudah mengajukan return untuk pesanan ini.');
        }

        $request->validate([
            'reason' => 'required|string|min:10',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        $proof = null;
        if ($request->hasFile('proof_image')) {
            $proof = $request->file('proof_image')->store('return-proofs', 'public');
        }

        ReturnRequest::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'reason' => $request->reason,
            'proof_image' => $proof,
            'status' => 'pending',
        ]);

        return redirect()->route('orders.detail', $order->id)
            ->with('success', 'Request return berhasil diajukan, tunggu konfirmasi admin.');
    }

    // Admin: daftar semua request return
    public function adminIndex()
    {
        $returns = ReturnRequest::with(['order', 'user'])->latest()->get();
        return view('admin.returns.index', compact('returns'));
    }

    // Admin: approve return
    public function approve(ReturnRequest $return)
    {
        $return->update(['status' => 'approved']);

        $return->order->update([
            'status' => 'returned',
            'payment_status' => 'refunded',
        ]);

        return back()->with('success', 'Return disetujui, status pesanan diubah jadi returned.');
    }

    // Admin: reject return
    public function reject(Request $request, ReturnRequest $return)
    {
        $request->validate([
            'admin_note' => 'required|string|min:5',
        ]);

        $return->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Return ditolak.');
    }
}