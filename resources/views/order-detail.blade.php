@extends('layouts.app')

@section('title', 'Detail Pesanan — The Daily Outfit')

@section('content')
<style>
.od-wrap { background: var(--pink-50); min-height: 100vh; padding: 2rem 0; }
.od-container { max-width: 760px; margin: 0 auto; padding: 0 1rem; }

.od-header { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom: 1.5rem; flex-wrap:wrap; gap:8px; }
.od-header h1 { font-size: 1.5rem; font-weight: 700; color: var(--brown); margin: 0 0 4px; }
.od-header p { font-size: 13px; color: var(--gray-400); margin: 0; }

.od-card {
    background: white; border-radius: var(--radius);
    box-shadow: var(--shadow); padding: 1.5rem; margin-bottom: 1rem;
}
.od-card-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 15px; font-weight: 700; color: var(--gray-800);
    margin-bottom: 1.25rem; padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--gray-100);
}

/* Timeline */
.od-timeline { display: flex; flex-direction: column; gap: 0; }
.od-tl-item { display: flex; gap: 12px; position: relative; padding-bottom: 1.5rem; }
.od-tl-item:last-child { padding-bottom: 0; }
.od-tl-dot {
    width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; color: white; z-index: 1;
}
.od-tl-line {
    position: absolute; left: 13px; top: 28px; bottom: 0;
    width: 2px; background: var(--gray-200);
}
.od-tl-item.done .od-tl-line { background: var(--pink-300); }
.od-tl-content { flex: 1; }
.od-tl-title { font-size: 14px; font-weight: 700; color: var(--gray-800); }
.od-tl-desc { font-size: 12px; color: var(--gray-400); margin-top: 2px; }

