@extends('layouts.app')

@section('title', 'Pembayaran — The Daily Outfit')

@section('content')
<style>
.pay-wrap { background: var(--pink-50); min-height: 100vh; padding: 2rem 0; }
.pay-container { max-width: 960px; margin: 0 auto; padding: 0 1rem; }

.pay-header { margin-bottom: 1.5rem; }
.pay-header h1 { font-size: 1.75rem; font-weight: 700; color: var(--brown); margin: 0 0 4px; }
.pay-header p { font-size: 13px; color: var(--gray-400); margin: 0; }

.pay-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
@media(max-width:720px){ .pay-grid { grid-template-columns: 1fr; } }

.pay-card {
    background: white;
    border-radius: var(--radius);
    padding: 1.5rem;
    box-shadow: var(--shadow);
    margin-bottom: 1rem;
}
.pay-card-title {
    display: flex; align-items: center; gap: 8px;
    font-size: 15px; font-weight: 700; color: var(--gray-800);
    margin-bottom: 1.25rem;
    padding-bottom: 0.75rem;
    border-bottom: 1px solid var(--gray-100);
}

.order-item {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 0; border-bottom: 1px solid var(--gray-100);
    font-size: 14px;
}
.order-item-name { color: var(--gray-800); font-weight: 500; }
.order-item-sub  { font-size: 12px; color: var(--gray-400); margin-top: 2px; }
.order-item-price{ font-weight: 600; color: var(--gray-800); white-space: nowrap; }

.summary-row {
    display: flex; justify-content: space-between;
    font-size: 14px; color: var(--gray-600); padding: 6px 0;
}
.summary-total {
    display: flex; justify-content: space-between;
    font-size: 16px; font-weight: 700; color: var(--brown);
    padding: 12px 0 0; border-top: 2px solid var(--gray-100); margin-top: 6px;
}
.badge-free {
    background: #f0fdf4; color: #16a34a;
    font-size: 11px; font-weight: 700;
    padding: 2px 10px; border-radius: 50px;
}

.bank-item {
    display: flex; align-items: center; gap: 12px;
    background: var(--gray-50); border-radius: 10px;
    padding: 12px 16px; margin-bottom: 8px;
}
.bank-logo {
    width: 44px; height: 44px; border-radius: 10px;
    background: var(--pink-50); display: flex;
    align-items: center; justify-content: center;
    font-weight: 700; font-size: 11px; color: var(--pink-600);
    flex-shrink: 0;
}
.bank-detail { flex: 1; }
.bank-name-text { font-size: 13px; font-weight: 700; color: var(--gray-800); }
.bank-no-text   { font-size: 15px; font-weight: 700; color: var(--gray-800); letter-spacing: 1px; margin: 2px 0; }
.bank-an-text   { font-size: 11px; color: var(--gray-400); }
.copy-btn {
    background: none; border: 1px solid var(--gray-200); border-radius: 6px;
    padding: 4px 10px; font-size: 11px; color: var(--gray-500);
    cursor: pointer; flex-shrink: 0; transition: all 0.15s;
}
.copy-btn:hover { background: var(--pink-50); color: var(--pink-600); border-color: var(--pink-200); }

.total-highlight {
    background: var(--pink-50); border: 1px dashed var(--pink-300);
    border-radius: 10px; padding: 12px 16px; margin-top: 12px;
    font-size: 14px; color: var(--gray-600); text-align: center;
}
.total-highlight strong { color: var(--brown); font-size: 18px; }

.upload-area {
    border: 2px dashed var(--gray-200); border-radius: 12px;
    padding: 2rem 1rem; text-align: center; cursor: pointer;
    transition: all 0.2s; background: var(--gray-50);
}
.upload-area:hover { border-color: var(--pink-400); background: var(--pink-50); }
.upload-icon { font-size: 2.5rem; margin-bottom: 8px; }
.upload-area p { font-size: 14px; color: var(--gray-600); margin: 4px 0; }
.upload-area small { font-size: 12px; color: var(--gray-400); }

.btn-confirm {
    width: 100%; padding: 14px; margin-top: 1rem;
    background: var(--pink-600); color: white; border: none;
    border-radius: 10px; font-size: 15px; font-weight: 700;
    cursor: pointer; transition: opacity 0.2s;
}
.btn-confirm:hover { opacity: 0.88; }

