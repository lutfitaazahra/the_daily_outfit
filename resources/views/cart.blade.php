@extends('layouts.app')

@section('title', 'Keranjang — The Daily Outfit')

@section('content')
<style>
.cart-wrap { background: var(--pink-50); min-height: 100vh; padding: 2rem 0; }
.cart-container { max-width: 1000px; margin: 0 auto; padding: 0 1rem; }
.cart-grid { display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start; }
@media(max-width:720px){ .cart-grid { grid-template-columns: 1fr; } }

.cart-header-bar {
    background: white; border-radius: var(--radius); padding: 12px 1.25rem;
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 8px; box-shadow: var(--shadow);
    font-size: 13px; color: var(--gray-600);
}
.cart-item {
    background: white; border-radius: var(--radius);
    box-shadow: var(--shadow); padding: 1rem 1.25rem;
    display: flex; align-items: center; gap: 1rem;
    margin-bottom: 8px; transition: box-shadow 0.2s;
}
.cart-item:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
.cart-item.selected { border-left: 3px solid var(--pink-600); }
.cart-checkbox {
    width: 20px; height: 20px; border-radius: 50%;
    border: 2px solid var(--gray-300); background: white;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer; flex-shrink: 0; transition: all 0.15s;
}
.cart-checkbox.checked { background: var(--pink-600); border-color: var(--pink-600); }
.cart-checkbox.checked::after { content: '✓'; color: white; font-size: 12px; font-weight: 700; }
.cart-img {
    width: 80px; height: 80px; border-radius: 10px;
    overflow: hidden; background: var(--pink-50);
    flex-shrink: 0; display: flex; align-items: center; justify-content: center;
}
.cart-img img { width: 100%; height: 100%; object-fit: cover; }
.cart-img-placeholder { font-weight: 700; color: var(--pink-400); font-size: 1.5rem; }
.qty-ctrl { display: flex; align-items: center; border: 1.5px solid var(--gray-200); border-radius: 8px; overflow: hidden; }
.qty-btn { width: 32px; height: 36px; border: none; background: var(--gray-50); font-size: 16px; cursor: pointer; color: var(--gray-600); display: flex; align-items: center; justify-content: center; transition: background 0.15s; }
.qty-btn:hover { background: var(--pink-50); color: var(--pink-600); }
.qty-input { width: 40px; height: 36px; border: none; border-left: 1.5px solid var(--gray-200); border-right: 1.5px solid var(--gray-200); text-align: center; font-size: 14px; font-weight: 600; color: var(--gray-800); -moz-appearance: textfield; }
.qty-input::-webkit-outer-spin-button, .qty-input::-webkit-inner-spin-button { -webkit-appearance: none; }
.remove-btn { color: var(--gray-300); font-size: 20px; text-decoration: none; flex-shrink: 0; transition: color 0.15s; line-height: 1; background: none; border: none; cursor: pointer; padding: 4px; }
.remove-btn:hover { color: #ef4444; }
.summary-card { background: white; border-radius: var(--radius); box-shadow: var(--shadow); padding: 1.5rem; position: sticky; top: 90px; }
.summary-title { font-size: 15px; font-weight: 700; color: var(--brown); margin-bottom: 1rem; padding-bottom: 0.75rem; border-bottom: 1px solid var(--gray-100); }
.summary-row { display: flex; justify-content: space-between; font-size: 14px; color: var(--gray-600); margin-bottom: 8px; }
.summary-total { display: flex; justify-content: space-between; font-size: 16px; font-weight: 700; color: var(--brown); padding-top: 12px; border-top: 2px solid var(--gray-100); margin: 8px 0 1.25rem; }
.badge-free { background: #dcfce7; color: #16a34a; font-size: 11px; font-weight: 700; padding: 2px 10px; border-radius: 50px; }
.progress-bar-wrap { background: var(--gray-100); border-radius: 50px; height: 4px; margin: 4px 0 8px; overflow: hidden; }
.progress-bar-fill { height: 100%; background: var(--pink-400); border-radius: 50px; transition: width 0.3s; }
.btn-checkout { width: 100%; padding: 14px; background: var(--brown); color: white; border: none; border-radius: 10px; font-size: 15px; font-weight: 700; cursor: pointer; margin-bottom: 8px; transition: opacity 0.2s; text-align: center; display: block; text-decoration: none; }
.btn-checkout:hover { opacity: 0.88; }
.btn-lanjut { width: 100%; padding: 12px; background: white; color: var(--gray-600); border: 1.5px solid var(--gray-200); border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; text-align: center; display: block; text-decoration: none; transition: all 0.2s; }
.btn-lanjut:hover { border-color: var(--pink-300); color: var(--pink-600); }
.selected-info { font-size: 13px; color: var(--gray-500); }
.selected-info span { font-weight: 700; color: var(--pink-600); }
</style>

<div class="cart-wrap">
<div class="cart-container">

    <h1 style="font-size:1.75rem; font-weight:700; color:var(--brown); margin-bottom:1.25rem;">
        Keranjang Belanja
    </h1>

    @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:1rem;">{{ session('success') }}</div>
    @endif

    @if($items->isEmpty())
    <div style="background:white; border-radius:var(--radius); box-shadow:var(--shadow); text-align:center; padding:4rem 2rem;">
        <div style="font-size:3.5rem; margin-bottom:1rem;">🛒</div>
        <p style="color:var(--gray-400); margin-bottom:1.5rem; font-size:15px;">Keranjang kamu masih kosong.</p>
        <a href="{{ route('shop') }}" class="btn btn-dark">Mulai Belanja</a>
    </div>

    @else
    @php
    $colorMap = ['Hitam'=>'#1a1a1a','Putih'=>'#f5f5f5','Abu'=>'#9ca3af','Navy'=>'#1e3a5f','Coklat'=>'#92400e','Krem'=>'#f5e6c8'];
    @endphp

    <div class="cart-grid">

        <div>
            <div class="cart-header-bar">
                <div class="cart-checkbox checked" id="checkAll" onclick="toggleAll(this)"></div>
                <span>Pilih Semua (<span id="totalItems">{{ $items->count() }}</span> produk)</span>
                <span style="margin-left:auto;" class="selected-info">
                    <span id="selectedCount">{{ $items->count() }}</span> dipilih
                </span>
            </div>

            <form method="POST" action="{{ route('cart.update') }}" id="cartForm">
                @csrf
                @foreach($items as $item)
                <div class="cart-item selected" id="item-{{ $item->id }}" data-price="{{ $item->product->price }}" data-qty="{{ $item->quantity }}">

                    <div class="cart-checkbox checked" id="check-{{ $item->id }}" onclick="toggleItem({{ $item->id }}, this)"></div>

                    <div class="cart-img">
                        @if($item->product->image)
                            <img src="{{ str_starts_with($item->product->image, 'http') ? $item->product->image : asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                        @else
                            <span class="cart-img-placeholder">{{ mb_substr($item->product->name, 0, 2) }}</span>
                        @endif
                    </div>

                    <div style="flex:1; min-width:0;">
                        <a href="{{ route('product.detail', $item->product_id) }}"
                           style="font-size:14px; font-weight:600; color:var(--gray-800); text-decoration:none; display:block; margin-bottom:3px;">
                            {{ $item->product->name }}
                        </a>

                        {{-- Ukuran --}}
                        <p style="font-size:12px; color:var(--gray-400); margin:0 0 2px;">
                            Ukuran: <strong>{{ $item->size }}</strong>
                        </p>

                        {{-- Warna (kalau ada) --}}
                        @if($item->color)
                        <p style="font-size:12px; color:var(--gray-400); margin:0 0 4px; display:flex; align-items:center; gap:4px;">
                            Warna:
                            <span style="display:inline-block; width:12px; height:12px; border-radius:50%;
                                         background:{{ $colorMap[$item->color] ?? '#e5e7eb' }};
                                         border:1px solid #ccc;"></span>
                            <strong>{{ $item->color }}</strong>
                        </p>
                        @endif

                        <p style="font-size:14px; font-weight:700; color:var(--brown); margin:0;">
                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                        </p>
                    </div>

                    <div class="qty-ctrl">
                        <button type="button" class="qty-btn" onclick="changeQty({{ $item->id }}, -1)">−</button>
                        <input type="number" class="qty-input" id="qty-{{ $item->id }}"
                               name="qty[{{ $item->id }}]" value="{{ $item->quantity }}" min="1" max="10"
                               onchange="updateSubtotal()">
                        <button type="button" class="qty-btn" onclick="changeQty({{ $item->id }}, 1)">+</button>
                    </div>

                    <div style="min-width:100px; text-align:right; font-weight:700; font-size:14px; color:var(--gray-800);">
                        Rp <span id="sub-{{ $item->id }}">{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}</span>
                    </div>

                    <a href="{{ route('cart.remove', $item->id) }}" class="remove-btn" title="Hapus">×</a>
                </div>
                @endforeach

                <button type="submit" style="margin-top:4px; background:none; border:none; color:var(--gray-400); font-size:13px; cursor:pointer; padding:8px 0;">
                    🔄 Update Keranjang
                </button>
            </form>
        </div>

        <div>
            <div class="summary-card">
                <div class="summary-title">🛍️ Ringkasan Pesanan</div>

                @php $progress = min(($subtotal / 300000) * 100, 100); @endphp
                @if($shipping > 0)
                <p style="font-size:12px; color:var(--pink-600); margin-bottom:4px;">
                    Belanja <strong>Rp {{ number_format(300000 - $subtotal, 0, ',', '.') }}</strong> lagi untuk gratis ongkir!
                </p>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill" style="width:{{ $progress }}%;"></div>
                </div>
                @endif

                <div class="summary-row">
                    <span>Subtotal (<span id="summaryCount">{{ $items->count() }}</span> produk)</span>
                    <span id="summarySubtotal">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Ongkir</span>
                    @if($shipping == 0)
                    <span class="badge-free">GRATIS</span>
                    @else
                    <span>Rp {{ number_format($shipping, 0, ',', '.') }}</span>
                    @endif
                </div>
                <div class="summary-total">
                    <span>Total</span>
                    <span id="summaryTotal">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </div>

                <a href="{{ route('checkout') }}" class="btn-checkout" id="btnCheckout" onclick="return goCheckout(event)">Checkout →</a>
                <a href="{{ route('shop') }}" class="btn-lanjut">Lanjut Belanja</a>
            </div>
        </div>

    </div>
    @endif

</div>
</div>

@push('scripts')
<script>
const allItems = @json($items->map(fn($i) => ['id' => $i->id, 'price' => $i->product->price, 'qty' => $i->quantity]));
let selected = new Set(allItems.map(i => i.id));

function formatRp(n) {
    return 'Rp ' + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}
function getQty(id) {
    const el = document.getElementById('qty-' + id);
    return el ? parseInt(el.value) || 1 : 1;
}
function updateSubtotal() {
    let subtotal = 0, count = 0;
    allItems.forEach(item => {
        const qty = getQty(item.id);
        const sub = item.price * qty;
        const subEl = document.getElementById('sub-' + item.id);
        if (subEl) subEl.innerText = Math.round(sub).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        if (selected.has(item.id)) { subtotal += sub; count++; }
    });
    document.getElementById('summarySubtotal').innerText = formatRp(subtotal);
    document.getElementById('summaryTotal').innerText = formatRp(subtotal);
    document.getElementById('summaryCount').innerText = count;
    document.getElementById('selectedCount').innerText = count;
    const btn = document.getElementById('btnCheckout');
    btn.style.opacity = count > 0 ? '1' : '0.4';
    btn.style.pointerEvents = count > 0 ? 'auto' : 'none';
}
function changeQty(id, delta) {
    const input = document.getElementById('qty-' + id);
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > 10) val = 10;
    input.value = val;
    updateSubtotal();
}
function toggleItem(id, el) {
    if (selected.has(id)) {
        selected.delete(id);
        el.classList.remove('checked');
        document.getElementById('item-' + id).classList.remove('selected');
    } else {
        selected.add(id);
        el.classList.add('checked');
        document.getElementById('item-' + id).classList.add('selected');
    }
    updateCheckAll();
    updateSubtotal();
}
function toggleAll(el) {
    const allChecked = selected.size === allItems.length;
    allItems.forEach(item => {
        const cb = document.getElementById('check-' + item.id);
        const card = document.getElementById('item-' + item.id);
        if (allChecked) {
            selected.delete(item.id);
            cb.classList.remove('checked');
            card.classList.remove('selected');
        } else {
            selected.add(item.id);
            cb.classList.add('checked');
            card.classList.add('selected');
        }
    });
    el.classList.toggle('checked', !allChecked);
    updateSubtotal();
}
function updateCheckAll() {
    const allCheck = document.getElementById('checkAll');
    allCheck.classList.toggle('checked', selected.size === allItems.length);
}
function goCheckout(e) {
    e.preventDefault();
    if (selected.size === 0) return false;
    const ids = Array.from(selected).join(',');
    window.location.href = "{{ route('checkout') }}?items=" + ids;
}
</script>
@endpush

@endsection