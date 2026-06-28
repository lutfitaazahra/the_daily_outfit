@extends('layouts.admin')

@section('title', 'Detail Pesanan — Admin')

@section('content')

<div class="admin-topbar">
    <div>
        <h1>Detail Pesanan</h1>
        <p style="font-family:monospace; font-size:13px;">{{ $order->order_number }}</p>
    </div>
    <a href="{{ route('admin.orders') }}" class="abtn abtn-outline">← Kembali</a>
</div>

@if(session('success'))
    <div style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; padding:12px 16px; border-radius:10px; margin-bottom:1.5rem; font-size:14px;">
        ✅ {{ session('success') }}
    </div>
@endif

@php
$statusLabels = ['pending'=>['Pending','pill-pending'],'processing'=>['Diproses','pill-processing'],'shipped'=>['Dikirim','pill-shipped'],'delivered'=>['Selesai','pill-delivered'],'cancelled'=>['Batal','pill-cancelled']];
$badge = $statusLabels[$order->status] ?? ['Unknown','pill-pending'];
@endphp

<!-- STATUS BAR -->
<div style="background:white; border-radius:16px; padding:1.25rem 1.5rem; margin-bottom:1.5rem;
            box-shadow:0 2px 12px rgba(0,0,0,0.04); display:flex; align-items:center; gap:2rem; flex-wrap:wrap;">
    <div style="display:flex; align-items:center; gap:8px;">
        <span style="font-size:12px; color:#b09098; font-weight:500;">STATUS</span>
        <span class="pill {{ $badge[1] }}" style="font-size:13px; padding:5px 14px;">{{ $badge[0] }}</span>
    </div>
    <div style="display:flex; align-items:center; gap:8px;">
        <span style="font-size:12px; color:#b09098; font-weight:500;">PEMBAYARAN</span>
        <span class="pill {{ $order->payment_status === 'paid' ? 'pill-paid' : 'pill-unpaid' }}" style="font-size:13px; padding:5px 14px;">
            {{ $order->payment_status === 'paid' ? '✓ Lunas' : '✗ Belum Bayar' }}
        </span>
    </div>
    <div style="display:flex; align-items:center; gap:8px;">
        <span style="font-size:12px; color:#b09098; font-weight:500;">TANGGAL</span>
        <span style="font-size:13px; font-weight:600; color:#5a3825;">{{ $order->created_at->format('d M Y, H:i') }}</span>
    </div>
    <div style="display:flex; align-items:center; gap:8px;">
        <span style="font-size:12px; color:#b09098; font-weight:500;">METODE BAYAR</span>
        <span style="font-size:13px; font-weight:600; color:#5a3825;">{{ ucfirst($order->payment_method) }}</span>
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 340px; gap:1.5rem; align-items:start;">

    <!-- KIRI -->
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

        <!-- INFO CUSTOMER -->
        <div class="admin-panel">
            <div class="admin-panel-header">
                <h3>👤 Info Customer</h3>
            </div>
            <div style="padding:1.5rem;">
                <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem;">
                    <div style="width:48px; height:48px; border-radius:50%; background:#fff0f3;
                                display:flex; align-items:center; justify-content:center;
                                font-weight:700; font-size:18px; color:#c94f7c; flex-shrink:0;">
                        {{ mb_strtoupper(mb_substr($order->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-size:15px; font-weight:700; color:#3a2a2d;">{{ $order->user->name }}</div>
                        <div style="font-size:13px; color:#b09098;">{{ $order->user->email }}</div>
                        <div style="font-size:13px; color:#b09098;">{{ $order->user->phone ?? '-' }}</div>
                    </div>
                </div>
                <div style="background:#fdfafb; border-radius:10px; padding:1rem; border:1px solid #f3e9eb;">
                    <div style="font-size:12px; font-weight:600; color:#b09098; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">📍 Alamat Pengiriman</div>
                    <div style="font-size:14px; color:#5a4a4d; line-height:1.6;">{{ $order->shipping_address }}</div>
                </div>
                @if($order->notes)
                <div style="margin-top:1rem; background:#fffbeb; border-radius:10px; padding:1rem; border:1px solid #fde68a;">
                    <div style="font-size:12px; font-weight:600; color:#92400e; margin-bottom:4px;">📝 Catatan</div>
                    <div style="font-size:13px; color:#78350f;">{{ $order->notes }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- ITEM PESANAN -->
        <div class="admin-panel">
            <div class="admin-panel-header">
                <h3>🛍️ Item Pesanan</h3>
                <span style="font-size:13px; color:#b09098;">{{ $order->items->count() }} item</span>
            </div>
            <div style="padding:1rem 1.5rem;">
                @foreach($order->items as $item)
                <div style="display:flex; align-items:center; gap:1rem; padding:12px 0;
                            border-bottom:1px solid #f3e9eb;">
                    <div style="width:52px; height:52px; border-radius:10px; overflow:hidden;
                                background:#fff0f3; flex-shrink:0; display:flex; align-items:center; justify-content:center;">
                        @if($item->product && $item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                        <span style="font-size:20px;">👗</span>
                        @endif
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:14px; font-weight:600; color:#3a2a2d;">{{ $item->product->name ?? '-' }}</div>
                        <div style="font-size:12px; color:#b09098; margin-top:2px;">
                            Ukuran: <strong>{{ $item->size }}</strong> &nbsp;·&nbsp; Qty: <strong>{{ $item->quantity }}</strong>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-size:13px; color:#b09098;">Rp {{ number_format($item->price, 0, ',', '.') }} × {{ $item->quantity }}</div>
                        <div style="font-size:14px; font-weight:700; color:#5a3825;">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                    </div>
                </div>
                @endforeach
            </div>
            <div style="padding:1rem 1.5rem; border-top:1px solid #f3e9eb; background:#fdfafb;">
                <div style="display:flex; justify-content:space-between; font-size:13px; color:#b09098; margin-bottom:6px;">
                    <span>Ongkir</span>
                    <span>Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:16px; font-weight:700; color:#5a3825;">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- KANAN -->
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

        <!-- UPDATE STATUS -->
        <div class="admin-panel">
            <div class="admin-panel-header">
                <h3>⚙️ Update Status</h3>
            </div>
            <div style="padding:1.5rem;">
                <form method="POST" action="{{ route('admin.orders.update', $order->id) }}">
                    @csrf
                    <div style="margin-bottom:1rem;">
                        <label style="font-size:12px; font-weight:600; color:#b09098; text-transform:uppercase; letter-spacing:1px; display:block; margin-bottom:8px;">Status Pesanan</label>
                        <select name="status"
                                style="width:100%; padding:10px 14px; border:1.5px solid #f3d9e0; border-radius:10px;
                                       font-size:14px; color:#5a3825; background:white; cursor:pointer;">
                            @foreach(['pending'=>'Pending','processing'=>'Diproses','shipped'=>'Dikirim','delivered'=>'Selesai','cancelled'=>'Batal'] as $val => $label)
                            <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="abtn abtn-pink" style="width:100%; justify-content:center; padding:12px;">
                        Simpan Perubahan
                    </button>
                </form>

                <!-- TIMELINE STATUS -->
                <div style="margin-top:1.5rem; border-top:1px solid #f3e9eb; padding-top:1.25rem;">
                    <div style="font-size:12px; font-weight:600; color:#b09098; text-transform:uppercase; letter-spacing:1px; margin-bottom:1rem;">Alur Status</div>
                    @php
                    $steps = ['pending'=>'📋 Pending','processing'=>'⚙️ Diproses','shipped'=>'🚚 Dikirim','delivered'=>'✅ Selesai'];
                    $stepKeys = array_keys($steps);
                    $currentIdx = array_search($order->status, $stepKeys);
                    @endphp
                    @foreach($steps as $key => $label)
                    @php $idx = array_search($key, $stepKeys); $done = $currentIdx !== false && $idx <= $currentIdx; @endphp
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom:10px;">
                        <div style="width:28px; height:28px; border-radius:50%; flex-shrink:0;
                                    background:{{ $done ? '#c94f7c' : '#f3e9eb' }};
                                    display:flex; align-items:center; justify-content:center;
                                    font-size:12px; color:{{ $done ? 'white' : '#b09098' }}; font-weight:700;">
                            {{ $done ? '✓' : ($idx + 1) }}
                        </div>
                        <span style="font-size:13px; font-weight:{{ $done ? '600' : '400' }}; color:{{ $done ? '#5a3825' : '#b09098' }};">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- INFO PEMBAYARAN -->
        <div class="admin-panel">
            <div class="admin-panel-header">
                <h3>💳 Info Pembayaran</h3>
            </div>
            <div style="padding:1.5rem;">
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:13px; color:#b09098;">Metode</span>
                        <span style="font-size:13px; font-weight:600; color:#5a3825;">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:13px; color:#b09098;">Status</span>
                        <span class="pill {{ $order->payment_status === 'paid' ? 'pill-paid' : 'pill-unpaid' }}">
                            {{ $order->payment_status === 'paid' ? '✓ Lunas' : '✗ Belum Bayar' }}
                        </span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:13px; color:#b09098;">Total</span>
                        <span style="font-size:14px; font-weight:700; color:#5a3825;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($order->payment && $order->payment->proof_image)
                <div style="margin-top:1.25rem; border-top:1px solid #f3e9eb; padding-top:1.25rem;">
                    <div style="font-size:12px; font-weight:600; color:#b09098; margin-bottom:8px;">Bukti Transfer</div>
                    <img src="{{ asset('storage/' . $order->payment->proof_image) }}"
                         style="width:100%; border-radius:10px; border:1px solid #f3e9eb;">
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection