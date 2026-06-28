@extends('layouts.admin')

@section('title', 'Laporan Penjualan — Admin The Daily Outfit')

@section('content')

<div class="admin-topbar">
    <div>
        <h1>Laporan Penjualan</h1>
        <p>Filter dan export data penjualan</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="abtn abtn-outline" style="padding:10px 20px;">← Dashboard</a>
</div>

{{-- FILTER --}}
<div class="admin-panel" style="margin-bottom:1.5rem;">
    <div class="admin-panel-header"><h3>🔍 Filter Laporan</h3></div>
    <form method="GET" action="{{ route('admin.laporan') }}" style="padding:1.25rem 1.5rem; display:flex; gap:1rem; flex-wrap:wrap; align-items:flex-end;">
        <div>
            <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; margin-bottom:4px;">DARI TANGGAL</label>
            <input type="date" name="dari" value="{{ $dari }}"
                   style="padding:9px 14px; border:1px solid #f0dde2; border-radius:10px; font-size:14px;">
        </div>
        <div>
            <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; margin-bottom:4px;">SAMPAI TANGGAL</label>
            <input type="date" name="sampai" value="{{ $sampai }}"
                   style="padding:9px 14px; border:1px solid #f0dde2; border-radius:10px; font-size:14px;">
        </div>
        <div>
            <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; margin-bottom:4px;">STATUS PEMBAYARAN</label>
            <select name="status" style="padding:9px 14px; border:1px solid #f0dde2; border-radius:10px; font-size:14px; background:white;">
                <option value="paid"   {{ $status === 'paid'   ? 'selected' : '' }}>Lunas</option>
                <option value="unpaid" {{ $status === 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="all"    {{ $status === 'all'    ? 'selected' : '' }}>Semua</option>
            </select>
        </div>
        <button type="submit" class="abtn abtn-pink" style="padding:10px 24px;">Tampilkan</button>
        <a href="{{ route('admin.laporan', array_merge(request()->all(), ['export' => 'csv'])) }}"
           class="abtn abtn-outline" style="padding:10px 24px;">⬇️ Export CSV</a>
    </form>
</div>

{{-- SUMMARY STATS --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem;">
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background:#fff0f3;">🧾</div>
        <div class="admin-stat-label">Total Transaksi</div>
        <div class="admin-stat-value">{{ $laporanStats['total_transaksi'] }}</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background:#f0fdf4;">💰</div>
        <div class="admin-stat-label">Total Revenue</div>
        <div class="admin-stat-value" style="font-size:1.1rem;">Rp {{ number_format($laporanStats['total_revenue'], 0, ',', '.') }}</div>
    </div>
    <div class="admin-stat-card">
        <div class="admin-stat-icon" style="background:#eff6ff;">📊</div>
        <div class="admin-stat-label">Rata-rata Transaksi</div>
        <div class="admin-stat-value" style="font-size:1.1rem;">Rp {{ number_format($laporanStats['rata_rata'], 0, ',', '.') }}</div>
    </div>
</div>

{{-- TABEL --}}
<div class="admin-panel">
    <div class="admin-panel-header">
        <h3>📋 Data Penjualan ({{ $dari }} s/d {{ $sampai }})</h3>
        <span style="font-size:13px; color:#b09098;">{{ $orders->count() }} transaksi</span>
    </div>
    <div class="admin-table-wrap">
        <table class="admin-data-table">
            <thead>
                <tr>
                    <th>No. Order</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Pembayaran</th>
                    <th>Status</th>
                    <th>Kurir</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @php
            $statusLabels = ['pending'=>['Pending','pill-pending'],'processing'=>['Diproses','pill-processing'],'shipped'=>['Dikirim','pill-shipped'],'delivered'=>['Selesai','pill-delivered'],'cancelled'=>['Batal','pill-cancelled']];
            @endphp
            @forelse($orders as $order)
            @php $badge = $statusLabels[$order->status] ?? ['Unknown','pill-pending']; @endphp
            <tr style="cursor:pointer;" onclick="window.location='{{ route('admin.orders.detail', $order->id) }}'">
                <td><strong>{{ $order->order_number }}</strong></td>
                <td>{{ $order->user->name ?? '-' }}</td>
                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td><span class="pill {{ $order->payment_status === 'paid' ? 'pill-paid' : 'pill-unpaid' }}">{{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum' }}</span></td>
                <td><span class="pill {{ $badge[1] }}">{{ $badge[0] }}</span></td>
                <td style="font-size:12px; color:#b09098;">{{ $order->courier ?? '-' }}</td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td><a href="{{ route('admin.orders.detail', $order->id) }}" class="abtn abtn-outline" onclick="event.stopPropagation()">Detail</a></td>
            </tr>
            @empty
            <tr><td colspan="8" style="text-align:center; padding:2rem; color:#b09098;">Tidak ada data untuk periode ini.</td></tr>
            @endforelse
            </tbody>
            @if($orders->count() > 0)
            <tfoot>
                <tr style="background:#fff0f3;">
                    <td colspan="2" style="font-weight:700; padding:12px 16px; color:var(--brown);">TOTAL</td>
                    <td style="font-weight:700; color:var(--brown);">Rp {{ number_format($laporanStats['total_revenue'], 0, ',', '.') }}</td>
                    <td colspan="5"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection