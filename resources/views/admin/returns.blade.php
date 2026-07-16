@extends('layouts.admin')

@section('title', 'Daftar Retur — Admin')

@section('content')
<div class="admin-topbar">
    <h1>Daftar Pengajuan Retur</h1>
</div>

@if(session('success'))
<div style="background:#f0fdf4; color:#166534; border:1px solid #bbf7d0; padding:12px 16px; border-radius:10px; margin-bottom:1.5rem; font-size:14px;">
    ✅ {{ session('success') }}
</div>
@endif
@if(session('error'))
<div style="background:#fef2f2; color:#991b1b; border:1px solid #fecaca; padding:12px 16px; border-radius:10px; margin-bottom:1.5rem; font-size:14px;">
    ⚠️ {{ session('error') }}
</div>
@endif

@php
$retLabels = [
    'pending'        => ['Menunggu Review', '#f59e0b'],
    'approved'       => ['Disetujui', '#3b82f6'],
    'rejected'       => ['Ditolak', '#ef4444'],
    'item_received'  => ['Barang Diterima', '#8b5cf6'],
    'refunded'       => ['Dana Dikembalikan', '#16a34a'],
];
@endphp

<div class="admin-panel">
    <div style="padding:1rem 1.5rem;">
        @forelse($returns as $ret)
        @php $badge = $retLabels[$ret->status] ?? ['Unknown', '#999']; @endphp
        <div style="display:flex; align-items:center; justify-content:space-between; padding:14px 0; border-bottom:1px solid #f3e9eb;">
            <div>
                <div style="font-size:14px; font-weight:700; color:#3a2a2d;">{{ $ret->order->order_number }}</div>
                <div style="font-size:13px; color:#b09098;">{{ $ret->user->name }} — {{ $ret->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div style="display:flex; align-items:center; gap:1rem;">
                <span style="font-size:12px; font-weight:700; color:{{ $badge[1] }};">{{ $badge[0] }}</span>
                <a href="{{ route('admin.orders.detail', $ret->order_id) }}" class="abtn abtn-outline">Lihat Detail</a>
            </div>
        </div>
        @empty
        <div style="padding:2rem; text-align:center; color:#b09098;">Belum ada pengajuan retur.</div>
        @endforelse
    </div>
</div>
@endsection