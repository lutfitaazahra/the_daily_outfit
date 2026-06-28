@extends('layouts.admin')

@section('title', 'Pesanan — Admin The Daily Outfit')

@section('content')

<div class="admin-topbar">
    <div>
        <h1>Manajemen Pesanan</h1>
        <p>Kelola semua pesanan masuk</p>
    </div>
</div>

@if(session('success'))
    <div style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; padding:12px 16px; border-radius:10px; margin-bottom:1.5rem; font-size:14px;">
        ✅ {{ session('success') }}
    </div>
@endif

<!-- FILTER STATUS -->
<div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:1.5rem;">
    @php
    $filters = ['' => 'Semua', 'pending' => 'Pending', 'processing' => 'Diproses', 'shipped' => 'Dikirim', 'delivered' => 'Selesai', 'cancelled' => 'Batal'];
    $filterColors = ['pending'=>'#fff7ed,#ea580c','processing'=>'#eff6ff,#2563eb','shipped'=>'#f0f9ff,#0284c7','delivered'=>'#f0fdf4,#16a34a','cancelled'=>'#fef2f2,#dc2626'];
    @endphp
    @foreach($filters as $val => $label)
    @php
    $isActive = request('status') === $val || (request('status') === null && $val === '');
    @endphp
    <a href="{{ route('admin.orders', $val ? ['status' => $val] : []) }}"
       style="padding:8px 18px; border-radius:50px; font-size:13px; font-weight:600; text-decoration:none; transition:all 0.2s;
              {{ $isActive ? 'background:#c94f7c; color:white; box-shadow:0 4px 12px rgba(201,79,124,0.25);' : 'background:white; color:#7a5a63; border:1.5px solid #f3d9e0;' }}">
        {{ $label }}
        @php
        $count = $val === '' ? $orders->count() : $orders->where('status', $val)->count();
        @endphp
        <span style="margin-left:6px; background:{{ $isActive ? 'rgba(255,255,255,0.25)' : '#f3d9e0' }}; color:{{ $isActive ? 'white' : '#c94f7c' }};
                     padding:1px 8px; border-radius:50px; font-size:11px;">{{ $count }}</span>
    </a>
    @endforeach
</div>

<!-- TABEL PESANAN -->
<div class="admin-panel">
    <div class="admin-table-wrap">
        <table class="admin-data-table">
            <thead>
                <tr>
                    <th>No. Order</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Update Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @php
            $statusLabels = [
                'pending'    => ['Pending',  'pill-pending'],
                'processing' => ['Diproses', 'pill-processing'],
                'shipped'    => ['Dikirim',  'pill-shipped'],
                'delivered'  => ['Selesai',  'pill-delivered'],
                'cancelled'  => ['Batal',    'pill-cancelled'],
            ];
            $filteredOrders = request('status') ? $orders->where('status', request('status')) : $orders;
            @endphp
            @forelse($filteredOrders as $order)
            @php $badge = $statusLabels[$order->status] ?? ['Unknown','pill-pending']; @endphp
            <tr>
                <td>
                    <span style="font-size:13px; font-weight:700; color:#5a3825; font-family:monospace;">
                        {{ $order->order_number }}
                    </span>
                </td>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:34px; height:34px; border-radius:50%; background:#fff0f3;
                                    display:flex; align-items:center; justify-content:center;
                                    font-weight:700; font-size:13px; color:#c94f7c; flex-shrink:0;">
                            {{ mb_strtoupper(mb_substr($order->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size:13px; font-weight:600; color:#3a2a2d;">{{ $order->user->name }}</div>
                            <div style="font-size:11px; color:#b09098;">{{ $order->user->email }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span style="font-size:13px; font-weight:700; color:#3a2a2d;">
                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                    </span>
                </td>
                <td>
                    <span class="pill {{ $order->payment_status === 'paid' ? 'pill-paid' : 'pill-unpaid' }}">
                        {{ $order->payment_status === 'paid' ? '✓ Lunas' : '✗ Belum' }}
                    </span>
                </td>
                <td>
                    <span class="pill {{ $badge[1] }}">{{ $badge[0] }}</span>
                </td>
                <td style="font-size:12px; color:#b09098;">
                    {{ $order->created_at->format('d M Y') }}<br>
                    <span style="font-size:11px;">{{ $order->created_at->format('H:i') }}</span>
                </td>
                <td>
                    <form method="POST" action="{{ route('admin.orders.update', $order->id) }}"
                          style="display:flex; gap:6px; align-items:center;">
                        @csrf
                        <select name="status"
                                style="padding:6px 10px; border:1.5px solid #f3d9e0; border-radius:8px;
                                       font-size:12px; color:#5a3825; background:white; cursor:pointer;">
                            @foreach($statusLabels as $val => $lbl)
                            <option value="{{ $val }}" {{ $order->status === $val ? 'selected' : '' }}>{{ $lbl[0] }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="abtn abtn-pink" style="padding:6px 14px; font-size:12px;">
                            Simpan
                        </button>
                    </form>
                </td>
                <td>
                    <a href="{{ route('admin.orders.detail', $order->id) }}" class="abtn abtn-outline">
                        Detail →
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:3rem; color:#b09098;">
                    <div style="font-size:2rem; margin-bottom:0.5rem;">📦</div>
                    Belum ada pesanan.
                </td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection