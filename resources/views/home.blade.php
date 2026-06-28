@extends('layouts.app')

@section('title', 'The Daily Outfit — Gaya Harianmu')

@section('content')

<!-- HERO FULL dengan teks overlay di atas gambar -->
<section style="position:relative; height:90vh; min-height:560px; overflow:hidden;">

    <!-- GAMBAR BACKGROUND -->
    <img src="https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=1600&q=90"
        alt="Fashion Hero"
        style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; object-position:center top;">

    <!-- OVERLAY GRADIENT -->
    <div style="position:absolute; inset:0;
                background:linear-gradient(to right, rgba(90,62,53,0.75) 0%, rgba(90,62,53,0.3) 60%, transparent 100%);"></div>

    <!-- KONTEN -->
    <div style="position:relative; z-index:2; height:100%; display:flex; align-items:center; padding:0 5%;">
        <div style="max-width:560px;">

            <!-- LOGO TOKO -->
            <div style="display:inline-flex; align-items:center; gap:12px; margin-bottom:2rem;
                        background:rgba(255,255,255,0.12); backdrop-filter:blur(8px);
                        border:1px solid rgba(255,255,255,0.2); border-radius:50px;
                        padding:8px 20px 8px 8px;">
                <div style="width:36px; height:36px; border-radius:50%; background:var(--pink-600);
                            display:flex; align-items:center; justify-content:center;
                            font-family:var(--font); font-weight:700; font-size:16px; color:white;">
                    T
                </div>
                <span style="font-family:var(--font); font-size:15px; font-weight:600; color:white; letter-spacing:0.5px;">
                    The Daily Outfit
                </span>
            </div>

            <span style="display:inline-block; font-size:11px; font-weight:700; letter-spacing:3px;
                        text-transform:uppercase; color:var(--pink-200); margin-bottom:1rem;">
                Simple · Comfortable · Stylish
            </span>

            <h1 style="font-family:var(--font); font-size:3.75rem; font-weight:700; line-height:1.1;
                    color:white; margin-bottom:1.25rem; letter-spacing:-1px;">
                Gaya Terbaik<br>untuk <em style="font-style:normal; color:var(--pink-200);">Setiap Hari</em>
            </h1>

            <p style="font-size:16px; color:rgba(255,255,255,0.8); line-height:1.7;
                    margin-bottom:2rem; max-width:420px;">
                Temukan koleksi pakaian yang nyaman, modern, dan siap menemani setiap aktivitasmu.
            </p>

            <div style="display:flex; gap:12px; flex-wrap:wrap;">
                <a href="{{ route('shop') }}"
                style="display:inline-flex; align-items:center; gap:8px; padding:14px 28px;
                        background:var(--pink-600); color:white; border-radius:50px;
                        font-size:14px; font-weight:600; transition:all 0.2s; text-decoration:none;">
                    Belanja Sekarang →
                </a>
                <a href="{{ route('new-arrivals') }}"
                style="display:inline-flex; align-items:center; gap:8px; padding:14px 28px;
                        background:rgba(255,255,255,0.15); backdrop-filter:blur(8px);
                        color:white; border-radius:50px; border:1.5px solid rgba(255,255,255,0.3);
                        font-size:14px; font-weight:600; transition:all 0.2s; text-decoration:none;">
                    Koleksi Baru
                </a>
            </div>

            <!-- STATS -->
            <div style="display:flex; gap:2rem; margin-top:2.5rem; flex-wrap:wrap;">
                <div>
                    <div style="font-family:var(--font); font-size:1.5rem; font-weight:700; color:white;">500+</div>
                    <div style="font-size:12px; color:rgba(255,255,255,0.6);">Produk Pilihan</div>
                </div>
                <div style="width:1px; background:rgba(255,255,255,0.2);"></div>
                <div>
                    <div style="font-family:var(--font); font-size:1.5rem; font-weight:700; color:white;">1K+</div>
                    <div style="font-size:12px; color:rgba(255,255,255,0.6);">Pelanggan Puas</div>
                </div>
                <div style="width:1px; background:rgba(255,255,255,0.2);"></div>
                <div>
                    <div style="font-family:var(--font); font-size:1.5rem; font-weight:700; color:white;">Free</div>
                    <div style="font-size:12px; color:rgba(255,255,255,0.6);">Ongkir > 100rb</div>
                </div>
            </div>
        </div>
    </div>

    <!-- SCROLL INDICATOR -->
    <div style="position:absolute; bottom:2rem; left:50%; transform:translateX(-50%); z-index:2;
                display:flex; flex-direction:column; align-items:center; gap:6px;">
        <span style="font-size:11px; color:rgba(255,255,255,0.5); letter-spacing:2px; text-transform:uppercase;">Scroll</span>
        <div style="width:1px; height:40px; background:linear-gradient(to bottom, rgba(255,255,255,0.5), transparent);"></div>
    </div>
</section>

