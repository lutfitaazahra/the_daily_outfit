@extends('layouts.app')

@section('title', 'Pesanan Saya — The Daily Outfit')

@section('content')
<style>
.orders-wrap { background: var(--pink-50); min-height: 100vh; padding: 2rem 0; }
.orders-container { max-width: 760px; margin: 0 auto; padding: 0 1rem; }

.orders-header { margin-bottom: 1.5rem; }
.orders-header h1 { font-size: 1.75rem; font-weight: 700; color: var(--brown); margin: 0 0 4px; }
.orders-header p  { font-size: 13px; color: var(--gray-400); margin: 0; }

/* Empty state */
.empty-state {
    background: white; border-radius: var(--radius); box-shadow: var(--shadow);
    padding: 4rem 2rem; text-align: center;
}
.empty-state .empty-icon { font-size: 4rem; display: block; margin-bottom: 1rem; }
.empty-state p { font-size: 15px; color: var(--gray-400); margin-bottom: 1.5rem; }

/* Order card */
.order-card {
    background: white; border-radius: var(--radius);
    box-shadow: var(--shadow); margin-bottom: 1rem;
    overflow: hidden; transition: box-shadow 0.2s;
}
.order-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.08); }

.order-card-header {
    display: flex; justify-content: space-between; align-items: flex-start;
    padding: 1rem 1.25rem 0.75rem;
    border-bottom: 1px solid var(--gray-100);
    flex-wrap: wrap; gap: 8px;
}
.order-number { font-size: 13px; font-weight: 700; color: var(--brown); letter-spacing: 0.5px; }
.order-date   { font-size: 12px; color: var(--gray-400); margin-left: 8px; }
.order-badges { display: flex; gap: 6px; flex-wrap: wrap; }

/* Badge status */
.badge {
    display: inline-block; font-size: 11px; font-weight: 600;
    padding: 3px 10px; border-radius: 50px;
}
.badge-pending    { background: #fef9c3; color: #854d0e; }
.badge-processing { background: #dbeafe; color: #1e40af; }
.badge-shipped    { background: #e0f2fe; color: #0369a1; }
.badge-delivered  { background: #dcfce7; color: #166534; }
.badge-cancelled  { background: #fee2e2; color: #991b1b; }
.badge-paid       { background: #dcfce7; color: #166534; }
.badge-unpaid     { background: #fff7ed; color: #9a3412; }

/* Items */
.order-items-section { padding: 0.75rem 1.25rem; }
.order-item-row {
    display: flex; align-items: center; gap: 10px;
    padding: 6px 0; border-bottom: 1px solid var(--gray-100);
    font-size: 13px; color: var(--gray-700);
}
.order-item-row:last-child { border-bottom: none; }
.order-item-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--pink-300); flex-shrink: 0;
}

/* Kurir info */
.order-courier {
    display: flex; align-items: center; gap: 6px;
    padding: 6px 1.25rem; background: var(--gray-50);
    font-size: 12px; color: var(--gray-400);
    border-top: 1px solid var(--gray-100);
}

/* Footer */
.order-card-footer {
    display: flex; justify-content: space-between; align-items: center;
    padding: 0.875rem 1.25rem;
    border-top: 1px solid var(--gray-100);
    background: var(--gray-50);
    flex-wrap: wrap; gap: 10px;
}
.order-total { font-size: 15px; font-weight: 700; color: var(--brown); }
.order-actions { display: flex; gap: 8px; }

.btn-sm {
    padding: 6px 16px; font-size: 13px; border-radius: 8px;
    font-weight: 600; text-decoration: none; cursor: pointer;
    display: inline-flex; align-items: center; gap: 4px;
}
.btn-pay {
    background: var(--pink-600); color: white; border: none;
}
.btn-pay:hover { opacity: 0.88; }
.btn-detail {
    background: white; color: var(--gray-600);
    border: 1px solid var(--gray-200);
}
.btn-detail:hover { border-color: var(--pink-300); color: var(--pink-600); }

/* Count badge */
.orders-count {
    display: inline-block; background: var(--pink-100); color: var(--pink-600);
    font-size: 12px; font-weight: 700; padding: 2px 10px;
    border-radius: 50px; margin-left: 8px; vertical-align: middle;
}
</style>

<div class="orders-wrap">
<div class="orders-container">

    <div class="orders-header">
        <h1>Pesanan Saya <span class="orders-count">{{ $orders->count() }}</span></h1>
        <p>Pantau status dan riwayat belanja kamu di sini</p>
    </div>

    @if($orders->isEmpty())
    <div class="empty-state">
        <span class="empty-icon">📦</span>
        <p>Kamu belum punya pesanan.<br>Yuk mulai belanja!</p>
        <a href="{{ route('shop') }}" class="btn btn-primary">Mulai Belanja</a>
    </div>

    @else
    @php
    $statusLabels = [
        'pending'    => ['⏳ Menunggu',  'badge-pending'],
        'processing' => ['🔄 Diproses',  'badge-processing'],
        'shipped'    => ['🚚 Dikirim',   'badge-shipped'],
        'delivered'  => ['✅ Selesai',   'badge-delivered'],
        'cancelled'  => ['❌ Dibatalkan','badge-cancelled'],
    ];
    @endphp

    <div class="orders-list">
        @foreach($orders as $order)
        @php $badge = $statusLabels[$order->status] ?? ['Unknown', 'badge-pending']; @endphp

        <div class="order-card">

            {{-- Header --}}
            <div class="order-card-header">
                <div>
                    <span class="order-number">#{{ $order->order_number }}</span>
                    <span class="order-date">{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <div class="order-badges">
                    <span class="badge {{ $badge[1] }}">{{ $badge[0] }}</span>
                    <span class="badge {{ $order->payment_status === 'paid' ? 'badge-paid' : 'badge-unpaid' }}">
                        {{ $order->payment_status === 'paid' ? '💳 Lunas' : '⚠️ Belum Bayar' }}
                    </span>
                </div>
            </div>

            {{-- Items --}}
            <div class="order-items-section">
                @foreach($order->items as $item)
                <div class="order-item-row">
                    <div class="order-item-dot"></div>
                    <span>{{ $item->product->name ?? 'Produk' }}</span>
                    <span style="color:var(--gray-400);">{{ $item->size }} × {{ $item->quantity }}</span>
                    <span style="margin-left:auto; font-weight:600; color:var(--gray-700);">
                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
            </div>

            {{-- Kurir --}}
            @if($order->courier)
            <div class="order-courier">
                🚚 {{ $order->courier }}
                @if($order->shipping_cost > 0)
                    · Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                @else
                    · <span style="color:#16a34a; font-weight:600;">GRATIS</span>
                @endif
            </div>
            @endif

            {{-- Footer --}}
            <div class="order-card-footer">
                <span class="order-total">Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                <div class="order-actions">
                    @if($order->payment_status === 'unpaid' && $order->status !== 'cancelled')
                    <a href="{{ route('payment', $order->id) }}" class="btn-sm btn-pay">Bayar Sekarang</a>
                    @endif
                    <a href="{{ route('orders.detail', $order->id) }}" class="btn-sm btn-detail">Lihat Detail</a>
                </div>
            </div>

        </div>
        @endforeach
    </div>
    @endif

</div>
</div>

@endsection