@extends('layouts.admin')

@section('title', 'Dashboard — Admin The Daily Outfit')

@section('content')

<div class="admin-topbar">
    <div>
        <h1>Dashboard</h1>
        <p>Selamat datang kembali, {{ auth()->user()->name }} 👋</p>
    </div>
    <div style="display:flex; gap:10px; align-items:center;">
        <a href="{{ route('admin.laporan') }}" class="abtn abtn-pink" style="padding:10px 20px;">📊 Laporan Penjualan</a>
        <div class="admin-user-chip">
            <div class="admin-user-avatar">{{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</div>
            <div>
                <div style="font-size:13px; font-weight:600; color:var(--brown);">{{ auth()->user()->name }}</div>
                <div style="font-size:11px; color:#b09098;">Administrator</div>
            </div>
        </div>
    </div>
</div>

<!-- STATS -->
<div class="admin-stats-grid">
    <a href="{{ route('admin.orders') }}" style="text-decoration:none;">
        <div class="admin-stat-card" style="cursor:pointer; transition:box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow=''">
            <div class="admin-stat-icon" style="background:#fff0f3;">📦</div>
            <div class="admin-stat-label">Total Pesanan</div>
            <div class="admin-stat-value">{{ $stats['total_orders'] }}</div>
        </div>
    </a>
    <a href="{{ route('admin.orders') }}?status=pending" style="text-decoration:none;">
        <div class="admin-stat-card" style="cursor:pointer; transition:box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow=''">
            <div class="admin-stat-icon" style="background:#fff7ed;">⏳</div>
            <div class="admin-stat-label">Pesanan Pending</div>
            <div class="admin-stat-value">{{ $stats['pending_orders'] }}</div>
        </div>
    </a>
    <a href="{{ route('admin.laporan') }}" style="text-decoration:none;">
        <div class="admin-stat-card" style="cursor:pointer; transition:box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow=''">
            <div class="admin-stat-icon" style="background:#f0fdf4;">💰</div>
            <div class="admin-stat-label">Total Revenue</div>
            <div class="admin-stat-value" style="font-size:1.25rem;">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
        </div>
    </a>
    <a href="{{ route('admin.products') }}" style="text-decoration:none;">
        <div class="admin-stat-card" style="cursor:pointer; transition:box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow=''">
            <div class="admin-stat-icon" style="background:#eff6ff;">👗</div>
            <div class="admin-stat-label">Total Produk</div>
            <div class="admin-stat-value">{{ $stats['total_products'] }}</div>
        </div>
    </a>
    <a href="{{ route('admin.orders') }}" style="text-decoration:none;">
        <div class="admin-stat-card" style="cursor:pointer; transition:box-shadow 0.2s;" onmouseover="this.style.boxShadow='0 4px 20px rgba(0,0,0,0.1)'" onmouseout="this.style.boxShadow=''">
            <div class="admin-stat-icon" style="background:#fdf4ff;">👤</div>
            <div class="admin-stat-label">Total Customer</div>
            <div class="admin-stat-value">{{ $stats['total_users'] }}</div>
        </div>
    </a>
</div>

<!-- GRAFIK REVENUE -->
<div class="admin-panel" style="margin-bottom:1.5rem;">
    <div class="admin-panel-header">
        <h3>Revenue 6 Bulan Terakhir</h3>
        <a href="{{ route('admin.laporan') }}" class="abtn abtn-outline">Lihat Laporan Lengkap →</a>
    </div>
    <div style="padding:1.5rem;">
        <canvas id="revenueChart" height="100"></canvas>
    </div>
</div>

<!-- PRODUK TERLARIS & STOK MENIPIS -->
<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">

    <div class="admin-panel">
        <div class="admin-panel-header">
            <h3>Produk Terlaris</h3>
            <a href="{{ route('admin.products') }}" class="abtn abtn-outline">Lihat Semua →</a>
        </div>
        <div style="padding:1rem 1.5rem;">
            @forelse($top_products as $i => $p)
            <div style="display:flex; align-items:center; gap:12px; padding:10px 0; {{ !$loop->last ? 'border-bottom:1px solid #fdf0f3;' : '' }}">
                <div style="width:28px; height:28px; border-radius:50%; background:{{ $i === 0 ? '#ffd700' : ($i === 1 ? '#c0c0c0' : ($i === 2 ? '#cd7f32' : '#f0dde2')) }}; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; color:{{ $i < 3 ? '#fff' : 'var(--brown)' }}; flex-shrink:0;">
                    {{ $i + 1 }}
                </div>
                @if($p->image)
                <img src="{{ str_starts_with($p->image, 'http') ? $p->image : asset('storage/' . $p->image) }}" style="width:40px; height:40px; object-fit:cover; border-radius:8px; flex-shrink:0;">
                @else
                <div style="width:40px; height:40px; background:#fff0f3; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:700; color:#c94f7c; font-size:11px; flex-shrink:0;">
                    {{ mb_substr($p->name, 0, 2) }}
                </div>
                @endif
                <div style="flex:1; min-width:0;">
                    <div style="font-size:13px; font-weight:600; color:var(--brown); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->name }}</div>
                    <div style="font-size:11px; color:#b09098;">Terjual {{ $p->total_sold }} pcs</div>
                </div>
                <div style="font-size:12px; font-weight:600; color:#c94f7c; white-space:nowrap;">
                    Rp {{ number_format($p->total_revenue, 0, ',', '.') }}
                </div>
            </div>
            @empty
            <p style="text-align:center; color:#b09098; padding:2rem 0; font-size:13px;">Belum ada data penjualan.</p>
            @endforelse
        </div>
    </div>

    <div class="admin-panel">
        <div class="admin-panel-header">
            <h3>⚠️ Stok Menipis</h3>
            <a href="{{ route('admin.products') }}" class="abtn abtn-outline">Kelola →</a>
        </div>
        <div style="padding:1rem 1.5rem;">
            @forelse($low_stock as $p)
            <div style="display:flex; align-items:center; gap:12px; padding:10px 0; {{ !$loop->last ? 'border-bottom:1px solid #fdf0f3;' : '' }}">
                @if($p->image)
                <img src="{{ str_starts_with($p->image, 'http') ? $p->image : asset('storage/' . $p->image) }}" style="width:40px; height:40px; object-fit:cover; border-radius:8px; flex-shrink:0;">
                @else
                <div style="width:40px; height:40px; background:#fff0f3; border-radius:8px; display:flex; align-items:center; justify-content:center; font-weight:700; color:#c94f7c; font-size:11px; flex-shrink:0;">
                    {{ mb_substr($p->name, 0, 2) }}
                </div>
                @endif
                <div style="flex:1; min-width:0;">
                    <div style="font-size:13px; font-weight:600; color:var(--brown); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $p->name }}</div>
                    <div style="font-size:11px; color:#b09098;">Sisa stok</div>
                </div>
                <span style="padding:4px 10px; border-radius:20px; font-size:12px; font-weight:700;
                    background:{{ $p->stock == 0 ? '#fef2f2' : '#fff7ed' }};
                    color:{{ $p->stock == 0 ? '#dc2626' : '#ea580c' }};">
                    {{ $p->stock == 0 ? 'Habis' : $p->stock . ' pcs' }}
                </span>
            </div>
            @empty
            <p style="text-align:center; color:#b09098; padding:2rem 0; font-size:13px;">Semua stok aman ✓</p>
            @endforelse
        </div>
    </div>
</div>

<!-- RECENT ORDERS -->
<div class="admin-panel">
    <div class="admin-panel-header">
        <h3>Pesanan Terbaru</h3>
        <a href="{{ route('admin.orders') }}" class="abtn abtn-outline">Lihat Semua →</a>
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
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @php
            $statusLabels = ['pending'=>['Pending','pill-pending'],'processing'=>['Diproses','pill-processing'],'shipped'=>['Dikirim','pill-shipped'],'delivered'=>['Selesai','pill-delivered'],'cancelled'=>['Batal','pill-cancelled']];
            @endphp
            @forelse($recent_orders as $order)
            @php $badge = $statusLabels[$order->status] ?? ['Unknown','pill-pending']; @endphp
            <tr style="cursor:pointer;" onclick="window.location='{{ route('admin.orders.detail', $order->id) }}'">
                <td><strong>{{ $order->order_number }}</strong></td>
                <td>{{ $order->user->name }}</td>
                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td><span class="pill {{ $order->payment_status === 'paid' ? 'pill-paid' : 'pill-unpaid' }}">{{ $order->payment_status === 'paid' ? 'Lunas' : 'Belum' }}</span></td>
                <td><span class="pill {{ $badge[1] }}">{{ $badge[0] }}</span></td>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td><a href="{{ route('admin.orders.detail', $order->id) }}" class="abtn abtn-outline" onclick="event.stopPropagation()">Detail</a></td>
            </tr>
            @empty
            <tr><td colspan="7" style="text-align:center; padding:2rem; color:#b09098;">Belum ada pesanan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($revenue_chart->pluck('month')->map(fn($m) => \Carbon\Carbon::parse($m)->translatedFormat('M Y')));
    const data   = @json($revenue_chart->pluck('total'));

    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue (Rp)',
                data: data,
                borderColor: '#c94f7c',
                backgroundColor: 'rgba(201,79,124,0.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#c94f7c',
                pointRadius: 5,
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID') } }
            },
            scales: {
                y: { beginAtZero: true, ticks: { callback: val => 'Rp ' + val.toLocaleString('id-ID') }, grid: { color: '#fdf0f3' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>

@endsection