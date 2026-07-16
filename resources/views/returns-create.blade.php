@extends('layouts.app')

@section('title', 'Ajukan Return — The Daily Outfit')

@section('content')
<style>
.rc-wrap { background: var(--pink-50); min-height: 100vh; padding: 2rem 0; }
.rc-container { max-width: 600px; margin: 0 auto; padding: 0 1rem; }
.rc-card { background: white; border-radius: var(--radius); box-shadow: var(--shadow); padding: 1.5rem; }
.rc-card h1 { font-size: 1.3rem; font-weight: 700; color: var(--brown); margin: 0 0 4px; }
.rc-card p.sub { font-size: 13px; color: var(--gray-400); margin: 0 0 1.5rem; }
.rc-field { margin-bottom: 1.25rem; }
.rc-field label { display:block; font-size:13px; font-weight:600; color:var(--gray-700); margin-bottom:6px; }
.rc-field textarea, .rc-field input[type=file] {
    width: 100%; border: 1.5px solid var(--gray-200); border-radius: 8px;
    padding: 10px 12px; font-size: 14px; font-family: inherit;
}
.rc-field textarea { min-height: 100px; resize: vertical; }
.rc-actions { display:flex; gap:10px; margin-top:1.5rem; }
</style>

<div class="rc-wrap">
<div class="rc-container">
    <div class="rc-card">
        <h1>↩️ Ajukan Return</h1>
        <p class="sub">Pesanan #{{ $order->order_number }}</p>

        @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1rem;">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('returns.store', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="rc-field">
                <label>Alasan Return <span style="color:#dc2626;">*</span></label>
                <textarea name="reason" placeholder="Contoh: Ukuran tidak sesuai, barang cacat, dll." required>{{ old('reason') }}</textarea>
            </div>

            <div class="rc-field">
                <label>Foto Bukti (opsional)</label>
                <input type="file" name="proof_image" accept="image/*">
            </div>

            <div class="rc-actions">
                <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center; background:#7c3aed; border-color:#7c3aed;">
                    Kirim Pengajuan
                </button>
                <a href="{{ route('orders.detail', $order->id) }}" class="btn btn-outline" style="flex:1; justify-content:center;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
</div>
@endsection