@extends('layouts.admin')

@section('title', 'Kelola Return — Admin')

@section('content')
<style>
.ar-wrap { padding: 2rem; }
.ar-header { margin-bottom: 1.5rem; }
.ar-header h1 { font-size: 1.5rem; font-weight: 700; color: var(--brown); margin: 0 0 4px; }
.ar-header p { font-size: 13px; color: var(--gray-400); margin: 0; }

.ar-card {
    background: white; border-radius: var(--radius);
    box-shadow: var(--shadow); overflow: hidden;
}

.ar-table { width: 100%; border-collapse: collapse; }
.ar-table th {
    text-align: left; font-size: 12px; font-weight: 700; color: var(--gray-500);
    text-transform: uppercase; padding: 12px 16px; background: var(--pink-50);
    border-bottom: 1px solid var(--gray-100);
}
.ar-table td {
    padding: 14px 16px; font-size: 13px; color: var(--gray-700);
    border-bottom: 1px solid var(--gray-100); vertical-align: top;
}
.ar-table tr:last-child td { border-bottom: none; }

.ar-empty { padding: 3rem; text-align: center; color: var(--gray-400); font-size: 14px; }

.badge {
    display: inline-block; font-size: 11px; font-weight: 600;
    padding: 3px 10px; border-radius: 50px;
}
.badge-pending  { background: #fef9c3; color: #854d0e; }
.badge-approved { background: #dcfce7; color: #166534; }
.badge-rejected { background: #fee2e2; color: #991b1b; }

.ar-reason { max-width: 220px; }
.ar-link { color: var(--brown); font-weight: 600; text-decoration: underline; font-size: 12px; }

.ar-actions { display: flex; flex-direction: column; gap: 6px; min-width: 160px; }
.ar-btn {
    padding: 6px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;
    border: none; cursor: pointer;
}
.ar-btn-approve { background: #16a34a; color: white; }
.ar-btn-reject { background: #dc2626; color: white; }
.ar-note-input {
    width: 100%; padding: 6px 8px; border: 1px solid var(--gray-200);
    border-radius: 6px; font-size: 12px; margin-bottom: 6px;
}
</style>

<div class="ar-wrap">

    <div class="ar-header">
        <h1>↩️ Kelola Request Return</h1>
        <p>Tinjau dan proses permintaan return dari customer.</p>
    </div>

    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
    <div class="alert alert-error" style="margin-bottom:1rem;">{{ session('error') }}</div>
    @endif

    <div class="ar-card">
        @if($returns->isEmpty())
            <div class="ar-empty">Belum ada request return.</div>
        @else
        <table class="ar-table">
            <thead>
                <tr>
                    <th>No Order</th>
                    <th>Customer</th>
                    <th>Alasan</th>
                    <th>Bukti</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($returns as $return)
                <tr>
                    <td style="font-weight:600;">{{ $return->order->order_number }}</td>
                    <td>{{ $return->user->name }}</td>
                    <td class="ar-reason">{{ $return->reason }}</td>
                    <td>
                        @if($return->proof_image)
                            <a href="{{ asset('storage/' . $return->proof_image) }}" target="_blank" class="ar-link">Lihat foto</a>
                        @else
                            <span style="color:var(--gray-400);">-</span>
                        @endif
                    </td>
                    <td>{{ $return->created_at->format('d M Y') }}</td>
                    <td>
                        <span class="badge badge-{{ $return->status }}">{{ ucfirst($return->status) }}</span>
                        @if($return->status === 'rejected' && $return->admin_note)
                            <div style="font-size:11px; color:var(--gray-400); margin-top:4px;">{{ $return->admin_note }}</div>
                        @endif
                    </td>
                    <td>
                        @if($return->status === 'pending')
                        <div class="ar-actions">
                            <form action="{{ route('admin.returns.approve', $return->id) }}" method="POST" onsubmit="return confirm('Setujui return ini? Status pesanan akan berubah jadi returned dan payment jadi refunded.')">
                                @csrf
                                <button type="submit" class="ar-btn ar-btn-approve" style="width:100%;">✓ Approve</button>
                            </form>

                            <form action="{{ route('admin.returns.reject', $return->id) }}" method="POST" onsubmit="return confirm('Tolak return ini?')">
                                @csrf
                                <input type="text" name="admin_note" class="ar-note-input" placeholder="Alasan tolak" required minlength="5">
                                <button type="submit" class="ar-btn ar-btn-reject" style="width:100%;">✕ Reject</button>
                            </form>
                        </div>
                        @else
                            <span style="color:var(--gray-400); font-size:12px;">Sudah diproses</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>
@endsection