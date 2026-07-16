<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\ReturnRequest;

class ReturnController extends Controller
{
    /**
     * Tampilkan form pengajuan return.
     * GET /orders/{order}/return
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
     * POST /orders/{order}/return
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

        // PENTING: order->status TIDAK diubah. Order tetap 'delivered'.
        // Status retur dilacak sepenuhnya lewat tabel returns sendiri.

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

    /**
     * Admin setujui pengajuan retur.
     */
    public function approve(ReturnRequest $return)
    {
        if ($return->status !== 'pending') {
            return back()->with('error', 'Retur ini sudah diproses sebelumnya.');
        }

        $return->update(['status' => 'approved']);

        return back()->with('success', 'Return disetujui. Silakan tunggu barang dikirim balik oleh customer.');
    }

    /**
     * Admin tolak pengajuan retur.
     */
    public function reject(Request $request, ReturnRequest $return)
    {
        if ($return->status !== 'pending') {
            return back()->with('error', 'Retur ini sudah diproses sebelumnya.');
        }

        $request->validate(['admin_note' => 'nullable|string|max:1000']);

        $return->update([
            'status'     => 'rejected',
            'admin_note' => $request->input('admin_note'),
        ]);

        return back()->with('success', 'Return ditolak.');
    }

    /**
     * Admin tandai barang retur sudah diterima kembali.
     */
    public function markItemReceived(ReturnRequest $return)
    {
        if ($return->status !== 'approved') {
            return back()->with('error', 'Retur ini belum disetujui atau sudah lebih lanjut prosesnya.');
        }

        $return->update(['status' => 'item_received']);

        return back()->with('success', 'Barang retur ditandai sudah diterima.');
    }

    /**
     * Admin tandai dana sudah dikembalikan ke customer. Ini status final.
     */
    public function markRefunded(ReturnRequest $return)
    {
        if ($return->status !== 'item_received') {
            return back()->with('error', 'Barang retur belum ditandai diterima.');
        }

        $return->update(['status' => 'refunded']);

        // Order payment_status ikut diupdate jadi refunded (kolom ini sudah ada di migration orders kamu)
        $return->order->update(['payment_status' => 'refunded']);

        return back()->with('success', 'Retur selesai. Dana sudah dikembalikan ke customer.');
    }
}