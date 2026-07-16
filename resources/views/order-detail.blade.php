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
    $dbStatus = isset($order->status) ? strtolower(trim($order->status)) : 'pending';
    
    $statusLabels = [
        'pending'    => ['⏳ Menunggu Pembayaran', 'badge-pending'],
        'unpaid'     => ['⏳ Menunggu Pembayaran', 'badge-pending'],
        'diproses'   => ['🔄 Sedang Diproses',   'badge-processing'],
        'processing' => ['🔄 Sedang Diproses',   'badge-processing'],
        'dikirim'    => ['🚚 Sedang Dikirim',    'badge-shipped'],
        'shipped'    => ['🚚 Sedang Dikirim',    'badge-shipped'],
        'selesai'    => ['✅ Pesanan Selesai',    'badge-delivered'],
        'delivered'  => ['✅ Pesanan Selesai',    'badge-delivered'],
        'batal'      => ['❌ Dibatalkan',        'badge-cancelled'],
        'cancelled'  => ['❌ Dibatalkan',        'badge-cancelled'],
        'returned'   => ['↩️ Direturn',           'badge-returned'],
    ];
    
    if (array_key_exists($dbStatus, $statusLabels)) {
        $badge = $statusLabels[$dbStatus];
    } else {
        $badge = ['📦 ' . ucwords($order->status), 'badge-processing'];
    }
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

    @if($dbStatus !== 'cancelled' && $dbStatus !== 'batal' && $dbStatus !== 'returned')
    {{-- TIMELINE STATUS --}}
    <div class="od-card">
        <div class="od-card-title">📍 Status Pesanan</div>
        @php
        $steps = [
            'pending'    => ['📋', 'Pesanan Dibuat', 'Pesanan kamu sedang menunggu konfirmasi pembayaran.'],
            'diproses'   => ['⚙️', 'Diproses', 'Pesanan kamu sedang disiapkan oleh penjual.'],
            'dikirim'    => ['🚚', 'Dikirim', 'Pesanan dalam perjalanan ke alamat kamu.'],
            'selesai'    => ['✅', 'Selesai', 'Pesanan telah tiba di tujuan.'],
        ];

        $normalizedStatus = $dbStatus;
        if($dbStatus === 'processing') $normalizedStatus = 'diproses';
        if($dbStatus === 'shipped') $normalizedStatus = 'dikirim';
        if($dbStatus === 'delivered') $normalizedStatus = 'selesai';

        $stepKeys = array_keys($steps);
        $currentIdx = array_search($normalizedStatus, $stepKeys);
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
    @elseif($dbStatus === 'cancelled' || $dbStatus === 'batal')
    <div class="od-card" style="background:#fef2f2; border:1px solid #fecaca;">
        <div style="font-size:14px; font-weight:600; color:#991b1b;">❌ Pesanan ini telah dibatalkan</div>
        <p style="font-size:12px; color:#b91c1c; margin-top:4px;">Hubungi admin jika kamu merasa ini sebuah kesalahan.</p>
    </div>
    @endif

    {{-- ITEM PESANAN --}}
    <div class="od-card">
        <div class="od-card-title">🛍️ Item Pesanan</div>
        @foreach($items as $item)
        <div class="od-item-row">
            <div class="od-item-img">
                @if($item->product && $item->product->image)
                    @php
                        $imagePath = $item->product->image;
                    @endphp
                    {{-- Mencoba mencari gambar di storage, jika gagal pindah ke asset public biasa --}}
                    <img src="{{ str_starts_with($imagePath, 'http') ? $imagePath : (file_exists(public_path('storage/' . $imagePath)) ? asset('storage/' . $imagePath) : asset($imagePath)) }}" 
                         alt="{{ $item->product->name ?? 'Produk' }}"
                         onerror="this.onerror=null; this.src='{{ asset('storage/products/' . $imagePath) }}';">
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
    </div>

    {{-- ACTIONS --}}
    <div class="od-actions">
        @if($order->payment_status === 'unpaid' && !in_array($dbStatus, ['cancelled', 'batal']))
        <a href="{{ route('payment', $order->id) }}" class="btn btn-primary" style="flex:1; justify-content:center;">
            💳 Bayar Sekarang
        </a>
        @endif

        <a href="{{ route('orders') }}" class="btn btn-outline" style="flex:1; justify-content:center;">← Kembali ke Pesanan Saya</a>
    </div>

</div>
</div>
@endsection