<!-- KATEGORI PILIHAN -->
<section class="section" style="background:var(--pink-50);">
    <div class="container">
        <h2 class="section-title">Kategori Pilihan</h2>
        <div class="category-img-grid">
            @php
            $catImages = [
                'dresses'     => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=400&q=80',
                'tops'        => 'https://images.unsplash.com/photo-1583744946564-b52ac1c389c8?w=400&q=80',
                'outerwear'   => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400&q=80',
                'bottoms'     => 'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=400&q=80',
                'accessories' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&q=80',
            ];
            @endphp
            @forelse($categories->take(3) as $cat)
            <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="category-img-card">
                <img src="{{ $catImages[$cat->slug] ?? 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&q=80' }}" alt="{{ $cat->name }}">
                <div class="category-img-label">{{ $cat->name }}</div>
            </a>
            @empty
            <a href="{{ route('shop') }}" class="category-img-card">
                <img src="https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=400&q=80" alt="Dress">
                <div class="category-img-label">Dress</div>
            </a>
            <a href="{{ route('shop') }}" class="category-img-card">
                <img src="https://images.unsplash.com/photo-1583744946564-b52ac1c389c8?w=400&q=80" alt="Blouse">
                <div class="category-img-label">Blouse</div>
            </a>
            <a href="{{ route('shop') }}" class="category-img-card">
                <img src="https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400&q=80" alt="Outerwear">
                <div class="category-img-label">Outerwear</div>
            </a>
            @endforelse
        </div>
    </div>
</section>

<!-- KOLEKSI TERBARU -->
<section class="section">
    <div class="container">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.75rem;">
            <h2 class="section-title" style="margin-bottom:0;">Koleksi Terbaru</h2>
            <a href="{{ route('shop') }}" style="font-size:13px; color:var(--pink-600); font-weight:500;">Lihat Semua →</a>
        </div>
        <div class="product-grid">
            @forelse($featured as $p)
            <div class="product-card">
                <a href="{{ route('product.detail', $p->id) }}">
                    <div class="product-img">
                        <span class="product-badge">BARU</span>
                        @if($p->image)
                            <img src="{{ asset('storage/' . $p->image) }}" alt="{{ $p->name }}">
                        @else
                        <img src="https://images.unsplash.com/photo-1469334031218-e382a71b716b?w=1600&q=90" alt="{{ $p->name }}">
                        @endif
                        <a href="{{ route('product.detail', $p->id) }}" class="product-add-overlay">Tambah ke Keranjang</a>
                    </div>
                </a>
                <div class="product-info">
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
            @empty
            @php
            $demoProducts = [
                ['name'=>'Dress Bunga Amara','price'=>'127.000','img'=>'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=400&q=80'],
                ['name'=>'Blouse Linen Mila','price'=>'70.000','img'=>'https://images.unsplash.com/photo-1583744946564-b52ac1c389c8?w=400&q=80'],
                ['name'=>'Kemeja Linen Mila','price'=>'50.000','img'=>'https://images.unsplash.com/photo-1562157873-818bc0726f68?w=400&q=80'],
                ['name'=>'Celana Amara','price'=>'98.000','img'=>'https://images.unsplash.com/photo-1509631179647-0177331693ae?w=400&q=80'],
                ['name'=>'Dress Midi Wrap','price'=>'109.000','img'=>'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=400&q=80'],
                ['name'=>'Cardigan Rajut Lembut','price'=>'129.000','img'=>'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=400&q=80'],
                ['name'=>'Rok Plisket Pastel','price'=>'45.000','img'=>'https://images.unsplash.com/photo-1614251056798-0a63eda2bb25?w=400&q=80'],
                ['name'=>'Set Linen Senada','price'=>'156.000','img'=>'https://images.unsplash.com/photo-1490481651871-ab68de25d43d?w=400&q=80'],
            ];
            @endphp
            @foreach($demoProducts as $demo)
            <div class="product-card">
                <div class="product-img">
                    <span class="product-badge">BARU</span>
                    <img src="{{ $demo['img'] }}" alt="{{ $demo['name'] }}">
                    <a href="{{ route('login') }}" class="product-add-overlay">Tambah ke Keranjang</a>
                </div>
                <div class="product-info">
                    <div class="product-stars">★★★★★</div>
                    <h3 class="product-name">{{ $demo['name'] }}</h3>
                    <div class="product-footer">
                        <span class="product-price">Rp {{ $demo['price'] }}</span>
                        <a href="{{ route('login') }}" class="btn-cart">+</a>
                    </div>
                </div>
            </div>
            @endforeach
            @endforelse
        </div>
    </div>
</section>

<!-- PROMO BANNER -->
<section class="promo-banner">
    <div class="container">
        <h2>Free Ongkir Pembelian di Atas <strong>Rp 50.000</strong></h2>
        <p>Berlaku untuk seluruh wilayah Indonesia</p>
        <a href="{{ route('shop') }}" class="btn btn-white">Belanja Sekarang</a>
    </div>
</section>

@endsection