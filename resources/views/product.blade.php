@extends('layouts.app')

@section('title', $product->name . ' — The Daily Outfit')

@section('content')
<div class="container">
    <nav class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> /
        <a href="{{ route('shop') }}">Shop</a> /
        <a href="{{ route('shop', ['category' => $product->category->slug ?? '']) }}">{{ $product->category->name ?? '' }}</a> /
        <span>{{ $product->name }}</span>
    </nav>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">{{ $errors->first() }}</div>
    @endif

    <div class="product-detail">
        <div class="product-detail-img">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            @else
                <div class="product-img-placeholder large"><span>{{ $product->name }}</span></div>
            @endif
        </div>

        <div class="product-detail-info">
            <span class="product-cat">{{ $product->category->name ?? '' }}</span>
            <h1>{{ $product->name }}</h1>
            <div class="product-detail-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            <p class="product-detail-desc">{{ $product->description }}</p>

            @auth
            <form method="POST" action="{{ route('cart.add') }}" class="add-to-cart-form">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <div class="form-group">
                    <label><strong>Ukuran</strong></label>
                    <div class="size-picker">
                        @foreach($product->sizes as $s)
                        <label class="size-btn {{ $s->stock < 1 ? 'disabled' : '' }}">
                            <input type="radio" name="size" value="{{ $s->size }}" {{ $s->stock < 1 ? 'disabled' : '' }} required>
                            {{ $s->size }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label><strong>Jumlah</strong></label>
                    <div class="qty-input">
                        <button type="button" onclick="changeQty(-1)">−</button>
                        <input type="number" name="quantity" id="qty" value="1" min="1" max="10">
                        <button type="button" onclick="changeQty(1)">+</button>
                    </div>
                </div>
                <div style="display:flex; gap:.75rem;">
                    <button type="submit" formaction="{{ route('cart.add') }}" class="btn btn-outline btn-full">Tambah ke Keranjang</button>
                    <button type="submit" formaction="{{ route('buy.now') }}" class="btn btn-primary btn-full">Beli Sekarang</button>
                </div>
            </form>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary btn-full">Login untuk Beli</a>
            @endauth

            <div class="product-shipping-info" style="margin-top:1rem">
                <div class="shipping-badge">🚚 Free Ongkir pembelian ≥ Rp 300.000</div>
                <div class="shipping-badge">↩️ Pengembalian mudah 7 hari</div>
            </div>
        </div>
    </div>

    @if($related->isNotEmpty())
    <section class="section">
        <div class="section-header"><h2>Produk Serupa</h2></div>
        <div class="product-grid">
            @foreach($related as $p)
            <div class="product-card">
                <a href="{{ route('product.detail', $p->id) }}" class="product-img-link">
                    <div class="product-img">
                        <div class="product-img-placeholder"><span>{{ mb_substr($p->name, 0, 2) }}</span></div>
                    </div>
                </a>
                <div class="product-info">
                    <h3 class="product-name"><a href="{{ route('product.detail', $p->id) }}">{{ $p->name }}</a></h3>
                    <div class="product-footer">
                        <span class="product-price">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

@section('scripts')
<script>
function changeQty(delta) {
    const input = document.getElementById('qty');
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > 10) val = 10;
    input.value = val;
}
</script>
@endsection
@endsection