/* Badge */
.badge {
    display: inline-block; font-size: 11px; font-weight: 600;
    padding: 3px 10px; border-radius: 50px;
}
.badge-pending    { background: #fef9c3; color: #854d0e; }
.badge-processing { background: #dbeafe; color: #1e40af; }
.badge-shipped    { background: #e0f2fe; color: #0369a1; }
.badge-delivered  { background: #dcfce7; color: #166534; }
.badge-cancelled  { background: #fee2e2; color: #991b1b; }
.badge-returned   { background: #ede9fe; color: #5b21b6; }
.badge-paid       { background: #dcfce7; color: #166534; }
.badge-unpaid     { background: #fff7ed; color: #9a3412; }
.badge-refunded   { background: #ede9fe; color: #5b21b6; }

/* Items */
.od-item-row {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 0; border-bottom: 1px solid var(--gray-100);
}
.od-item-row:last-child { border-bottom: none; }
.od-item-img {
    width: 52px; height: 52px; border-radius: 10px; overflow: hidden;
    background: var(--pink-50); flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
}
.od-item-img img { width: 100%; height: 100%; object-fit: cover; }
.od-item-name { font-size: 14px; font-weight: 600; color: var(--gray-800); }
.od-item-sub { font-size: 12px; color: var(--gray-400); margin-top: 2px; }
.od-item-price { font-weight: 700; color: var(--gray-800); white-space: nowrap; }

.summary-row { display: flex; justify-content: space-between; font-size: 14px; color: var(--gray-600); padding: 6px 0; }
.summary-total {
    display: flex; justify-content: space-between;
    font-size: 16px; font-weight: 700; color: var(--brown);
    padding: 12px 0 0; border-top: 2px solid var(--gray-100); margin-top: 6px;
}
.badge-free { background: #f0fdf4; color: #16a34a; font-size: 11px; font-weight: 700; padding: 2px 10px; border-radius: 50px; }

.od-actions { display: flex; gap: 10px; margin-top: 1rem; flex-wrap: wrap; }

.od-return-box {
    padding: 12px; border-radius: 8px; font-size: 13px; font-weight: 600; text-align: center; flex: 1;
}
.od-return-pending  { background:#fff7ed; color:#9a3412; border:1.5px solid #fed7aa; }
.od-return-approved { background:#f0fdf4; color:#166534; border:1.5px solid #bbf7d0; }
.od-return-rejected { background:#fef2f2; color:#991b1b; border:1.5px solid #fecaca; }
</style>

<div class="od-wrap">
<div class="od-container">

    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-error" style="margin-bottom:1rem;">{{ session('error') }}</div>
    @endif

    @php
    $statusLabels = [
        'pending'    => ['⏳ Menunggu',  'badge-pending'],
        'processing' => ['🔄 Diproses',  'badge-processing'],
        'shipped'    => ['🚚 Dikirim',   'badge-shipped'],
        'delivered'  => ['✅ Selesai',   'badge-delivered'],
        'cancelled'  => ['❌ Dibatalkan','badge-cancelled'],
        'returned'   => ['↩️ Direturn',  'badge-returned'],
    ];
    $badge = $statusLabels[$order->status] ?? ['Unknown', 'badge-pending'];
    @endphp

    <div class="od-header">
        <div>
            <h1>Pesanan #{{ $order->order_number }}</h1>
            <p>{{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <div style="display:flex; gap:6px; flex-wrap:wrap;">
            <span class="badge {{ $badge[1] }}">{{ $badge[0] }}</span>
            <span class="badge {{ $order->payment_status === 'paid' ? 'badge-paid' : ($order->payment_status === 'refunded' ? 'badge-refunded' : 'badge-unpaid') }}">
                {{ $order->payment_status === 'paid' ? '💳 Lunas' : ($order->payment_status === 'refunded' ? '↩️ Direfund' : '⚠️ Belum Bayar') }}
            </span>
        </div>
    </div>

    @if($order->status !== 'cancelled' && $order->status !== 'returned')
    {{-- TIMELINE STATUS --}}
    <div class="od-card">
        <div class="od-card-title">📍 Status Pesanan</div>
        @php
        $steps = [
            'pending'    => ['📋', 'Pesanan Dibuat', 'Pesanan kamu sedang menunggu konfirmasi pembayaran.'],
            'processing' => ['⚙️', 'Diproses', 'Pesanan kamu sedang disiapkan oleh penjual.'],
            'shipped'    => ['🚚', 'Dikirim', 'Pesanan dalam perjalanan ke alamat kamu.'],
            'delivered'  => ['✅', 'Selesai', 'Pesanan telah tiba di tujuan.'],
        ];
        $stepKeys = array_keys($steps);
        $currentIdx = array_search($order->status, $stepKeys);
        @endphp
        <div class="od-timeline">
            @foreach($steps as $key => [$icon, $title, $desc])
            @php
                $idx = array_search($key, $stepKeys);
                $done = $currentIdx !== false && $idx <= $currentIdx;
            @endphp
            <div class="od-tl-item {{ $done ? 'done' : '' }}">
                @if(!$loop->last)<div class="od-tl-line"></div>@endif
                <div class="od-tl-dot" style="background:{{ $done ? '#c94f7c' : '#d1d5db' }};">
                    {{ $done ? '✓' : $icon }}
                </div>
                <div class="od-tl-content">
                    <div class="od-tl-title" style="color:{{ $done ? 'var(--brown)' : 'var(--gray-400)' }};">{{ $title }}</div>
                    <div class="od-tl-desc">{{ $desc }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @elseif($order->status === 'cancelled')
    <div class="od-card" style="background:#fef2f2; border:1px solid #fecaca;">
        <div style="font-size:14px; font-weight:600; color:#991b1b;">❌ Pesanan ini telah dibatalkan</div>
        <p style="font-size:12px; color:#b91c1c; margin-top:4px;">Hubungi admin jika kamu merasa ini sebuah kesalahan.</p>
    </div>
    @elseif($order->status === 'returned')
    <div class="od-card" style="background:#f5f3ff; border:1px solid #ddd6fe;">
        <div style="font-size:14px; font-weight:600; color:#5b21b6;">↩️ Pesanan ini telah direturn</div>
        <p style="font-size:12px; color:#6d28d9; margin-top:4px;">Dana akan dikembalikan sesuai kebijakan toko.</p>
    </div>
    @endif

    {{-- ITEM PESANAN --}}
    <div class="od-card">
        <div class="od-card-title">🛍️ Item Pesanan</div>
        @foreach($items as $item)
        <div class="od-item-row">
            <div class="od-item-img">
                @if($item->product && $item->product->image)
                <img src="{{ str_starts_with($item->product->image, 'http') ? $item->product->image : asset('storage/' . $item->product->image) }}">
                @else
                <span style="font-size:18px;">👗</span>
                @endif
            </div>
            <div style="flex:1;">
                <div class="od-item-name">{{ $item->product->name ?? '-' }}</div>
                <div class="od-item-sub">Ukuran {{ $item->size }} × {{ $item->quantity }}</div>
            </div>
            <div class="od-item-price">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
        </div>
        @endforeach

        <div style="margin-top:12px;">
            <div class="summary-row">
                <span>Subtotal</span>
                <span>Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row">
                <span>Ongkir</span>
                @if($order->shipping_cost > 0)
                    <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                @else
                    <span class="badge-free">GRATIS</span>
                @endif
            </div>
            <div class="summary-total">
                <span>Total</span>
                <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- ALAMAT --}}
    <div class="od-card">
        <div class="od-card-title">📍 Alamat Pengiriman</div>
        <p style="font-size:14px; color:var(--gray-700); line-height:1.6; margin:0;">{{ $order->shipping_address }}</p>
        @if($order->courier)
        <p style="font-size:13px; color:var(--gray-400); margin-top:8px;">🚚 Kurir: {{ $order->courier }}</p>
        @endif
    </div>

    {{-- PEMBAYARAN --}}
    <div class="od-card">
        <div class="od-card-title">💳 Informasi Pembayaran</div>
        <div class="summary-row">
            <span>Metode</span>
            <span style="font-weight:600; color:var(--gray-800);">{{ ucfirst($order->payment_method) }}</span>
        </div>
        <div class="summary-row">
            <span>Status</span>
            <span class="badge {{ $order->payment_status === 'paid' ? 'badge-paid' : ($order->payment_status === 'refunded' ? 'badge-refunded' : 'badge-unpaid') }}">
                {{ $order->payment_status === 'paid' ? '💳 Lunas' : ($order->payment_status === 'refunded' ? '↩️ Direfund' : '⚠️ Belum Bayar') }}
            </span>
        </div>

        @if($order->payment && $order->payment->proof_image)
        <div style="margin-top:1rem; border-top:1px solid var(--gray-100); padding-top:1rem;">
            <p style="font-size:13px; font-weight:600; color:var(--gray-700); margin-bottom:8px;">Bukti Pembayaran</p>
            <img src="{{ asset('storage/' . $order->payment->proof_image) }}"
                 style="width:100%; max-width:300px; border-radius:10px; border:1px solid var(--gray-200);">
        </div>
        @endif
    </div>

    {{-- RETURN --}}
    @if($order->returnRequest)
    <div class="od-card">
        <div class="od-card-title">↩️ Request Return</div>
        <div class="summary-row">
            <span>Status</span>
            <span class="badge {{ $order->returnRequest->status === 'approved' ? 'badge-delivered' : ($order->returnRequest->status === 'rejected' ? 'badge-cancelled' : 'badge-pending') }}">
                {{ ucfirst($order->returnRequest->status) }}
            </span>
        </div>
        <div class="summary-row" style="flex-direction:column; align-items:flex-start; gap:4px;">
            <span>Alasan</span>
            <span style="color:var(--gray-700); font-weight:500;">{{ $order->returnRequest->reason }}</span>
        </div>
        @if($order->returnRequest->status === 'rejected' && $order->returnRequest->admin_note)
        <div class="summary-row" style="flex-direction:column; align-items:flex-start; gap:4px;">
            <span>Catatan Admin</span>
            <span style="color:#991b1b; font-weight:500;">{{ $order->returnRequest->admin_note }}</span>
        </div>
        @endif
    </div>
    @endif

    {{-- ACTIONS --}}
    <div class="od-actions">

        {{-- Tombol Bayar: muncul kalau belum bayar dan belum dibatalkan --}}
        @if($order->payment_status === 'unpaid' && $order->status !== 'cancelled')
        <a href="{{ route('payment', $order->id) }}" class="btn btn-primary" style="flex:1; justify-content:center;">
            💳 Bayar Sekarang
        </a>
        @endif

        {{-- Tombol Batalkan: muncul HANYA kalau belum bayar (unpaid) dan status masih pending/processing --}}
        @if($order->payment_status === 'unpaid' && in_array($order->status, ['pending', 'processing']))
        <form method="POST" action="{{ route('orders.cancel', $order->id) }}" style="flex:1;" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
            @csrf
            <button type="submit" style="width:100%; padding:12px; background:white; color:#dc2626; border:1.5px solid #fecaca; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer;">
                ❌ Batalkan Pesanan
            </button>
        </form>
        @endif

        {{-- Info: sudah bayar, tidak bisa dibatalkan --}}
        @if($order->payment_status === 'paid' && in_array($order->status, ['pending', 'processing']))
        <div style="flex:1; padding:12px; background:#fff7ed; color:#9a3412; border:1.5px solid #fed7aa; border-radius:8px; font-size:13px; font-weight:600; text-align:center;">
            ℹ️ Pesanan tidak bisa dibatalkan setelah pembayaran dikonfirmasi
        </div>
        @endif

        {{-- Tombol Ajukan Return: muncul kalau status delivered dan belum pernah return --}}
        @if($order->status === 'delivered' && !$order->returnRequest)
        <a href="{{ route('returns.create', $order->id) }}" class="btn btn-primary" style="flex:1; justify-content:center; background:#7c3aed; border-color:#7c3aed;">
            ↩️ Ajukan Return
        </a>
        @endif

        <a href="{{ route('orders') }}" class="btn btn-outline" style="flex:1; justify-content:center;">← Kembali ke Pesanan Saya</a>
    </div>

</div>
</div>
@endsection