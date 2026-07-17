@extends('layouts.app')

@section('title', $product->name . ' — The Daily Outfit')

@section('content')

@php
    $isAccessory = $product->category && $product->category->slug === 'accessories';

    // Kumpulkan semua kombinasi dari product_sizes
    $allVariants = $product->sizes; // koleksi semua baris

    // Untuk baju: ambil ukuran unik dan warna unik
    $uniqueSizes  = $allVariants->whereNotNull('size')->pluck('size')->unique()->values();
    $uniqueColors = $allVariants->whereNotNull('color')->pluck('color')->unique()->values();

    // Build stock map: "size||color" => stock (untuk JS)
    $stockMap = [];
    foreach ($allVariants as $v) {
        $key = ($v->size ?? '') . '||' . ($v->color ?? '');
        $stockMap[$key] = $v->stock;
    }

    function parseDesc($text) {
        $text = e($text);
        $text = preg_replace('/^### (.+)$/m', '<h4 style="font-size:14px;font-weight:700;color:var(--brown);margin:1rem 0 0.5rem;">$1</h4>', $text);
        $text = preg_replace('/^## (.+)$/m', '<h3 style="font-size:16px;font-weight:700;color:var(--brown);margin:1.25rem 0 0.5rem;">$1</h3>', $text);
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);

        $lines = explode("\n", $text);
        $output = '';
        $inTable = false;
        $tableHtml = '';
        $isHeader = true;

        foreach ($lines as $i => $line) {
            $trimmed = trim($line);

            if (strpos($trimmed, '|') !== false) {
                if (preg_match('/^\|[\s\-\|]+\|$/', $trimmed)) { $isHeader = false; continue; }
                if (!$inTable) {
                    $inTable = true; $isHeader = true;
                    $tableHtml = '<div style="overflow-x:auto;margin:1rem 0;"><table style="width:100%;border-collapse:collapse;font-size:13px;">';
                }
                $cols = array_map('trim', explode('|', trim($trimmed, '|')));
                $tag = $isHeader ? 'th' : 'td';
                $style = $isHeader
                    ? 'style="padding:8px 12px;background:var(--pink-50);color:var(--pink-600);font-weight:700;border:1px solid #fce7f3;text-align:left;"'
                    : 'style="padding:8px 12px;border:1px solid #fce7f3;color:var(--gray-700);"';
                $tableHtml .= '<tr>';
                foreach ($cols as $col) { if ($col !== '') $tableHtml .= "<{$tag} {$style}>{$col}</{$tag}>"; }
                $tableHtml .= '</tr>';
            } else {
                if ($inTable) { $output .= $tableHtml . '</table></div>'; $inTable = false; $tableHtml = ''; $isHeader = true; }
                if (preg_match('/^\* (.+)$/', $trimmed, $m)) {
                    $output .= '<li style="font-size:14px;color:var(--gray-600);line-height:1.8;margin-left:1rem;">' . $m[1] . '</li>';
                } elseif (preg_match('/^- (.+)$/', $trimmed, $m)) {
                    $output .= '<li style="font-size:14px;color:var(--gray-600);line-height:1.8;margin-left:1rem;">' . $m[1] . '</li>';
                } elseif ($trimmed === '') {
                    $output .= '<br>';
                } else {
                    if (substr($trimmed, 0, 1) === '<') { $output .= $trimmed; }
                    else { $output .= '<p style="font-size:14px;color:var(--gray-600);line-height:1.8;margin:0 0 0.5rem;">' . $trimmed . '</p>'; }
                }
            }
        }
        if ($inTable) $output .= $tableHtml . '</table></div>';
        return $output;
    }

    // Semua key dalam colorMap diubah menjadi lowercase saat pengecekan di bawah
    $colorMap = [
        'hitam'=>'#1a1a1a','putih'=>'#f5f5f5','abu'=>'#9ca3af','grey'=>'#9ca3af',
        'navy'=>'#1e3a5f','coklat'=>'#92400e','krem'=>'#f5e6c8','pink'=>'#f9a8d4',
        'merah'=>'#dc2626','biru'=>'#3b82f6','hijau'=>'#22c55e','kuning'=>'#fbbf24',
        'ungu'=>'#a855f7','orange'=>'#f97316','maroon'=>'#7f1d1d','dusty pink'=>'#e8a0a0',
        'sage'=>'#87a878','sage green'=>'#87a878','lavender'=>'#c4b5fd','camel'=>'#c19a6b',
        'olive'=>'#6b7c3a','olive green'=>'#6b7c3a','tosca'=>'#2dd4bf','beige'=>'#e8d5b7',
        'oat cream'=>'#f5ede0','harbor green'=>'#4a7c6f','sky blue'=>'#7dd3fc',
        'lilac'=>'#c4b5fd','charcoal'=>'#374151','emerald green'=>'#059669',
        'dusty blue'=>'#7096a8','ash blue'=>'#8fa3b1','ocean blue'=>'#0369a1','sea blue'=>'#1e6b8a',
        'dusty yellow'=>'#f0d060','atlantic sea'=>'#1e6b8a','rose gold'=>'#b76e79',
        'gold'=>'#d4a017','silver'=>'#c0c0c0','butter yellow'=>'#f5d060',
        'butteryellow'=>'#f5d060','mahogany'=>'#c04000','biru denim'=>'#1560bd',
        'denim'=>'#1560bd','cream'=>'#fffdd0','mocca'=>'#6f4e37','teal'=>'#008080',
        'mustard'=>'#e3a857','terracotta'=>'#e2725b','burgundy'=>'#800020',
        'pastel pink'=>'#ffb6c1','pastel blue'=>'#aec6cf','pastel green'=>'#b5ead7',
        'pastel yellow'=>'#fdfd96','pastel purple'=>'#d8b4fe','abu-abu'=>'#9ca3af',
        'cokelat'=>'#92400e','kopi'=>'#6f4e37','khaki'=>'#c3b091','tan'=>'#d2b48c',
        'baby pink'=>'#ffb6c1','soft blue'=>'#aec6cf','soft pink'=>'#ffb6c1',
        'soft green'=>'#b5ead7','baby blue'=>'#aec6cf','coksu'=>'#8B7355',
    ];
