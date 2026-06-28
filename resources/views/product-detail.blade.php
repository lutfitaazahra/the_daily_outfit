@extends('layouts.app')

@section('title', $product->name . ' — The Daily Outfit')

@section('content')

@php
    $isAccessory = $product->category && $product->category->slug === 'accessories';
    $sizes  = $product->sizes->whereNull('color')->whereNotNull('size')->where('stock', '>', 0);
    $colors = $product->sizes->whereNotNull('color')->where('stock', '>', 0);
    $variants = $isAccessory ? $product->sizes->where('stock', '>', 0) : collect();

    // Parse Markdown sederhana ke HTML
    function parseDesc($text) {
        $text = e($text); // escape HTML dulu biar aman

        // Heading ## dan ###
        $text = preg_replace('/^### (.+)$/m', '<h4 style="font-size:14px;font-weight:700;color:var(--brown);margin:1rem 0 0.5rem;">$1</h4>', $text);
        $text = preg_replace('/^## (.+)$/m', '<h3 style="font-size:16px;font-weight:700;color:var(--brown);margin:1.25rem 0 0.5rem;">$1</h3>', $text);

        // Bold **text**
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);

        // Tabel markdown: baris yang ada | ... |
        // Pisahkan per baris dulu
        $lines = explode("\n", $text);
        $output = '';
        $inTable = false;
        $tableHtml = '';
        $isHeader = true;

        foreach ($lines as $i => $line) {
            $trimmed = trim($line);

            // Deteksi baris tabel
            if (strpos($trimmed, '|') !== false) {
                // Skip baris separator (---|---)
                if (preg_match('/^\|[\s\-\|]+\|$/', $trimmed)) {
                    $isHeader = false;
                    continue;
                }

                if (!$inTable) {
                    $inTable = true;
                    $isHeader = true;
                    $tableHtml = '<div style="overflow-x:auto;margin:1rem 0;"><table style="width:100%;border-collapse:collapse;font-size:13px;">';
                }

                // Parse kolom
                $cols = array_map('trim', explode('|', trim($trimmed, '|')));
                $tag = $isHeader ? 'th' : 'td';
                $style = $isHeader
                    ? 'style="padding:8px 12px;background:var(--pink-50);color:var(--pink-600);font-weight:700;border:1px solid #fce7f3;text-align:left;"'
                    : 'style="padding:8px 12px;border:1px solid #fce7f3;color:var(--gray-700);"';

                $tableHtml .= '<tr>';
                foreach ($cols as $col) {
                    if ($col !== '') $tableHtml .= "<{$tag} {$style}>{$col}</{$tag}>";
                }
                $tableHtml .= '</tr>';

            } else {
                // Tutup tabel kalau ada
                if ($inTable) {
                    $output .= $tableHtml . '</table></div>';
                    $inTable = false;
                    $tableHtml = '';
                    $isHeader = true;
                }

                // Baris bullet * atau -
                if (preg_match('/^\* (.+)$/', $trimmed, $m)) {
                    $output .= '<li style="font-size:14px;color:var(--gray-600);line-height:1.8;margin-left:1rem;">' . $m[1] . '</li>';
                } elseif (preg_match('/^- (.+)$/', $trimmed, $m)) {
                    $output .= '<li style="font-size:14px;color:var(--gray-600);line-height:1.8;margin-left:1rem;">' . $m[1] . '</li>';
                } elseif ($trimmed === '') {
                    $output .= '<br>';
                } else {
                    // Kalau tidak diawali tag HTML (heading sudah jadi tag)
                    if (substr($trimmed, 0, 1) === '<') {
                        $output .= $trimmed;
                    } else {
                        $output .= '<p style="font-size:14px;color:var(--gray-600);line-height:1.8;margin:0 0 0.5rem;">' . $trimmed . '</p>';
                    }
                }
            }
        }

        // Tutup tabel kalau masih terbuka
        if ($inTable) {
            $output .= $tableHtml . '</table></div>';
        }

        return $output;
    }
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
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                         style="width:100%; height:100%; object-fit:cover;">
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
                           margin:0.5rem 0 0.75rem; line-height:1.2;">
                    {{ $product->name }}
                </h1>

                <div style="font-size:1.5rem; font-weight:700; color:var(--gray-800); margin-bottom:1.25rem;">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </div>

                @if($product->description)
                <div style="margin-bottom:1.5rem;">
                    {!! parseDesc($product->description) !!}
                </div>
                @endif

                @auth
                <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    @if($isAccessory)
                        @if($variants->count() > 0)
                        <div style="margin-bottom:1.5rem;">
                            <p style="font-size:13px; font-weight:600; margin-bottom:0.75rem; color:var(--gray-800);">Pilih Varian</p>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                @foreach($variants as $variant)
                                <label style="cursor:pointer;">
                                    <input type="radio" name="size" value="{{ $variant->size }}" style="display:none;" {{ $loop->first ? 'checked' : '' }}>
                                    <span class="size-option" style="display:inline-flex; align-items:center; justify-content:center; padding:8px 16px; border-radius:8px; border:1.5px solid var(--gray-200); font-size:13px; font-weight:600; transition:all 0.2s; white-space:nowrap;">
                                        {{ $variant->size }}
                                        <span style="font-size:11px; color:var(--gray-400); margin-left:4px;">({{ $variant->stock }})</span>
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="size" value="One Size">
                        @endif

                    @else
                        @if($sizes->count() > 0)
                        <div style="margin-bottom:1.5rem;">
                            <p style="font-size:13px; font-weight:600; margin-bottom:0.75rem; color:var(--gray-800);">Pilih Ukuran</p>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                @foreach($sizes as $size)
                                <label style="cursor:pointer;">
                                    <input type="radio" name="size" value="{{ $size->size }}" style="display:none;" {{ $loop->first ? 'checked' : '' }}>
                                    <span class="size-option" style="display:inline-flex; align-items:center; justify-content:center; width:44px; height:44px; border-radius:8px; border:1.5px solid var(--gray-200); font-size:13px; font-weight:600; transition:all 0.2s;">
                                        {{ $size->size }}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <input type="hidden" name="size" value="Free Size">
                        @endif

                        @if($colors->count() > 0)
                        <div style="margin-bottom:1.5rem;">
                            <p style="font-size:13px; font-weight:600; margin-bottom:0.75rem; color:var(--gray-800);">Pilih Warna</p>
                            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                @php
                                $colorMap = [
                                    'Hitam'        => '#1a1a1a',
                                    'Putih'        => '#f5f5f5',
                                    'Abu'          => '#9ca3af',
                                    'Grey'         => '#9ca3af',
                                    'Navy'         => '#1e3a5f',
                                    'Coklat'       => '#92400e',
                                    'Krem'         => '#f5e6c8',
                                    'Pink'         => '#f9a8d4',
                                    'Merah'        => '#dc2626',
                                    'Biru'         => '#3b82f6',
                                    'Hijau'        => '#22c55e',
                                    'Kuning'       => '#fbbf24',
                                    'Ungu'         => '#a855f7',
                                    'Orange'       => '#f97316',
                                    'Maroon'       => '#7f1d1d',
                                    'Dusty Pink'   => '#e8a0a0',
                                    'Sage'         => '#87a878',
                                    'Sage Green'   => '#87a878',
                                    'Lavender'     => '#c4b5fd',
                                    'Camel'        => '#c19a6b',
                                    'Olive'        => '#6b7c3a',
                                    'Olive Green'  => '#6b7c3a',
                                    'Tosca'        => '#2dd4bf',
                                    'Beige'        => '#e8d5b7',
                                    'Oat Cream'    => '#f5ede0',
                                    'Harbor Green' => '#4a7c6f',
                                    'Sky Blue'     => '#7dd3fc',
                                    'Lilac'        => '#c4b5fd',
                                    'Charcoal'     => '#374151',
                                    'Emerald Green'=> '#059669',
                                    'Dusty Blue'   => '#7096a8',
                                    'Ash Blue'     => '#8fa3b1',
                                    'Ocean Blue'   => '#0369a1',
                                    'Dusty Yellow' => '#f0d060',
                                    'Atlantic Sea' => '#1e6b8a',
                                    'Rose Gold'    => '#b76e79',
                                    'Gold'         => '#d4a017',
                                    'Silver'       => '#c0c0c0',
                                    'Butteryellow' => '#f5d060',
                                    'Butter Yellow'=> '#f5d060',
                                    'Mahogany'     => '#c04000',
                                    'Biru Denim'   => '#1560bd',
                                    'Denim'        => '#1560bd',
                                    'Cream'        => '#fffdd0',
                                    'Mocca'        => '#6f4e37',
                                    'Teal'         => '#008080',
                                    'Mustard'      => '#e3a857',
                                    'Terracotta'   => '#e2725b',
                                    'Burgundy'     => '#800020',
                                    'Pastel Pink'  => '#ffb6c1',
                                    'Pastel Blue'  => '#aec6cf',
                                    'Pastel Green' => '#b5ead7',
                                    'Pastel Yellow'=> '#fdfd96',
                                    'Pastel Purple'=> '#d8b4fe',
                                    'Abu-Abu'      => '#9ca3af',
                                    'Coksu'        => '#8B7355',
                                    'Cokelat'      => '#92400e',
                                    'Kopi'         => '#6f4e37',
                                    'Khaki'        => '#c3b091',
                                    'Tan'          => '#d2b48c',
                                    'Baby Pink'    => '#ffb6c1',
                                    'Soft Blue'    => '#aec6cf',
                                    'Soft Pink'    => '#ffb6c1',
                                    'Soft Green'   => '#b5ead7',
                                    'Baby Blue'    => '#aec6cf',
                                ];
                                @endphp

                                @foreach($colors as $color)
                                @php
                                    $colorKey = collect($colorMap)->keys()->first(fn($k) => strtolower($k) === strtolower($color->color));
                                    if ($colorKey) {
                                        $hex = $colorMap[$colorKey];
                                    } else {
                                        $hash = md5(strtolower($color->color));
                                        $hex  = '#' . substr($hash, 0, 6);
                                    }
                                @endphp
                                <label style="cursor:pointer;">
                                    <input type="radio" name="color" value="{{ $color->color }}" style="display:none;" {{ $loop->first ? 'checked' : '' }}>
                                    <span class="color-option" title="{{ $color->color }}" style="display:inline-flex; flex-direction:column; align-items:center; gap:4px;">
                                        <span style="width:32px; height:32px; border-radius:50%; background:{{ $hex }}; border:2px solid var(--gray-200); display:block; transition:all 0.2s;"></span>
                                        <span style="font-size:10px; color:var(--gray-500);">{{ $color->color }}</span>
                                    </span>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endif

                    <div style="margin-bottom:1.5rem;">
                        <p style="font-size:13px; font-weight:600; margin-bottom:0.75rem; color:var(--gray-800);">Jumlah</p>
                        <div style="display:inline-flex; align-items:center; border:1.5px solid var(--gray-200); border-radius:8px; overflow:hidden;">
                            <button type="button" onclick="changeQty(-1)" style="width:40px; height:40px; border:none; background:var(--pink-50); font-size:1.1rem; cursor:pointer;">−</button>
                            <input type="number" name="quantity" id="qty" value="1" min="1" max="{{ $product->stock }}" style="width:56px; height:40px; text-align:center; border:none; border-left:1px solid var(--gray-200); border-right:1px solid var(--gray-200); font-size:14px;">
                            <button type="button" onclick="changeQty(1)" style="width:40px; height:40px; border:none; background:var(--pink-50); font-size:1.1rem; cursor:pointer;">+</button>
                        </div>
                    </div>

                    <p style="font-size:13px; color:var(--gray-600); margin-bottom:1.5rem;">
                        Stok tersedia: <strong>{{ $product->stock }}</strong>
                    </p>

                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        <button type="submit" formaction="{{ route('cart.add') }}" class="btn btn-outline" style="flex:1; justify-content:center; padding:14px;">
                            Tambah ke Keranjang
                        </button>
                        <button type="submit" formaction="{{ route('buy.now') }}" class="btn btn-dark" style="flex:1; justify-content:center; padding:14px;">
                            Beli Sekarang
                        </button>
                    </div>
                    <a href="{{ route('cart') }}" class="btn btn-outline" style="width:100%; justify-content:center; padding:12px; margin-top:10px;">
                        Lihat Keranjang
                    </a>
                </form>
                @else
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
</style>

<script>
function changeQty(delta) {
    const input = document.getElementById('qty');
    let val = parseInt(input.value) + delta;
    const max = parseInt(input.max) || 10;
    if (val < 1) val = 1;
    if (val > max) val = max;
    input.value = val;
}
</script>

@endsection