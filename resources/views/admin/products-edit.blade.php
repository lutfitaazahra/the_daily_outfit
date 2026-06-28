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
    transition: border-color 0.2s;
}
.size-box:focus-within { border-color: #c94f7c; }
.size-box span {
    font-size: 13px;
    font-weight: 700;
    color: #c94f7c;
}
.size-box input {
    width: 52px;
    padding: 6px;
    text-align: center;
    border: 1px solid #f0dde2;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #4a3840;
    background: white;
}
.size-box input:focus { outline: none; border-color: #c94f7c; }
.color-row {
    display: flex;
    gap: 10px;
    align-items: center;
    background: #fff8f9;
    border: 1.5px solid #f0dde2;
    border-radius: 10px;
    padding: 8px 12px;
    transition: border-color 0.2s;
}
.color-row:focus-within { border-color: #c94f7c; }
.color-row input {
    border: none;
    background: transparent;
    font-size: 13px;
    color: #4a3840;
    padding: 4px 0;
}
.color-row input:focus { outline: none; }
.color-row input[type=number] { width: 70px; text-align: center; font-weight: 600; }
.color-divider { width: 1px; height: 20px; background: #f0dde2; flex-shrink: 0; }
.btn-remove {
    background: none;
    border: none;
    color: #e0a0b0;
    cursor: pointer;
    font-size: 18px;
    line-height: 1;
    padding: 0 2px;
    transition: color 0.2s;
}
.btn-remove:hover { color: #dc2626; }
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
                            <img id="preview-img" src="{{ asset('storage/' . $product->image) }}" style="width:100%; height:100%; object-fit:cover;">
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

        <!-- HARGA & STOK -->
        <div class="edit-section">
            <div class="edit-section-header">💰 Harga & Stok</div>
            <div class="edit-section-body" style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div>
                    <label class="field-label">Harga (Rp)</label>
                    <input type="number" name="price" min="0" value="{{ old('price', $product->price) }}" required class="field-input">
                </div>
                <div>
                    <label class="field-label">Total Stok</label>
                    <input type="number" name="stock" min="0" value="{{ old('stock', $product->stock) }}" class="field-input">
                </div>
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

        <!-- STOK PER UKURAN (BAJU) -->
        <div class="edit-section" id="section-clothing">
            <div class="edit-section-header">📐 Stok per Ukuran</div>
            <div class="edit-section-body">
                <div style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:1.5rem;">
                    @foreach(['S','M','L','XL'] as $sz)
                    @php $sizeData = $product->sizes->firstWhere('size', $sz); @endphp
                    <div class="size-box">
                        <span>{{ $sz }}</span>
                        <input type="number" name="size_{{ $sz }}" value="{{ $sizeData->stock ?? 0 }}" min="0">
                    </div>
                    @endforeach
                </div>

                <div style="border-top:1px solid #faeaee; padding-top:1.25rem;">
                    <label class="field-label" style="margin-bottom:10px;">🎨 Stok per Warna</label>
                    @php $productColors = $product->sizes->filter(fn($s) => !empty($s->color)); @endphp
                    <div id="color-rows" style="display:flex; flex-direction:column; gap:8px; margin-bottom:10px;">
                        @forelse($productColors as $color)
                        <div class="color-row">
                            <span style="font-size:14px;">🎨</span>
                            <input type="text" name="color_name[]" value="{{ $color->color }}" placeholder="Nama warna" style="flex:1;">
                            <div class="color-divider"></div>
                            <input type="number" name="color_stock[]" value="{{ $color->stock }}" min="0" placeholder="0">
                            <span style="font-size:11px; color:#b09098;">pcs</span>
                            <button type="button" onclick="this.closest('.color-row').remove()" class="btn-remove">×</button>
                        </div>
                        @empty
                        <div class="color-row">
                            <span style="font-size:14px;">🎨</span>
                            <input type="text" name="color_name[]" placeholder="Nama warna" style="flex:1;">
                            <div class="color-divider"></div>
                            <input type="number" name="color_stock[]" min="0" placeholder="0">
                            <span style="font-size:11px; color:#b09098;">pcs</span>
                            <button type="button" onclick="this.closest('.color-row').remove()" class="btn-remove">×</button>
                        </div>
                        @endforelse
                    </div>
                    <button type="button" onclick="addColorRow()" class="abtn abtn-outline" style="padding:7px 14px; font-size:12px; width:100%;">
                        + Tambah Warna
                    </button>
                </div>
            </div>
        </div>

        <!-- STOK PER VARIAN (AKSESORIS) -->
        <div class="edit-section" id="section-accessories" style="display:none;">
            <div class="edit-section-header">✨ Stok per Varian</div>
            <div class="edit-section-body">
                <div style="display:flex; gap:12px; flex-wrap:wrap;">
                    @php $variants = ['Gold','Silver','Hitam','Putih','Rose Gold']; @endphp
                    @foreach($variants as $v)
                    @php $variantData = $product->sizes->firstWhere('size', $v); @endphp
                    <div class="size-box">
                        <span style="font-size:11px;">{{ $v }}</span>
                        <input type="number" name="size_{{ $v }}" value="{{ $variantData->stock ?? 0 }}" min="0">
                    </div>
                    @endforeach
                </div>
                <p style="font-size:11px; color:#b09098; margin-top:10px;">Isi 0 jika varian tidak tersedia.</p>
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

function addColorRow() {
    const container = document.getElementById('color-rows');
    const row = document.createElement('div');
    row.className = 'color-row';
    row.innerHTML = `
        <span style="font-size:14px;">🎨</span>
        <input type="text" name="color_name[]" placeholder="Nama warna" style="flex:1; border:none; background:transparent; font-size:13px; color:#4a3840; padding:4px 0;">
        <div class="color-divider"></div>
        <input type="number" name="color_stock[]" min="0" placeholder="0" style="width:70px; text-align:center; font-weight:600; border:none; background:transparent; font-size:13px; color:#4a3840; padding:4px 0;">
        <span style="font-size:11px; color:#b09098;">pcs</span>
        <button type="button" onclick="this.closest('.color-row').remove()" class="btn-remove">×</button>
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

toggleSection();
</script>

@endsection