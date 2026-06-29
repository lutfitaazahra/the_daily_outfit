@extends('layouts.admin')

@section('title', 'Edit Produk — Admin The Daily Outfit')

@section('content')

<style>
.edit-section {
    background: white;
    border-radius: 16px;
    border: 1px solid #faeaee;
    margin-bottom: 1.25rem;
    overflow: hidden;
}
.edit-section-header {
    padding: 14px 20px;
    background: #fff8f9;
    border-bottom: 1px solid #faeaee;
    font-size: 12px;
    font-weight: 700;
    color: #c94f7c;
    text-transform: uppercase;
    letter-spacing: 1px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.edit-section-body { padding: 1.25rem 1.5rem; }
.field-label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #9a8a8e;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 6px;
}
.field-input {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #f0dde2;
    border-radius: 10px;
    font-size: 14px;
    color: #4a3840;
    transition: border-color 0.2s;
    box-sizing: border-box;
}
.field-input:focus { outline: none; border-color: #c94f7c; }

/* Combo row (size + color + stok) */
.combo-row {
    display: grid;
    grid-template-columns: 90px 1fr 80px 36px;
    gap: 8px;
    align-items: center;
    background: #fff8f9;
    border: 1.5px solid #f0dde2;
    border-radius: 10px;
    padding: 8px 12px;
    transition: border-color 0.2s;
}
.combo-row:focus-within { border-color: #c94f7c; }
.combo-row select,
.combo-row input {
    border: none;
    background: transparent;
    font-size: 13px;
    color: #4a3840;
    padding: 4px 2px;
    width: 100%;
}
.combo-row select:focus,
.combo-row input:focus { outline: none; }
.combo-row input[type=number] { text-align: center; font-weight: 600; }
.btn-remove {
    background: none;
    border: none;
    color: #e0a0b0;
    cursor: pointer;
    font-size: 18px;
    line-height: 1;
    padding: 0;
    transition: color 0.2s;
    text-align: center;
}
.btn-remove:hover { color: #dc2626; }
.combo-header {
    display: grid;
    grid-template-columns: 90px 1fr 80px 36px;
    gap: 8px;
    padding: 0 12px;
    margin-bottom: 6px;
}
.combo-header span {
    font-size: 10px;
    font-weight: 700;
    color: #b09098;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.size-box {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    background: #fff8f9;
    border: 1.5px solid #f0dde2;
    border-radius: 12px;
    padding: 12px 10px;
    min-width: 70px;
}
.size-box span { font-size: 13px; font-weight: 700; color: #c94f7c; }
.size-box input {
    width: 52px; padding: 6px; text-align: center;
    border: 1px solid #f0dde2; border-radius: 8px;
    font-size: 14px; font-weight: 600; color: #4a3840; background: white;
}
</style>

<div class="admin-topbar">
    <div>
        <h1>Edit Produk</h1>
        <p style="color:#b09098;">{{ $product->name }}</p>
    </div>
    <a href="{{ route('admin.products') }}" class="abtn abtn-outline" style="padding:12px 24px;">← Kembali</a>
</div>

@if($errors->any())
<div style="background:#fff0f3; color:#c94f7c; padding:12px 18px; border-radius:10px; font-size:13px; font-weight:500; margin-bottom:1.25rem;">
    ⚠️ {{ $errors->first() }}
</div>
@endif

@if(session('success'))
<div style="background:#f0fdf4; color:#16a34a; padding:12px 18px; border-radius:10px; font-size:13px; font-weight:500; margin-bottom:1.25rem;">
    {{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
@csrf

<div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

    <!-- KOLOM KIRI -->
    <div style="display:flex; flex-direction:column; gap:1.25rem;">

        <!-- FOTO -->
        <div class="edit-section">
            <div class="edit-section-header">🖼️ Foto Produk</div>
            <div class="edit-section-body">
                <div style="display:flex; gap:1rem; align-items:center;">
                    <div style="width:90px; height:90px; border-radius:12px; overflow:hidden; background:#fff0f3; display:flex; align-items:center; justify-content:center; flex-shrink:0; border:2px solid #f0dde2;">
                        @if($product->image)
                            <img id="preview-img" src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}" style="width:100%; height:100%; object-fit:cover;">
                        @else
                            <span id="preview-text" style="font-weight:700; color:#c94f7c; font-size:1.75rem;">{{ mb_substr($product->name, 0, 2) }}</span>
                            <img id="preview-img" style="width:100%; height:100%; object-fit:cover; display:none;">
                        @endif
                    </div>
                    <div style="flex:1;">
                        <input type="file" name="image" accept="image/*" onchange="previewImage(this)"
                               style="width:100%; padding:9px; border:1.5px dashed #f0dde2; border-radius:10px; font-size:12px; cursor:pointer; box-sizing:border-box;">
                        <p style="font-size:11px; color:#b09098; margin-top:6px;">Kosongkan jika tidak ingin mengganti foto.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- INFO DASAR -->
        <div class="edit-section">
            <div class="edit-section-header">📝 Informasi Dasar</div>
            <div class="edit-section-body" style="display:flex; flex-direction:column; gap:1rem;">
                <div>
                    <label class="field-label">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="field-input">
                </div>
                <div>
                    <label class="field-label">Kategori</label>
                    <select name="category_id" id="category_select" onchange="toggleSection()" class="field-input" style="background:white; cursor:pointer;">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="field-label">Deskripsi</label>
                    <textarea name="description" rows="4" class="field-input" style="resize:vertical;">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>

        <!-- HARGA -->
        <div class="edit-section">
            <div class="edit-section-header">💰 Harga</div>
            <div class="edit-section-body">
                <div>
                    <label class="field-label">Harga (Rp)</label>
                    <input type="number" name="price" min="0" value="{{ old('price', $product->price) }}" required class="field-input">
                </div>
                <p style="font-size:11px; color:#b09098; margin-top:8px;">Total stok dihitung otomatis dari kombinasi ukuran & warna.</p>
            </div>
        </div>

        <!-- FEATURED -->
        <div class="edit-section">
            <div class="edit-section-body">
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}
                           style="width:16px; height:16px; accent-color:#c94f7c; cursor:pointer;">
                    <div>
                        <div style="font-size:13px; font-weight:600; color:var(--brown);">Tampilkan di Halaman Utama</div>
                        <div style="font-size:11px; color:#b09098;">Produk akan muncul di bagian unggulan toko</div>
                    </div>
                </label>
            </div>
        </div>

    </div>

    <!-- KOLOM KANAN -->
    <div style="display:flex; flex-direction:column; gap:1.25rem;">

        <!-- STOK KOMBINASI (BAJU) -->
        <div class="edit-section" id="section-clothing">
            <div class="edit-section-header">📐 Stok per Ukuran & Warna</div>
            <div class="edit-section-body">
                <p style="font-size:12px; color:#b09098; margin-bottom:12px;">
                    Setiap baris = 1 kombinasi ukuran + warna. Stok total dihitung otomatis.
                </p>

                <div class="combo-header">
                    <span>Ukuran</span>
                    <span>Warna</span>
                    <span style="text-align:center;">Stok</span>
                    <span></span>
                </div>

                <div id="combo-rows" style="display:flex; flex-direction:column; gap:8px; margin-bottom:10px;">
                    @php
                        $existingCombos = $product->sizes->filter(fn($s) => $s->size !== null || $s->color !== null);
                    @endphp
                    @forelse($existingCombos as $combo)
                    <div class="combo-row">
                        <select name="combo_size[]">
                            <option value="">-</option>
                            @foreach(['S','M','L','XL','XXL','Free Size'] as $sz)
                            <option value="{{ $sz }}" {{ $combo->size === $sz ? 'selected' : '' }}>{{ $sz }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="combo_color[]" value="{{ $combo->color }}" placeholder="cth: Dusty Pink">
                        <input type="number" name="combo_stock[]" value="{{ $combo->stock }}" min="0" placeholder="0">
                        <button type="button" onclick="this.closest('.combo-row').remove()" class="btn-remove">×</button>
                    </div>
                    @empty
                    <div class="combo-row">
                        <select name="combo_size[]">
                            <option value="">-</option>
                            @foreach(['S','M','L','XL','XXL','Free Size'] as $sz)
                            <option value="{{ $sz }}">{{ $sz }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="combo_color[]" placeholder="cth: Dusty Pink">
                        <input type="number" name="combo_stock[]" min="0" placeholder="0">
                        <button type="button" onclick="this.closest('.combo-row').remove()" class="btn-remove">×</button>
                    </div>
                    @endforelse
                </div>

                <button type="button" onclick="addComboRow()" class="abtn abtn-outline" style="padding:7px 14px; font-size:12px; width:100%;">
                    + Tambah Kombinasi
                </button>

                <div style="margin-top:14px; padding:10px 14px; background:#fff0f3; border-radius:8px; font-size:12px; color:#c94f7c;">
                    💡 <strong>Contoh:</strong> S + Dusty Pink = 10 pcs, M + Hitam = 15 pcs, L + Dusty Pink = 8 pcs
                </div>
            </div>
        </div>

        <!-- AKSESORIS -->
        <div class="edit-section" id="section-accessories" style="display:none;">
            <div class="edit-section-header">✨ Stok Aksesoris</div>
            <div class="edit-section-body">
                @php
                    $hasVariants = (bool) $product->has_variants;
                    $variantList = ['Gold','Silver','Rose Gold'];
                @endphp

                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13px; font-weight:600; color:var(--brown); margin-bottom:14px;">
                    <input type="checkbox" id="edit_has_variants" name="accessory_has_variants" value="1"
                           onchange="toggleEditAccessoryMode()" {{ $hasVariants ? 'checked' : '' }}>
                    Aktifkan Varian Warna (misal: Gold / Silver / Rose Gold)
                </label>

                <!-- MODE: FIX PINK -->
                <div id="edit-accessory-fixed" style="{{ $hasVariants ? 'display:none;' : '' }}">
                    @php $accessoryStock = $product->sizes->where('color', 'Pink')->sum('stock'); @endphp
                    <label class="field-label">Jumlah Stok (Warna: Pink)</label>
                    <input type="number" name="accessory_stock" value="{{ $accessoryStock }}" min="0" class="field-input" style="max-width:160px;">
                    <p style="font-size:11px; color:#b09098; margin-top:10px;">Produk ini tidak punya pilihan varian warna lain — fix Pink.</p>
                </div>

                <!-- MODE: VARIAN -->
                <div id="edit-accessory-variants" style="{{ $hasVariants ? '' : 'display:none;' }}">
                    <div style="display:flex; gap:12px; flex-wrap:wrap;">
                        @foreach($variantList as $v)
                        @php $variantData = $product->sizes->firstWhere('size', $v); @endphp
                        <div class="size-box">
                            <span style="font-size:11px;">{{ $v }}</span>
                            <input type="number" name="variant_{{ str_replace(' ', '_', $v) }}" value="{{ $variantData->stock ?? 0 }}" min="0">
                        </div>
                        @endforeach
                    </div>
                    <p style="font-size:11px; color:#b09098; margin-top:10px;">Isi 0 jika varian tidak tersedia.</p>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- TOMBOL AKSI -->
<div style="display:flex; gap:10px; margin-top:1.25rem; justify-content:flex-end;">
    <a href="{{ route('admin.products') }}" class="abtn abtn-outline" style="padding:12px 28px;">Batal</a>
    <button type="submit" class="abtn abtn-pink" style="padding:12px 32px;">💾 Simpan Perubahan</button>
</div>

</form>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('preview-img');
            const text = document.getElementById('preview-text');
            img.src = e.target.result;
            img.style.display = 'block';
            if (text) text.style.display = 'none';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function addComboRow() {
    const container = document.getElementById('combo-rows');
    const row = document.createElement('div');
    row.className = 'combo-row';
    row.innerHTML = `
        <select name="combo_size[]" style="border:none; background:transparent; font-size:13px; color:#4a3840; padding:4px 2px; width:100%;">
            <option value="">-</option>
            <option>S</option><option>M</option><option>L</option>
            <option>XL</option><option>XXL</option><option>Free Size</option>
        </select>
        <input type="text" name="combo_color[]" placeholder="cth: Dusty Pink"
               style="border:none; background:transparent; font-size:13px; color:#4a3840; padding:4px 2px; width:100%;">
        <input type="number" name="combo_stock[]" min="0" placeholder="0"
               style="text-align:center; font-weight:600; border:none; background:transparent; font-size:13px; color:#4a3840; padding:4px 2px; width:100%;">
        <button type="button" onclick="this.closest('.combo-row').remove()" class="btn-remove">×</button>
    `;
    container.appendChild(row);
}

function toggleSection() {
    const select = document.getElementById('category_select');
    const selected = select.options[select.selectedIndex];
    const slug = selected.getAttribute('data-slug');
    const isAccessory = slug === 'accessories';
    document.getElementById('section-clothing').style.display = isAccessory ? 'none' : 'block';
    document.getElementById('section-accessories').style.display = isAccessory ? 'block' : 'none';
}

function toggleEditAccessoryMode() {
    const checked = document.getElementById('edit_has_variants').checked;
    document.getElementById('edit-accessory-fixed').style.display = checked ? 'none' : 'block';
    document.getElementById('edit-accessory-variants').style.display = checked ? 'block' : 'none';
}

toggleSection();
</script>

@endsection