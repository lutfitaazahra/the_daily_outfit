@extends('layouts.app')

@section('title', 'Checkout — The Daily Outfit')

@section('content')
<div style="background:var(--pink-50); min-height:100vh; padding:2rem 0;">
<div class="container" style="max-width:960px;">

    <h1 style="font-family:var(--font); font-size:1.75rem; font-weight:700; color:var(--brown); margin-bottom:1.5rem;">Checkout</h1>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form">
        @csrf
        <input type="hidden" name="shipping_cost" id="shipping_cost_input" value="0">
        <input type="hidden" name="courier" id="courier_input" value="">
        @if(request('items'))
        <input type="hidden" name="items" value="{{ request('items') }}">
        @endif

        <!-- INFORMASI PENGIRIMAN -->
        <div style="background:white; border-radius:var(--radius); padding:1.5rem; margin-bottom:1rem; box-shadow:var(--shadow);">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:1.25rem;">
                <span style="font-size:18px;">📍</span>
                <h3 style="font-size:15px; font-weight:700; color:var(--gray-800);">Informasi Pengiriman</h3>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="form-group">
                    <label>Nama Penerima</label>
                    <input type="text" value="{{ $user->name }}" readonly style="background:var(--gray-50);">
                </div>
                <div class="form-group">
                    <label>No. HP</label>
                    <input type="text" value="{{ $user->phone ?? '-' }}" readonly style="background:var(--gray-50);">
                </div>
                <div class="form-group" style="grid-column:span 2;">
                    <label>Alamat Lengkap *</label>
                    <textarea name="address" rows="3" placeholder="Jl. Nama Jalan No. X, Kota" required
                              style="width:100%; padding:10px 14px; border:1px solid var(--gray-200); border-radius:8px; font-size:14px; resize:vertical;">{{ old('address', $user->address) }}</textarea>
                </div>
            </div>
        </div>

        <!-- OPSI PENGIRIMAN -->
        <div style="background:white; border-radius:var(--radius); padding:1.5rem; margin-bottom:1rem; box-shadow:var(--shadow);">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:1.25rem;">
                <span style="font-size:18px;">🚚</span>
                <h3 style="font-size:15px; font-weight:700; color:var(--gray-800);">Opsi Pengiriman</h3>
            </div>

            @php
            $couriers = [
                'hemat_kargo'  => ['📦', 'Hemat Kargo', 'Ekonomis', 'Estimasi 5-7 hari kerja', 0, 'Gratis'],
                'gosend'       => ['🟢', 'GoSend', 'Same Day', 'Estimasi hari ini', 18000, 'Tercepat'],
                'anteraja_reg' => ['🔵', 'Anteraja', 'Reguler', 'Estimasi 3-4 hari kerja', 8000, 'Termurah'],
                'jnt_ez'       => ['🔴', 'J&T Express', 'EZ', 'Estimasi 2-3 hari kerja', 10000, 'Hemat Kargo'],
                'sicepat_reg'  => ['🟡', 'SiCepat', 'REG', 'Estimasi 2-3 hari kerja', 12000, 'Hemat Kargo'],
                'jne_reg'      => ['🟠', 'JNE', 'REG', 'Estimasi 2-3 hari kerja', 15000, 'Hemat Kargo'],
            ];
            $featured = ['Gratis', 'Tercepat'];
            @endphp

            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($couriers as $key => [$logo, $name, $service, $desc, $price, $tag])
                @php $isHidden = !in_array($tag, $featured); @endphp
                <label class="courier-item{{ $isHidden ? ' courier-hidden' : '' }}"
                       style="cursor:pointer;{{ $isHidden ? ' display:none;' : '' }}"
                       onclick="selectCourier('{{ $key }}', {{ $price }}, '{{ $name }} {{ $service }}', '{{ $desc }}')">
                    <input type="radio" name="courier_option" value="{{ $key }}" style="display:none;" id="courier_{{ $key }}">
                    <div id="couriercard_{{ $key }}"
                         style="display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem;
                                border-radius:10px; border:2px solid var(--gray-200);
                                background:white; transition:all 0.2s;">
                        <div id="courierradio_{{ $key }}"
                             style="width:20px; height:20px; border-radius:50%; border:2px solid var(--gray-300);
                                    display:flex; align-items:center; justify-content:center; flex-shrink:0;"></div>
                        <div style="width:44px; height:44px; border-radius:10px; background:var(--gray-100);
                                    display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0;">
                            {{ $logo }}
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                                <span style="font-size:14px; font-weight:700; color:var(--gray-800);">{{ $name }}</span>
                                <span style="font-size:11px; color:var(--gray-400); background:var(--gray-100); padding:2px 8px; border-radius:4px;">{{ $service }}</span>
                                @if($tag)
                                @php
                                $tagColor = match($tag) {
                                    'Gratis'      => ['#eff6ff', '#1d4ed8'],
                                    'Termurah'    => ['#f0fdf4', '#16a34a'],
                                    'Tercepat'    => ['#fff7ed', '#ea580c'],
                                    'Hemat Kargo' => ['#eff6ff', '#1d4ed8'],
                                    default       => ['#f1f5f9', '#64748b'],
                                };
                                @endphp
                                <span style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:50px;
                                             background:{{ $tagColor[0] }}; color:{{ $tagColor[1] }};">{{ $tag }}</span>
                                @endif
                            </div>
                            <p style="font-size:12px; color:var(--gray-400); margin-top:3px;">{{ $desc }}</p>
                        </div>
                        <div style="text-align:right; flex-shrink:0;">
                            <span style="font-size:14px; font-weight:700; color:{{ $price == 0 ? '#16a34a' : 'var(--gray-800)' }};">
                                {{ $price == 0 ? 'GRATIS' : 'Rp ' . number_format($price, 0, ',', '.') }}
                            </span>
                        </div>
                        <div id="couriercheck_{{ $key }}" style="color:transparent; font-size:18px; flex-shrink:0;">✓</div>
                    </div>
                </label>
                @endforeach
            </div>

            <!-- Lihat Semua -->
            <button type="button" id="show-all-btn" onclick="showAllCouriers()"
                    style="margin-top:12px; background:none; border:none; color:var(--pink-600);
                           font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:4px; padding:0;">
                Lihat semua opsi pengiriman ▾
            </button>

            <div id="shipping-info" style="display:none; margin-top:1rem; padding:10px 14px;
                 background:var(--pink-50); border-radius:8px; font-size:13px; color:var(--pink-600); font-weight:500;">
                🚚 <span id="shipping-text"></span>
            </div>
        </div>

        <!-- METODE PEMBAYARAN -->
        <div style="background:white; border-radius:var(--radius); padding:1.5rem; margin-bottom:1rem; box-shadow:var(--shadow);">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:1.25rem;">
                <span style="font-size:18px;">💳</span>
                <h3 style="font-size:15px; font-weight:700; color:var(--gray-800);">Metode Pembayaran</h3>
            </div>
            @php
            $methods = [
                'transfer' => ['🏦', 'Transfer Bank', 'BCA / Mandiri / BNI / BRI', 'Populer'],
                'ewallet'  => ['📱', 'E-Wallet', 'GoPay / OVO / DANA', 'Instan'],
                'cod'      => ['🚚', 'Bayar di Tempat (COD)', 'Bayar tunai saat barang tiba', null],
            ];
            @endphp
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($methods as $val => [$icon, $label, $desc, $tag])
                <label style="cursor:pointer;" onclick="selectPayment('{{ $val }}')">
                    <input type="radio" name="payment_method" value="{{ $val }}"
                           {{ old('payment_method', 'transfer') === $val ? 'checked' : '' }}
                           style="display:none;" id="pay_{{ $val }}">
                    <div id="paycard_{{ $val }}"
                         style="display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem;
                                border-radius:10px; border:2px solid {{ old('payment_method','transfer') === $val ? 'var(--pink-600)' : 'var(--gray-200)' }};
                                background:{{ old('payment_method','transfer') === $val ? 'var(--pink-50)' : 'white' }};
                                transition:all 0.2s;">
                        <div id="payradio_{{ $val }}"
                             style="width:20px; height:20px; border-radius:50%;
                                    border:2px solid {{ old('payment_method','transfer') === $val ? 'var(--pink-600)' : 'var(--gray-300)' }};
                                    display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                            @if(old('payment_method','transfer') === $val)
                            <div style="width:10px;height:10px;border-radius:50%;background:var(--pink-600);"></div>
                            @endif
                        </div>
                        <div style="width:44px; height:44px; border-radius:10px; background:var(--pink-50);
                                    display:flex; align-items:center; justify-content:center; font-size:22px; flex-shrink:0;">
                            {{ $icon }}
                        </div>
                        <div style="flex:1;">
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="font-size:14px; font-weight:600; color:var(--gray-800);">{{ $label }}</span>
                                @if($tag)
                                <span style="font-size:10px; font-weight:600; padding:2px 8px; border-radius:50px;
                                             background:var(--pink-100); color:var(--pink-600);">{{ $tag }}</span>
                                @endif
                            </div>
                            <p style="font-size:12px; color:var(--gray-400); margin-top:2px;">{{ $desc }}</p>
                        </div>
                        <div id="paycheck_{{ $val }}" style="color:{{ old('payment_method','transfer') === $val ? 'var(--pink-600)' : 'transparent' }}; font-size:18px;">✓</div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <!-- RINGKASAN PESANAN -->
        <div style="background:white; border-radius:var(--radius); padding:1.5rem; margin-bottom:1rem; box-shadow:var(--shadow);">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:1.25rem;">
                <span style="font-size:18px;">🛍️</span>
                <h3 style="font-size:15px; font-weight:700; color:var(--gray-800);">Ringkasan Pesanan</h3>
            </div>
            @foreach($items as $item)
            <div style="display:flex; align-items:center; gap:1rem; padding:10px 0; border-bottom:1px solid var(--gray-100);">
                <div style="width:48px; height:48px; border-radius:8px; overflow:hidden; background:var(--pink-50); flex-shrink:0;">
                    @if($item->product->image)
                    <img src="{{ asset('storage/' . $item->product->image) }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                    <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;font-size:18px;">👗</div>
                    @endif
                </div>
                <div style="flex:1;">
                    <p style="font-size:14px; font-weight:500; color:var(--gray-800);">{{ $item->product->name }}</p>
                    <p style="font-size:12px; color:var(--gray-400);">Ukuran: {{ $item->size }} × {{ $item->quantity }}</p>
                </div>
                <span style="font-size:14px; font-weight:600; color:var(--gray-800);">
                    Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                </span>
            </div>
            @endforeach
            <div style="margin-top:1rem; display:flex; flex-direction:column; gap:8px;">
                <div style="display:flex; justify-content:space-between; font-size:14px; color:var(--gray-600);">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:14px; color:var(--gray-600);">
                    <span>Ongkir</span>
                    <span id="ongkir-display" style="color:var(--gray-400);">— Pilih pengiriman dulu</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:16px; font-weight:700;
                            color:var(--brown); padding-top:10px; border-top:1px solid var(--gray-200); margin-top:4px;">
                    <span>Total Bayar</span>
                    <span id="total-display">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- TOMBOL -->
        <div style="display:flex; gap:12px; margin-bottom:2rem;">
            <a href="{{ route('cart') }}" class="btn btn-outline" style="flex:1; justify-content:center;">← Kembali</a>
            <button type="submit" class="btn btn-dark" style="flex:2; justify-content:center; padding:14px;">
                Buat Pesanan →
            </button>
        </div>

    </form>
