@extends('layouts.app')

@section('title', 'Notifikasi — The Daily Outfit')

@section('content')
<div class="section">
    <div class="container" style="max-width:700px;">

        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
            <div>
                <h1 style="font-family:var(--font); font-size:1.75rem; font-weight:700; color:var(--brown); margin-bottom:4px;">
                    Notifikasi
                </h1>
                <p style="font-size:13px; color:var(--gray-500);">{{ $notifications->count() }} notifikasi</p>
            </div>
            @if($notifications->count() > 0)
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="btn btn-outline" style="padding:8px 16px; font-size:12px;">
                    Tandai semua dibaca
                </button>
            </form>
            @endif
        </div>

        @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1.25rem;">{{ session('success') }}</div>
        @endif

        @if($notifications->count() > 0)
        <div style="display:flex; flex-direction:column; gap:10px;">
            @foreach($notifications as $notif)
            @php
                $typeStyles = [
                    'order' => ['color' => '#22c55e', 'icon' => '✅'],
                    'promo' => ['color' => '#c94f7c', 'icon' => '🎉'],
                    'info'  => ['color' => '#3b82f6', 'icon' => 'ℹ️'],
                ];
                $style = $typeStyles[$notif->type] ?? $typeStyles['info'];

                // AMBIL PESAN ASLI
                $displayMessage = $notif->message;

                // DETEKSI KONDISI OTOMATIS BERDASARKAN REAL-TIME STATUS DATABASE
                if ($notif->type === 'order') {
                    // Cari kode invoice TDO-XXXXX di dalam teks
                    preg_match('/TDO-[A-Z0-9-]+/', $notif->message, $matches);
                    if (!empty($matches)) {
                        $invoiceNumber = $matches[0];
                        // Ambil status terbaru dari database berdasarkan invoice
                        $realOrder = \App\Models\Order::where('order_number', str_replace('TDO-', '', $invoiceNumber))
                                        ->orWhere('order_number', $invoiceNumber)
                                        ->first();

                        if ($realOrder) {
                            $nominalStr = '';
                            preg_match('/senilai Rp\s*[0-9.,]+/', $notif->message, $nominalMatches);
                            if (!empty($nominalMatches)) {
                                $nominalStr = ' ' . $nominalMatches[0];
                            }

                            // Ubah teks secara dinamis mengikuti status pembayaran/pesanan asli
                            if ($realOrder->payment_status === 'paid') {
                                if (in_array(strtolower($realOrder->status), ['diproses', 'processing'])) {
                                    $displayMessage = "Pesanan kamu dengan invoice {$invoiceNumber}{$nominalStr} telah lunas dan sedang disiapkan oleh penjual.";
                                } elseif (in_array(strtolower($realOrder->status), ['dikirim', 'shipped'])) {
                                    $displayMessage = "Pesanan kamu dengan invoice {$invoiceNumber}{$nominalStr} sedang dalam perjalanan oleh kurir.";
                                } elseif (in_array(strtolower($realOrder->status), ['selesai', 'delivered'])) {
                                    $displayMessage = "Pesanan kamu dengan invoice {$invoiceNumber}{$nominalStr} telah selesai diterima. Terima kasih!";
                                } else {
                                    $displayMessage = "Pesanan kamu dengan invoice {$invoiceNumber}{$nominalStr} telah berhasil dibayar dan lunas.";
                                }
                            } elseif (in_array(strtolower($realOrder->status), ['batal', 'cancelled'])) {
                                $displayMessage = "Pesanan kamu dengan invoice {$invoiceNumber}{$nominalStr} telah dibatalkan.";
                            }
                        }
                    }
                }
            @endphp
            <div class="notif-card" style="border-left:4px solid {{ $style['color'] }}; {{ !$notif->is_read ? 'background:#fff8f9;' : 'background:white;' }}">
                <div class="notif-icon">{{ $style['icon'] }}</div>
                <div class="notif-content">
                    <div class="notif-title">{{ $notif->title }}</div>
                    <div class="notif-message">{{ $displayMessage }}</div>
                    <div class="notif-time">{{ $notif->created_at->diffForHumans() }}</div>
                </div>
                <form method="POST" action="{{ route('notifications.destroy', $notif->id) }}" onsubmit="return confirm('Hapus notifikasi ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="notif-delete" title="Hapus">×</button>
                </form>
            </div>
            @endforeach
        </div>
        @else
        <div style="text-align:center; padding:4rem 1rem;">
            <div style="font-size:3rem; margin-bottom:1rem;">🎈</div>
            <h3 style="font-family:var(--font); font-size:1.25rem; font-weight:700; color:var(--brown); margin-bottom:6px;">
                Semua Bersih
            </h3>
            <p style="font-size:13px; color:var(--gray-500);">Belum ada notifikasi untuk kamu saat ini.</p>
        </div>
        @endif

    </div>
</div>

<style>
.notif-card {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 16px 18px;
    border-radius: 12px;
    border: 1px solid var(--gray-200);
    transition: box-shadow 0.2s;
}
.notif-card:hover {
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}
.notif-icon {
    font-size: 1.25rem;
    flex-shrink: 0;
    margin-top: 2px;
}
.notif-content {
    flex: 1;
    min-width: 0;
}
.notif-title {
    font-size: 14px;
    font-weight: 700;
    color: var(--brown);
    margin-bottom: 4px;
}
.notif-message {
    font-size: 13px;
    color: var(--gray-600);
    line-height: 1.5;
    margin-bottom: 6px;
}
.notif-time {
    font-size: 11px;
    color: var(--gray-400);
}
.notif-delete {
    background: none;
    border: none;
    color: var(--gray-400);
    font-size: 18px;
    line-height: 1;
    cursor: pointer;
    padding: 2px 4px;
    flex-shrink: 0;
    transition: color 0.2s;
}
.notif-delete:hover {
    color: #dc2626;
}
</style>
@endsection