/* Tombol batalkan di halaman payment */
.btn-cancel-payment {
    width: 100%; padding: 12px; margin-top: 10px;
    background: white; color: #dc2626;
    border: 1.5px solid #fecaca; border-radius: 10px;
    font-size: 14px; font-weight: 600; cursor: pointer;
    transition: all 0.2s;
}
.btn-cancel-payment:hover { background: #fef2f2; border-color: #dc2626; }

.cod-badge {
    background: #fff7ed; border: 1px solid #fed7aa;
    border-radius: 10px; padding: 1rem 1.25rem;
    display: flex; align-items: center; gap: 12px; margin-bottom: 1rem;
}
.cod-badge-icon { font-size: 2rem; }
.cod-badge-text h4 { font-size: 14px; font-weight: 700; color: #9a3412; margin: 0 0 2px; }
.cod-badge-text p  { font-size: 12px; color: #c2410c; margin: 0; }

.steps { display: flex; align-items: center; gap: 0; margin-bottom: 1.5rem; }
.step  { display: flex; align-items: center; gap: 6px; font-size: 12px; color: var(--gray-400); }
.step.active { color: var(--pink-600); font-weight: 600; }
.step.done   { color: #16a34a; }
.step-dot { width: 24px; height: 24px; border-radius: 50%; border: 2px solid var(--gray-200); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; }
.step.active .step-dot { border-color: var(--pink-600); background: var(--pink-600); color: white; }
.step.done   .step-dot { border-color: #16a34a; background: #16a34a; color: white; }
.step-line { flex: 1; height: 2px; background: var(--gray-100); margin: 0 6px; }

.success-card {
    background: white; border-radius: var(--radius);
    box-shadow: var(--shadow); padding: 3rem 2rem;
    text-align: center; max-width: 480px; margin: 0 auto;
}
.success-card .success-icon { font-size: 4rem; margin-bottom: 1rem; }
.success-card h2 { font-size: 1.5rem; font-weight: 700; color: var(--brown); margin-bottom: 0.5rem; }
.success-card p  { color: var(--gray-600); font-size: 14px; margin: 4px 0; }
.success-actions { display: flex; gap: 10px; justify-content: center; margin-top: 1.5rem; flex-wrap: wrap; }
</style>

<div class="pay-wrap">
<div class="pay-container">

    {{-- Step indicator --}}
    <div class="steps">
        <div class="step done"><div class="step-dot">✓</div> <span>Keranjang</span></div>
        <div class="step-line"></div>
        <div class="step done"><div class="step-dot">✓</div> <span>Checkout</span></div>
        <div class="step-line"></div>
        <div class="step active"><div class="step-dot">3</div> <span>Pembayaran</span></div>
        <div class="step-line"></div>
        <div class="step"><div class="step-dot">4</div> <span>Selesai</span></div>
    </div>

    <div class="pay-header">
        <h1>Pembayaran</h1>
        <p>Order #{{ $order->order_number }}</p>
    </div>

    @if(session('success'))
    {{-- SUCCESS STATE --}}
    <div class="success-card">
        <div class="success-icon">🎉</div>
        <h2>Pesanan Dikonfirmasi!</h2>
        <p>Terima kasih sudah berbelanja di <strong>The Daily Outfit</strong>.</p>
        <p style="margin-top:8px;">Nomor Pesanan:</p>
        <p style="font-size:16px; font-weight:700; color:var(--brown); letter-spacing:1px;">{{ $order->order_number }}</p>
        <div class="success-actions">
            <a href="{{ route('orders') }}" class="btn btn-primary">Lihat Pesanan</a>
            <a href="{{ route('home') }}" class="btn btn-outline">Lanjut Belanja</a>
        </div>
    </div>

    @else
    {{-- PAYMENT STATE --}}
    <div class="pay-grid">

        {{-- KIRI --}}
        <div>
            {{-- Ringkasan Pesanan --}}
            <div class="pay-card">
                <div class="pay-card-title">🛍️ Ringkasan Pesanan</div>
                @foreach($items as $item)
                <div class="order-item">
                    <div>
                        <div class="order-item-name">{{ $item->product->name }}</div>
                        <div class="order-item-sub">Ukuran {{ $item->size }} × {{ $item->quantity }}</div>
                    </div>
                    <div class="order-item-price">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</div>
                </div>
                @endforeach

                <div style="margin-top:12px;">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($order->total_amount - $order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Ongkir</span>
                        @if($order->shipping_cost > 0)
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        @else
                            <span class="badge-free">GRATIS</span>
                        @endif
                    </div>
                    <div class="summary-total">
                        <span>Total Bayar</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- Instruksi Pembayaran --}}
            @if($order->payment_method !== 'cod')
            <div class="pay-card">
                <div class="pay-card-title">
                    {{ $order->payment_method === 'transfer' ? '🏦' : '📱' }}
                    Instruksi Pembayaran
                </div>
                <p style="font-size:13px; color:var(--gray-400); margin-bottom:12px;">
                    Transfer ke salah satu rekening berikut:
                </p>

                @if($order->payment_method === 'transfer')
                    <div class="bank-item">
                        <div class="bank-logo">BCA</div>
                        <div class="bank-detail">
                            <div class="bank-name-text">Bank BCA</div>
                            <div class="bank-no-text">1234567890</div>
                            <div class="bank-an-text">a/n The Daily Outfit</div>
                        </div>
                        <button class="copy-btn" onclick="copyText('1234567890', this)">Salin</button>
                    </div>
                    <div class="bank-item">
                        <div class="bank-logo">MDR</div>
                        <div class="bank-detail">
                            <div class="bank-name-text">Bank Mandiri</div>
                            <div class="bank-no-text">9876543210</div>
                            <div class="bank-an-text">a/n The Daily Outfit</div>
                        </div>
                        <button class="copy-btn" onclick="copyText('9876543210', this)">Salin</button>
                    </div>
                @else
                    <div class="bank-item">
                        <div class="bank-logo" style="font-size:9px;">GoPay</div>
                        <div class="bank-detail">
                            <div class="bank-name-text">GoPay</div>
                            <div class="bank-no-text">082345678901</div>
                        </div>
                        <button class="copy-btn" onclick="copyText('082345678901', this)">Salin</button>
                    </div>
                    <div class="bank-item">
                        <div class="bank-logo">OVO</div>
                        <div class="bank-detail">
                            <div class="bank-name-text">OVO</div>
                            <div class="bank-no-text">082345678901</div>
                        </div>
                        <button class="copy-btn" onclick="copyText('082345678901', this)">Salin</button>
                    </div>
                @endif

                <div class="total-highlight">
                    Transfer tepat sebesar<br>
                    <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                </div>
            </div>
            @endif
        </div>

        {{-- KANAN --}}
        <div>
            <div class="pay-card">
                <div class="pay-card-title">
                    {{ $order->payment_method === 'cod' ? '🚚' : '📋' }}
                    Konfirmasi Pembayaran
                </div>

                @if($order->payment_method === 'cod')
                <div class="cod-badge">
                    <div class="cod-badge-icon">🚚</div>
                    <div class="cod-badge-text">
                        <h4>Bayar di Tempat (COD)</h4>
                        <p>Siapkan uang tunai saat kurir tiba. Pastikan nominal sesuai.</p>
                    </div>
                </div>
                <div class="summary-row" style="font-size:15px; font-weight:700; color:var(--brown);">
                    <span>Siapkan:</span>
                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('payment.confirm', $order->id) }}" enctype="multipart/form-data">
                    @csrf
                    @if($order->payment_method !== 'cod')
                    <div class="upload-area" onclick="document.getElementById('proofFile').click()">
                        <input type="file" name="proof_image" id="proofFile" accept="image/*"
                               style="display:none" onchange="previewImage(this)">
                        <div id="uploadPlaceholder">
                            <div class="upload-icon">📤</div>
                            <p><strong>Klik untuk upload bukti transfer</strong></p>
                            <small>JPG, PNG, max 5MB</small>
                        </div>
                        <img id="previewImg" style="display:none; max-width:100%; border-radius:8px;">
                    </div>
                    <p style="font-size:12px; color:var(--gray-400); text-align:center; margin-top:8px;">
                        Upload screenshot atau foto bukti transfer kamu
                    </p>
                    @endif

                    <button type="submit" class="btn-confirm">
                        {{ $order->payment_method === 'cod' ? '✅ Konfirmasi Pesanan COD' : '✅ Konfirmasi Pembayaran' }}
                    </button>
                </form>

                {{-- TOMBOL BATALKAN: hanya muncul sebelum upload bukti bayar --}}
                @if($order->payment_status === 'unpaid' && in_array($order->status, ['pending', 'processing']))
                <form method="POST" action="{{ route('orders.cancel', $order->id) }}"
                      onsubmit="return confirm('Yakin ingin membatalkan pesanan ini? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    <button type="submit" class="btn-cancel-payment">
                        ❌ Batalkan Pesanan
                    </button>
                </form>
                @endif

                <p style="font-size:12px; color:var(--gray-400); text-align:center; margin-top:12px;">
                    Pesanan akan diproses setelah pembayaran dikonfirmasi oleh tim kami.
                </p>
            </div>

            {{-- Info tambahan --}}
            <div class="pay-card" style="background:var(--pink-50); box-shadow:none; border:1px solid var(--pink-100);">
                <div style="font-size:13px; color:var(--gray-600); line-height:1.7;">
                    <p style="margin:0 0 6px; font-weight:700; color:var(--brown);">ℹ️ Perlu bantuan?</p>
                    <p style="margin:0;">Hubungi kami via WhatsApp atau DM Instagram jika ada kendala pembayaran.</p>
                </div>
            </div>
        </div>

    </div>
    @endif

</div>
</div>

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewImg').style.display = 'block';
            document.getElementById('uploadPlaceholder').style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function copyText(text, btn) {
    navigator.clipboard.writeText(text).then(() => {
        btn.innerText = '✓ Disalin';
        btn.style.color = '#16a34a';
        setTimeout(() => { btn.innerText = 'Salin'; btn.style.color = ''; }, 2000);
    });
}
</script>
@endpush

@endsection