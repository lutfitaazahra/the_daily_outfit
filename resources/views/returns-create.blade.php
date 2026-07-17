@extends('layouts.app')

@section('title', 'Ajukan Retur — The Daily Outfit')

@section('content')
<style>
.ret-wrap { background: var(--pink-50); min-height: 100vh; padding: 2rem 0; }
.ret-container { max-width: 600px; margin: 0 auto; padding: 0 1rem; }

.ret-header { margin-bottom: 1.5rem; }
.ret-header .ret-back {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 13px; color: var(--gray-400); text-decoration: none;
    margin-bottom: 10px;
}
.ret-header .ret-back:hover { color: var(--pink-600); }
.ret-header h1 {
    font-family: var(--font); font-size: 1.5rem; font-weight: 700;
    color: var(--brown); margin: 0 0 4px;
}
.ret-header p { font-size: 13px; color: var(--gray-400); margin: 0; }

.ret-card {
    background: white; border-radius: var(--radius);
    box-shadow: var(--shadow); padding: 1.75rem;
}

.ret-notice {
    display: flex; gap: 10px; align-items: flex-start;
    background: var(--pink-50); border: 1px solid #fce7f3;
    border-radius: 12px; padding: 12px 14px; margin-bottom: 1.5rem;
    font-size: 12.5px; color: var(--pink-600); line-height: 1.6;
}

.ret-label {
    font-size: 13px; font-weight: 700; color: var(--gray-800);
    display: block; margin-bottom: 8px;
}
.ret-label .optional {
    font-weight: 400; color: var(--gray-400); font-size: 12px;
}

.ret-textarea {
    width: 100%; padding: 12px 14px; border: 1.5px solid var(--gray-200);
    border-radius: 12px; font-size: 14px; font-family: inherit;
    resize: vertical; box-sizing: border-box; color: var(--gray-800);
    transition: border-color 0.2s;
}
.ret-textarea:focus { outline: none; border-color: var(--pink-400); }

.ret-charcount { text-align: right; font-size: 11px; color: var(--gray-400); margin-top: 4px; }

.ret-upload {
    border: 1.5px dashed var(--gray-200); border-radius: 12px;
    padding: 20px; text-align: center; cursor: pointer;
    transition: border-color 0.2s, background 0.2s; position: relative;
}
.ret-upload:hover { border-color: var(--pink-300); background: var(--pink-50); }
.ret-upload input[type="file"] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
}
.ret-upload-icon { font-size: 1.75rem; margin-bottom: 6px; display: block; }
.ret-upload-text { font-size: 13px; color: var(--gray-600); font-weight: 600; }
.ret-upload-sub { font-size: 11.5px; color: var(--gray-400); margin-top: 2px; }
.ret-filename {
    margin-top: 10px; font-size: 12.5px; color: var(--pink-600);
    font-weight: 600; display: none;
}

.ret-submit {
    width: 100%; justify-content: center; padding: 14px;
    margin-top: 1.75rem; font-size: 14px; font-weight: 700;
}

.ret-errors {
    background: #fef2f2; color: #991b1b; border: 1px solid #fecaca;
    padding: 12px 16px; border-radius: 12px; margin-bottom: 1.5rem; font-size: 13px;
}
.ret-errors div { padding: 2px 0; }
</style>

<div class="ret-wrap">
<div class="ret-container">

    <div class="ret-header">
        <a href="{{ route('orders.detail', $order->id) }}" class="ret-back">← Kembali ke Detail Pesanan</a>
        <h1>↩️ Ajukan Retur</h1>
        <p>Pesanan #{{ $order->order_number }}</p>
    </div>

    @if($errors->any())
    <div class="ret-errors">
        @foreach($errors->all() as $error)
        <div>⚠️ {{ $error }}</div>
        @endforeach
    </div>
    @endif

    <div class="ret-card">

        <div class="ret-notice">
            💡 Ceritakan kondisi barang yang kamu terima selengkap mungkin. Sertakan foto bukti bila memungkinkan agar tim kami bisa meninjau pengajuanmu lebih cepat.
        </div>

        <form method="POST" action="{{ route('returns.store', $order->id) }}" enctype="multipart/form-data">
            @csrf

            <div style="margin-bottom: 1.5rem;">
                <label class="ret-label">Alasan Retur</label>
                <textarea name="reason" id="ret-reason" required minlength="10" maxlength="1000" rows="5"
                          placeholder="Contoh: Ukuran baju tidak sesuai dengan panduan ukuran, terlalu kecil di bagian lengan..."
                          class="ret-textarea">{{ old('reason') }}</textarea>
                <div class="ret-charcount"><span id="ret-charnum">0</span>/1000 karakter</div>
            </div>

            <div>
                <label class="ret-label">Foto Bukti <span class="optional">(opsional, maks 2MB)</span></label>
                <label class="ret-upload" id="ret-upload-box">
                    <input type="file" name="proof_image" accept="image/*" id="ret-file-input">
                    <span class="ret-upload-icon">📷</span>
                    <div class="ret-upload-text">Klik untuk unggah foto</div>
                    <div class="ret-upload-sub">JPG, PNG, atau WebP</div>
                </label>
                <div class="ret-filename" id="ret-filename"></div>
            </div>

            <button type="submit" class="btn btn-dark ret-submit">
                Kirim Pengajuan Retur
            </button>
        </form>

    </div>

</div>
</div>

<script>
const reasonEl = document.getElementById('ret-reason');
const charnumEl = document.getElementById('ret-charnum');
if (reasonEl) {
    const updateCount = () => charnumEl.textContent = reasonEl.value.length;
    reasonEl.addEventListener('input', updateCount);
    updateCount();
}

const fileInput = document.getElementById('ret-file-input');
const filenameEl = document.getElementById('ret-filename');
const uploadBox = document.getElementById('ret-upload-box');
if (fileInput) {
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            filenameEl.textContent = '✓ ' + this.files[0].name;
            filenameEl.style.display = 'block';
            uploadBox.style.borderColor = 'var(--pink-400)';
        } else {
            filenameEl.style.display = 'none';
        }
    });
}
</script>
@endsection