@endphp

<div class="section">
    <div class="container">

        <div style="font-size:13px; color:var(--gray-400); margin-bottom:2rem;">
            <a href="{{ route('home') }}" style="color:var(--gray-400);">Home</a>
            <span style="margin:0 8px;">›</span>
            <a href="{{ route('shop') }}" style="color:var(--gray-400);">Shop</a>
            <span style="margin:0 8px;">›</span>
            <span style="color:var(--gray-800);">{{ $product->name }}</span>
        </div>

        @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:1.5rem;">{{ session('success') }}</div>
        @endif
        @if($errors->any())
        <div class="alert alert-error" style="margin-bottom:1.5rem;">{{ $errors->first() }}</div>
        @endif

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:4rem; align-items:start;">

            <!-- FOTO PRODUK -->
            <div style="border-radius:var(--radius); overflow:hidden; background:var(--pink-50); aspect-ratio:3/4;">
                @if($product->image)
                    <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}" style="width:100%; height:100%; object-fit:cover;">
                @else
                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center;
                                background:var(--pink-100); font-size:4rem; font-weight:700; color:var(--pink-400);">
                        {{ mb_substr($product->name, 0, 2) }}
                    </div>
                @endif
            </div>

            <!-- INFO PRODUK -->
            <div style="padding-top:1rem;">
                <span style="font-size:12px; font-weight:600; letter-spacing:1px; text-transform:uppercase; color:var(--pink-600);">
                    {{ $product->category->name ?? '' }}
                </span>

                <h1 style="font-family:var(--font); font-size:2.25rem; font-weight:700; color:var(--brown);
                           margin:0.5rem 0 0.75rem; line-height:1.2;">{{ $product->name }}</h1>

                <div style="font-size:1.5rem; font-weight:700; color:var(--gray-800); margin-bottom:1.25rem;">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                @if($product->description)
                <div style="margin-bottom:1.5rem;">
                    {!! parseDesc($product->description) !!}
                </div>
                @endif

                @auth
                <form method="POST" action="{{ route('cart.add') }}" id="product-form">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    @if($isAccessory)
                        {{-- AKSESORIS: pilih varian atau langsung beli --}}
                        @php $accessoryVariants = $allVariants->where('stock', '>', 0); @endphp
                        @if($accessoryVariants->count() > 0)
                        <div style="margin-bottom:1.5rem;">
                            <p style="font-size:13px; font-weight:600; margin-bottom:0.75rem; color:var(--gray-800);">Pilih Varian</p>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                @foreach($accessoryVariants as $variant)
                                <label style="cursor:pointer;">
                                    <input type="radio" name="size" value="{{ $variant->size }}" style="display:none;" {{ $loop->first ? 'checked' : '' }}>
                                    <span class="size-option" style="display:inline-flex; align-items:center; justify-content:center; padding:8px 16px; border-radius:8px; border:1.5px solid var(--gray-200); font-size:13px; font-weight:600; transition:all 0.2s;">
                                        {{ $variant->size }}
                                        <span style="font-size:11px; color:var(--gray-400); margin-left:4px;">({{ $variant->stock }})</span>
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @else
                        {{-- Tidak ada varian, langsung beli --}}
                        <input type="hidden" name="size" value="One Size">
                        <p style="font-size:13px; color:var(--gray-600); margin-bottom:1.5rem;">
                            Stok tersedia: <strong>{{ $product->stock }}</strong>
                        </p>
                        @endif

                    @else
                        {{-- BAJU: pilih ukuran dulu, lalu warna muncul sesuai --}}
                        @if($uniqueSizes->count() > 0)
                        <div style="margin-bottom:1.5rem;">
                            <p style="font-size:13px; font-weight:600; margin-bottom:0.75rem; color:var(--gray-800);">Pilih Ukuran</p>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;" id="size-picker">
                                @foreach($uniqueSizes as $sz)
                                <label style="cursor:pointer;">
                                    <input type="radio" name="size" value="{{ $sz }}" style="display:none;"
                                           onchange="onSizeChange('{{ $sz }}')" {{ $loop->first ? 'checked' : '' }}>
                                    <span class="size-option" style="display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:8px; border:1.5px solid var(--gray-200); font-size:13px; font-weight:600; transition:all 0.2s;">
                                        {{ $sz }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="size" value="Free Size">
                        @endif

                        @if($uniqueColors->count() > 0)
                        <div style="margin-bottom:1.5rem;" id="color-section">
                            <p style="font-size:13px; font-weight:600; margin-bottom:0.75rem; color:var(--gray-800);">Pilih Warna</p>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;" id="color-picker">
                                @foreach($uniqueColors as $color)
                                @php
                                    $searchKey = strtolower(trim($color));
                                    $hex = array_key_exists($searchKey, $colorMap) ? $colorMap[$searchKey] : ('#' . substr(md5($searchKey), 0, 6));
                                @endphp
                                <label style="cursor:pointer;" class="color-label" data-color="{{ $color }}">
                                    <input type="radio" name="color" value="{{ $color }}" style="display:none;"
                                           onchange="onColorChange('{{ $color }}')" {{ $loop->first ? 'checked' : '' }}>
                                    <span class="color-option" title="{{ $color }}"
                                          style="display:inline-flex; flex-direction:column; align-items:center; gap:4px;">
                                        <span style="width:32px; height:32px; border-radius:50%; background:{{ $hex }};
                                                     border:2px solid var(--gray-200); display:block; transition:all 0.2s;"></span>
                                        <span style="font-size:10px; color:var(--gray-500);">{{ $color }}</span>
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="color" value="">
                        @endif
                    @endif

                    <!-- JUMLAH -->
                    <div style="margin-bottom:1rem;">
                        <p style="font-size:13px; font-weight:600; margin-bottom:0.75rem; color:var(--gray-800);">Jumlah</p>
                        <div style="display:inline-flex; align-items:center; border:1.5px solid var(--gray-200); border-radius:8px; overflow:hidden;">
                            <button type="button" onclick="changeQty(-1)" style="width:40px; height:40px; border:none; background:var(--pink-50); font-size:1.1rem; cursor:pointer;">−</button>
                            <input type="number" name="quantity" id="qty" value="1" min="1" max="99"
                                   style="width:56px; height:40px; text-align:center; border:none; border-left:1px solid var(--gray-200); border-right:1px solid var(--gray-200); font-size:14px;">
                            <button type="button" onclick="changeQty(1)" style="width:40px; height:40px; border:none; background:var(--pink-50); font-size:1.1rem; cursor:pointer;">+</button>
                        </div>
                    </div>

                    <!-- INFO STOK DINAMIS -->
                    <div id="stock-info" style="font-size:13px; color:var(--gray-600); margin-bottom:1.5rem;">
                        Stok tersedia: <strong id="stock-count">—</strong>
                    </div>

                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button type="submit" formaction="{{ route('cart.add') }}" id="btn-cart"
                                class="btn btn-outline" style="flex:1; justify-content:center; padding:14px;">
                            Tambah ke Keranjang
                        </button>
                        <button type="submit" formaction="{{ route('buy.now') }}" id="btn-buy"
                                class="btn btn-dark" style="flex:1; justify-content:center; padding:14px;">
                            Beli Sekarang
                        </button>
                    </div>
                    <a href="{{ route('cart') }}" class="btn btn-outline"
                       style="width:100%; justify-content:center; padding:12px; margin-top:10px;">
                        Lihat Keranjang
                    </a>
                </form>

                @else
                {{-- BELUM LOGIN --}}
                <p style="font-size:13px; color:var(--gray-600); margin-bottom:1.5rem;">
                    Stok tersedia: <strong>{{ $product->stock }}</strong>
                </p>
                <a href="{{ route('login') }}" class="btn btn-dark" style="width:100%; justify-content:center; padding:14px;">
                    Login untuk Membeli
                </a>
                @endauth

                <div style="border-top:1px solid var(--gray-200); margin:2rem 0;"></div>

                <div style="display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; gap:10px; font-size:13px; color:var(--gray-600);">
                        <span>🚚</span><span>Free ongkir untuk pembelian di atas Rp 50.000</span>
                    </div>
                    <div style="display:flex; gap:10px; font-size:13px; color:var(--gray-600);">
                        <span>↩️</span><span>Pengembalian mudah dalam 7 hari</span>
                    </div>
                    <div style="display:flex; gap:10px; font-size:13px; color:var(--gray-600);">
                        <span>✅</span><span>Produk original & berkualitas</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
input[type="radio"]:checked + .size-option {
    border-color: var(--pink-600) !important;
    background: var(--pink-50);
    color: var(--pink-600);
}
input[type="radio"]:checked + .color-option span:first-child {
    border-color: var(--pink-600) !important;
    box-shadow: 0 0 0 3px rgba(201,79,124,0.25);
}
.size-option.disabled-size {
    opacity: 0.35;
    cursor: not-allowed;
    text-decoration: line-through;
}
.color-label.hidden-color { display: none; }
</style>

<script>
const stockMap = @json($stockMap);
const allVariants = @json($allVariants->map(fn($v) => ['size' => $v->size, 'color' => $v->color, 'stock' => $v->stock])->values());

function changeQty(delta) {
    const qtyInput = document.getElementById('qty');
    if (!qtyInput) return;
    let val = parseInt(qtyInput.value) || 1;
    const max = parseInt(qtyInput.max) || 99;
    const min = parseInt(qtyInput.min) || 1;
    val += delta;
    if (val < min) val = min;
    if (val > max) val = max;
    qtyInput.value = val;
}

function getSelectedSize() {
    const checked = document.querySelector('input[name="size"]:checked');
    return checked ? checked.value : '';
}

function getSelectedColor() {
    const checked = document.querySelector('input[name="color"]:checked');
    return checked ? checked.value : '';
}

function getStock(size, color) {
    const key = (size || '') + '||' + (color || '');
    return stockMap[key] ?? null;
}

function updateColorOptions(selectedSize) {
    const colorLabels = document.querySelectorAll('.color-label');
    let firstAvailable = null;

    colorLabels.forEach(label => {
        const color = label.getAttribute('data-color');
        const stock = getStock(selectedSize, color);
        const available = stock !== null && stock > 0;

        if (available) {
            label.classList.remove('hidden-color');
            if (!firstAvailable) firstAvailable = label;
        } else {
            label.classList.add('hidden-color');
            const radio = label.querySelector('input[type="radio"]');
            if (radio && radio.checked) radio.checked = false;
        }
    });

    if (firstAvailable) {
        const radio = firstAvailable.querySelector('input[type="radio"]');
        if (radio) radio.checked = true;
    }

    updateStockInfo();
}

function onSizeChange(size) {
    updateColorOptions(size);
}

function onColorChange(color) {
    updateStockInfo();
}

function updateStockInfo() {
    const size  = getSelectedSize();
    const color = getSelectedColor();
    const stock = getStock(size, color);
    const stockEl = document.getElementById('stock-count');
    const qtyInput = document.getElementById('qty');
    const btnCart = document.getElementById('btn-cart');
    const btnBuy  = document.getElementById('btn-buy');

    if (stock === null || stock === undefined) {
        if (stockEl) { stockEl.textContent = '—'; stockEl.style.color = 'var(--gray-400)'; }
    } else if (stock <= 0) {
        if (stockEl) { stockEl.textContent = 'Habis'; stockEl.style.color = '#dc2626'; }
        if (qtyInput) qtyInput.max = 0;
        if (btnCart) { btnCart.disabled = true; btnCart.style.opacity = '0.4'; }
        if (btnBuy)  { btnBuy.disabled  = true; btnBuy.style.opacity  = '0.4'; }
    } else {
        if (stockEl) { stockEl.textContent = stock + ' pcs'; stockEl.style.color = stock <= 5 ? '#f59e0b' : 'var(--gray-800)'; }
        if (qtyInput) {
            qtyInput.max = stock;
            if (parseInt(qtyInput.value) > stock) qtyInput.value = stock;
        }
        if (btnCart) { btnCart.disabled = false; btnCart.style.opacity = '1'; }
        if (btnBuy)  { btnBuy.disabled  = false; btnBuy.style.opacity  = '1'; }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const firstSize = document.querySelector('input[name="size"]:checked');
    if (firstSize) {
        updateColorOptions(firstSize.value);
    } else {
        updateStockInfo();
    }
});
</script>

@endsection