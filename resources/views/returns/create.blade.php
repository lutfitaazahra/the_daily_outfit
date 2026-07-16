@extends('layouts.app')

@section('title', 'Ajukan Return — The Daily Outfit')

@section('content')
<style>
.rc-wrap { background: var(--pink-50); min-height: 100vh; padding: 2rem 0; }
.rc-container { max-width: 600px; margin: 0 auto; padding: 0 1rem; }

.rc-header { margin-bottom: 1.5rem; }
.rc-header h1 { font-size: 1.5rem; font-weight: 700; color: var(--brown); margin: 0 0 4px; }
.rc-header p { font-size: 13px; color: var(--gray-400); margin: 0; }

.rc-card {
    background: white; border-radius: var(--radius);
    box-shadow: var(--shadow); padding: 1.5rem; margin-bottom: 1rem;
}

.rc-order-info {
    display: flex; justify-content: space-between; align-items: center;
    padding-bottom: 1rem; margin-bottom: 1.25rem;
    border-bottom: 1px solid var(--gray-100);
}
.rc-order-info .num { font-size: 14px; font-weight: 700; color: var(--gray-800); }
.rc-order-info .date { font-size: 12px; color: var(--gray-400); }

.rc-label {
    display: block; font-size: 13px; font-weight: 600;
    color: var(--gray-700); margin-bottom: 6px;
}
.rc-textarea, .rc-file {
    width: 100%; padding: 10px 12px; border: 1.5px solid var(--gray-200);
    border-radius: 8px; font-size: 14px; font-family: inherit;
}
.rc-textarea { resize: vertical; min-height: 100px; }
.rc-hint { font-size: 12px; color: var(--gray-400); margin-top: 4px; }
.rc-error { font-size: 12px; color: #dc2626; margin-top: 4px; }

.rc-actions { display: flex; gap: 10px; margin-top: 1.5rem; }
</style>

<div class="rc-wrap">
<div class="rc-container">

    <div class="rc-header">
        <h1>↩️ Ajukan Return</h1>
        <p>Jelaskan alasan return, tim kami akan meninjau permintaanmu.</p>
    </div>

    @if(session('error'))
    <div class="alert alert-error" style="margin-bottom:1rem;">{{ session('error') }}</div>
    @endif

    <div class="rc-card">
        <div class="rc-order-info">
            <span class="num">Pesanan #{{ $order->order_number }}</span>
            <span class="date">{{ $order->created_at->format('d M Y') }}</span>
        </div>

        <form action="{{ route('returns.store', $order->id) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom:1.25rem;">
                <label class="rc-label">Alasan Return</label>
                <textarea name="reason" class="rc-textarea" placeholder="Contoh: ukuran tidak sesuai, barang cacat/rusak, warna berbeda dari gambar, dll." minlength="10" required>{{ old('reason') }}</textarea>
                @error('reason')
                    <div class="rc-error">{{ $message }}</div>
                @enderror
                <div class="rc-hint">Minimal 10 karakter, jelaskan sedetail mungkin.</div>
            </div>

            <div style="margin-bottom:0.5rem;">
                <label class="rc-label">Foto Bukti (opsional)</label>
                <input type="file" name="proof_image" class="rc-file" accept="image/*">
                @error('proof_image')
                    <div class="rc-error">{{ $message }}</div>
                @enderror
                <div class="rc-hint">Format JPG/PNG, maksimal 2MB.</div>
            </div>

            <div class="rc-actions">
                <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;">
                    Kirim Request Return
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