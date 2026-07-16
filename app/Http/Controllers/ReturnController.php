<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ReturnRequest;

class ReturnController extends Controller
{
    /**
     * Tampilkan form pengajuan return.
     * Dipanggil saat klik link "Ajukan Return" (GET /orders/{order}/return)
     */
    public function create(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        if ($order->status !== 'delivered') {
            return redirect()->route('orders.detail', $order->id)
                ->with('error', 'Pesanan ini belum bisa diajukan return.');
        }

        if ($order->returnRequest) {
            return redirect()->route('orders.detail', $order->id)
                ->with('error', 'Return untuk pesanan ini sudah pernah diajukan.');
        }

        return view('returns-create', compact('order'));
    }

    /**
     * Simpan pengajuan return.
     * Dipanggil saat submit form di halaman returns-create (POST /orders/{order}/return)
     */
    public function store(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);

        if ($order->status !== 'delivered') {
            return back()->with('error', 'Pesanan ini belum bisa diajukan return.');
        }

        if ($order->returnRequest) {
            return back()->with('error', 'Return untuk pesanan ini sudah pernah diajukan.');
        }

        $request->validate([
            'reason'      => 'required|string|max:1000',
            'proof_image' => 'nullable|image|max:2048',
        ]);

        $proof = null;
        if ($request->hasFile('proof_image')) {
            $proof = $request->file('proof_image')->store('returns', 'public');
        }

        ReturnRequest::create([
            'order_id'    => $order->id,
            'user_id'     => auth()->id(),
            'reason'      => $request->input('reason'),
            'proof_image' => $proof,
            'status'      => 'pending',
        ]);

        $order->update(['status' => 'returned']);

        return redirect()->route('orders.detail', $order->id)
            ->with('success', 'Pengajuan return berhasil dikirim. Tim kami akan segera meninjaunya.');
    }

    /**
     * Daftar semua pengajuan return (admin).
     */
    public function adminIndex()
    {
        $returns = ReturnRequest::with(['order', 'user'])->latest()->get();
        return view('admin.returns', compact('returns'));
    }

    public function approve(ReturnRequest $return)
    {
        $return->update(['status' => 'approved']);
        return back()->with('success', 'Return disetujui.');
    }

    public function reject(Request $request, ReturnRequest $return)
    {
        $request->validate(['admin_note' => 'nullable|string|max:1000']);

        $return->update([
            'status'     => 'rejected',
            'admin_note' => $request->input('admin_note'),
        ]);

        return back()->with('success', 'Return ditolak.');
    }
}