</div>
</div>

@push('scripts')
<script>
const subtotal = {{ $subtotal }};
const courierKeys = ['hemat_kargo','gosend','anteraja_reg','jnt_ez','sicepat_reg','jne_reg'];

function formatRp(n) {
    return n === 0 ? 'GRATIS' : 'Rp ' + n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

function selectCourier(val, price, name, desc) {
    courierKeys.forEach(k => {
        const card  = document.getElementById('couriercard_' + k);
        const radio = document.getElementById('courierradio_' + k);
        const check = document.getElementById('couriercheck_' + k);
        if (!card) return;
        if (k === val) {
            card.style.border     = '2px solid var(--pink-600)';
            card.style.background = 'var(--pink-50)';
            radio.style.border    = '2px solid var(--pink-600)';
            radio.innerHTML       = '<div style="width:10px;height:10px;border-radius:50%;background:var(--pink-600);"></div>';
            check.style.color     = 'var(--pink-600)';
            document.getElementById('courier_' + k).checked = true;
        } else {
            card.style.border     = '2px solid var(--gray-200)';
            card.style.background = 'white';
            radio.style.border    = '2px solid var(--gray-300)';
            radio.innerHTML       = '';
            check.style.color     = 'transparent';
        }
    });

    document.getElementById('shipping_cost_input').value = price;
    document.getElementById('courier_input').value = val;

    const ongkirEl = document.getElementById('ongkir-display');
    ongkirEl.style.color = price === 0 ? '#16a34a' : 'var(--gray-800)';
    ongkirEl.innerText = formatRp(price);
    document.getElementById('total-display').innerText = 'Rp ' + (subtotal + price).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');

    const info = document.getElementById('shipping-info');
    info.style.display = 'block';
    document.getElementById('shipping-text').innerText = name + ' · ' + desc;
}

function selectPayment(val) {
    ['transfer','ewallet','cod'].forEach(m => {
        const card  = document.getElementById('paycard_' + m);
        const radio = document.getElementById('payradio_' + m);
        const check = document.getElementById('paycheck_' + m);
        if (m === val) {
            card.style.border     = '2px solid var(--pink-600)';
            card.style.background = 'var(--pink-50)';
            radio.style.border    = '2px solid var(--pink-600)';
            radio.innerHTML       = '<div style="width:10px;height:10px;border-radius:50%;background:var(--pink-600);"></div>';
            check.style.color     = 'var(--pink-600)';
            document.getElementById('pay_' + m).checked = true;
        } else {
            card.style.border     = '2px solid var(--gray-200)';
            card.style.background = 'white';
            radio.style.border    = '2px solid var(--gray-300)';
            radio.innerHTML       = '';
            check.style.color     = 'transparent';
        }
    });
}

function showAllCouriers() {
    document.querySelectorAll('.courier-hidden').forEach(el => {
        el.style.display = 'block';
    });
    document.getElementById('show-all-btn').style.display = 'none';
}
</script>
@endpush

@endsection