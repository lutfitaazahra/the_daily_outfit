@extends('layouts.app')

@section('title', 'New Arrivals — The Daily Outfit')

@section('content')

<!-- HERO NEW ARRIVALS -->
<div style="background:linear-gradient(135deg, var(--pink-50) 0%, white 100%); padding:3rem 0 2rem; border-bottom:1px solid var(--gray-100);">
    <div class="container" style="text-align:center;">
        <span style="font-size:11px; font-weight:700; letter-spacing:3px; text-transform:uppercase; color:var(--pink-400);">Koleksi Terbaru</span>
        <h1 style="font-family:var(--font); font-size:2.5rem; font-weight:700; color:var(--brown); margin:0.5rem 0;">New Arrivals ✨</h1>
        <p style="color:var(--gray-400); font-size:15px;">Produk terbaru yang baru saja hadir untuk kamu</p>
    </div>
</div>

<!-- CATEGORY PILLS -->
<div style="background:white; border-bottom:1px solid #e5e5e5; padding:1rem 2rem; position:sticky; top:64px; z-index:50;">
    <div class="container">
        <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
            <a href="{{ route('new-arrivals') }}"
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:50px; font-size:13px; font-weight:500;
               {{ !request('category') ? 'background:#c94f7c; color:white;' : 'background:#fff0f3; color:#c94f7c; border:1.5px solid #ffd6df;' }}">
                🛍️ Semua
            </a>
            @php
            $icons = ['tops'=>'👚','bottoms'=>'👖','dresses'=>'👗','outerwear'=>'🧥','accessories'=>'👜'];
            @endphp
            @foreach($categories as $cat)
            <a href="{{ route('new-arrivals') }}?category={{ $cat->slug }}"
               style="display:inline-flex; align-items:center; gap:6px; padding:8px 18px; border-radius:50px; font-size:13px; font-weight:500;
               {{ request('category') === $cat->slug ? 'background:#c94f7c; color:white;' : 'background:#fff0f3; color:#c94f7c; border:1.5px solid #ffd6df;' }}">
                {{ $icons[$cat->slug] ?? '🏷️' }} {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- PRODUCTS -->
<div class="section" style="background:var(--pink-50);">
    <div class="container">
        <p style="font-size:13px; color:var(--gray-400); margin-bottom:1.5rem;">{{ $products->count() }} produk terbaru ditemukan</p>

        @if($products->isEmpty())
        <div style="text-align:center; padding:4rem 0;">
            <div style="font-size:3rem; margin-bottom:1rem;">📦</div>
            <p style="color:var(--gray-400); margin-bottom:1.5rem;">Belum ada produk baru.</p>
            <a href="{{ route('shop') }}" class="btn btn-dark">Lihat Semua Produk</a>
        </div>
        @else
        <div class="product-grid">
            @foreach($products as $p)
            <div class="product-card">
                <a href="{{ route('product.detail', $p->id) }}">
                    <div class="product-img">
                        <span class="product-badge">BARU</span>
                        @if($p->image)
                            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}">
                        @else
                            <div class="product-img-placeholder"><span>{{ mb_substr($p->name, 0, 2) }}</span></div>
                        @endif
                        <div class="product-add-overlay">Lihat Produk</div>
                    </div>
                </a>
                <div class="product-info">
                    <span class="product-cat">{{ $p->category->name ?? '' }}</span>
                    <div class="product-stars">★★★★★</div>
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