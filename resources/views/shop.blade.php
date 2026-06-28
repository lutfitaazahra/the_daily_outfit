@extends('layouts.app')

@section('title', 'Shop — The Daily Outfit')

@section('content')

<!-- SHOP HEADER -->
<div class="shop-header">
    <div class="container">
        <h1>Our Collection</h1>
        <p>{{ $products->count() }} produk ditemukan</p>
    </div>
</div>

<!-- CATEGORY PILLS -->
<div style="background: white; border-bottom: 1px solid #e5e5e5; padding: 1rem 2rem; position: sticky; top: 64px; z-index: 50;">
    <div class="container">
        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
            <a href="{{ route('shop') }}"
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:50px; font-size:13px; font-weight:500; transition:all 0.2s;
               {{ !request('category') ? 'background:#c94f7c; color:white;' : 'background:#fff0f3; color:#c94f7c; border:1.5px solid #ffd6df;' }}">
                🛍️ Semua
            </a>
            @php
            $icons = ['tops'=>'👚','bottoms'=>'👖','dresses'=>'👗','outerwear'=>'🧥','accessories'=>'👜'];
            @endphp
            @foreach($categories as $cat)
            <a href="{{ route('shop', ['category' => $cat->slug, 'sort' => request('sort')]) }}"
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:50px; font-size:13px; font-weight:500; transition:all 0.2s;
               {{ request('category') === $cat->slug ? 'background:#c94f7c; color:white;' : 'background:#fff0f3; color:#c94f7c; border:1.5px solid #ffd6df;' }}">
                {{ $icons[$cat->slug] ?? '🏷️' }} {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- TOOLBAR -->
<div style="padding: 1.25rem 2rem; background: #fafafa; border-bottom: 1px solid #e5e5e5;">
    <div class="container" style="display:flex; align-items:center; justify-content:flex-end; flex-wrap:wrap; gap:1rem;">

        <!-- Sort -->
        <div style="display:flex; align-items:center; gap:8px;">
            <span style="font-size:13px; color:#525252;">Urutkan:</span>
            <div style="display:flex; gap:6px;">
                @foreach(['newest' => 'Terbaru', 'price_asc' => '↑ Harga', 'price_desc' => '↓ Harga', 'name' => 'A-Z'] as $val => $label)
                <a href="{{ route('shop', array_merge(request()->all(), ['sort' => $val])) }}"
                   style="padding:6px 14px; border-radius:50px; font-size:12px; font-weight:500; transition:all 0.2s;
                   {{ request('sort', 'newest') === $val ? 'background:#c94f7c; color:white;' : 'background:white; color:#525252; border:1px solid #e5e5e5;' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

    </div>
</div>

<!-- PRODUCTS -->
<div class="section">
    <div class="container">
        @if($products->isEmpty())
        <div style="text-align:center; padding:5rem 2rem;">
            <div style="font-size:3rem; margin-bottom:1rem;">🛍️</div>
            <p style="color:#525252; margin-bottom:1.5rem;">Produk tidak ditemukan.</p>
            <a href="{{ route('shop') }}" class="btn btn-outline">Lihat Semua Produk</a>
        </div>
        @else
        <div class="product-grid">
            @foreach($products as $p)
            <div class="product-card">
                <a href="{{ route('product.detail', $p->id) }}">
                    <div class="product-img">
                        @if($p->image)
                            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}">
                        @else
                            <div class="product-img-placeholder"><span>{{ mb_substr($p->name, 0, 2) }}</span></div>
                        @endif
                    </div>
                </a>
                <div class="product-info">
                    <span class="product-cat">{{ $p->category->name ?? '' }}</span>
                    <h3 class="product-name">
                        <a href="{{ route('product.detail', $p->id) }}">{{ $p->name }}</a>
                    </h3>
                    <div class="product-footer">
                        <span class="product-price">Rp {{ number_format($p->price, 0, ',', '.') }}</span>
                        @auth
                        <a href="{{ route('product.detail', $p->id) }}" class="btn-cart">+</a>
                        @else
                        <a href="{{ route('login') }}" class="btn-cart">+</a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>

@endsection