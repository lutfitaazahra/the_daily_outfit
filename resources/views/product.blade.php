@extends('layouts.admin')

@section('title', 'Produk — Admin The Daily Outfit')

@section('content')

<div class="admin-topbar">
    <div>
        <h1>Manajemen Produk</h1>
        <p>{{ $products->count() }} produk total</p>
    </div>
    <button onclick="document.getElementById('addForm').style.display = document.getElementById('addForm').style.display === 'none' ? 'block' : 'none'"
            class="abtn abtn-pink" style="padding:12px 24px; font-size:13px;">
        + Tambah Produk
    </button>
</div>

@if(session('success'))
<div style="background:#f0fdf4; color:#16a34a; padding:12px 18px; border-radius:10px; font-size:13px; font-weight:500; margin-bottom:1.5rem;">
    {{ session('success') }}
</div>
@endif

<!-- FORM TAMBAH PRODUK -->
<div id="addForm" class="admin-panel" style="display:none; margin-bottom:1.5rem;">
    <div class="admin-panel-header">
        <h3>Tambah Produk Baru</h3>
    </div>
    <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" style="padding:1.5rem;">
        @csrf
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Nama Produk</label>
                <input type="text" name="name" required style="width:100%; padding:10px 14px; border:1px solid #f0dde2; border-radius:10px; font-size:14px; box-sizing:border-box;">
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Kategori</label>
                <select name="category_id" id="add_category_select" onchange="toggleAddSection()" style="width:100%; padding:10px 14px; border:1px solid #f0dde2; border-radius:10px; font-size:14px; background:white; box-sizing:border-box;">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" data-slug="{{ $cat->slug }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="grid-column:span 2;">
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Deskripsi</label>
                <textarea name="description" rows="3" style="width:100%; padding:10px 14px; border:1px solid #f0dde2; border-radius:10px; font-size:14px; resize:vertical; box-sizing:border-box;"></textarea>
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Harga (Rp)</label>
                <input type="number" name="price" min="0" required style="width:100%; padding:10px 14px; border:1px solid #f0dde2; border-radius:10px; font-size:14px; box-sizing:border-box;">
            </div>
            <div style="display:flex; align-items:center; padding-top:1.5rem;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:14px; color:var(--brown);">
                    <input type="checkbox" name="is_featured" value="1"> Tampilkan di Halaman Utama
                </label>
            </div>

            <!-- STOK KOMBINASI (BAJU) -->
            <div style="grid-column:span 2;" id="add-section-clothing">
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">
                    Stok per Ukuran & Warna
                </label>
                <p style="font-size:12px; color:#b09098; margin-bottom:10px;">Setiap baris = 1 kombinasi ukuran + warna + stok.</p>

                <div style="display:grid; grid-template-columns:90px 1fr 80px 36px; gap:8px; padding:0 12px; margin-bottom:6px;">
                    <span style="font-size:10px; font-weight:700; color:#b09098; text-transform:uppercase;">Ukuran</span>
                    <span style="font-size:10px; font-weight:700; color:#b09098; text-transform:uppercase;">Warna</span>
                    <span style="font-size:10px; font-weight:700; color:#b09098; text-transform:uppercase; text-align:center;">Stok</span>
                    <span></span>
                </div>

                <div id="add-combo-rows" style="display:flex; flex-direction:column; gap:8px; margin-bottom:10px;">
                    <div style="display:grid; grid-template-columns:90px 1fr 80px 36px; gap:8px; align-items:center; background:#fff8f9; border:1.5px solid #f0dde2; border-radius:10px; padding:8px 12px;">
                        <select name="combo_size[]" style="border:none; background:transparent; font-size:13px; color:#4a3840; padding:4px 2px; width:100%;">
                            <option value="">-</option>
                            <option>S</option><option>M</option><option>L</option>
                            <option>XL</option><option>XXL</option><option>Free Size</option>
                        </select>
                        <input type="text" name="combo_color[]" placeholder="cth: Dusty Pink"
                               style="border:none; background:transparent; font-size:13px; color:#4a3840; padding:4px 2px; width:100%;">
                        <input type="number" name="combo_stock[]" min="0" placeholder="0"
                               style="text-align:center; font-weight:600; border:none; background:transparent; font-size:13px; color:#4a3840; padding:4px 2px; width:100%;">
                        <button type="button" onclick="this.closest('div[style]').remove()"
                                style="background:none; border:none; color:#e0a0b0; cursor:pointer; font-size:18px; line-height:1; padding:0; text-align:center;">×</button>
                    </div>
                </div>
                <button type="button" onclick="addComboRowAdd()" class="abtn abtn-outline" style="padding:8px 16px; font-size:12px;">+ Tambah Kombinasi</button>
                <div style="margin-top:10px; padding:10px 14px; background:#fff0f3; border-radius:8px; font-size:12px; color:#c94f7c;">
                    💡 <strong>Contoh:</strong> S + Dusty Pink = 10 pcs, M + Hitam = 15 pcs
                </div>
            </div>

            <!-- STOK VARIAN (AKSESORIS) -->
            <div style="grid-column:span 2; display:none;" id="add-section-accessories">
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Stok per Varian</label>
                <div style="display:flex; gap:1rem; flex-wrap:wrap;">
                    @foreach(['Gold','Silver','Hitam','Putih','Rose Gold'] as $v)
                    <div style="display:flex; flex-direction:column; align-items:center; gap:4px; background:#fff8f9; border:1.5px solid #f0dde2; border-radius:12px; padding:12px 10px; min-width:70px;">
                        <span style="font-size:11px; font-weight:700; color:#c94f7c;">{{ $v }}</span>
                        <input type="number" name="size_{{ str_replace(' ', '_', $v) }}" value="0" min="0"
                               style="width:52px; padding:6px; text-align:center; border:1px solid #f0dde2; border-radius:8px; font-size:14px;">
                    </div>
                    @endforeach
                </div>
            </div>

            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Foto Produk</label>
                <input type="file" name="image" accept="image/*" style="width:100%; padding:8px; border:1px solid #f0dde2; border-radius:10px; font-size:13px;">
            </div>
        </div>

        <div style="margin-top:1.25rem; display:flex; gap:10px;">
            <button type="submit" class="abtn abtn-pink" style="padding:12px 28px;">Simpan Produk</button>
            <button type="button" onclick="document.getElementById('addForm').style.display='none'" class="abtn abtn-outline" style="padding:12px 28px;">Batal</button>
        </div>
    </form>
</div>

<!-- TABEL PRODUK -->
<div class="admin-panel">
    <div class="admin-table-wrap">
        <table class="admin-data-table">
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Unggulan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @foreach($products as $p)
            <tr>
                <td>
                    @if($p->image)
                    <img src="{{ str_starts_with($p->image, 'http') ? $p->image : asset('storage/' . $p->image) }}" style="width:48px; height:48px; object-fit:cover; border-radius:10px;">
                    @else
                    <div style="width:48px; height:48px; background:#fff0f3; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:700; color:#c94f7c; font-size:13px;">
                        {{ mb_substr($p->name, 0, 2) }}
                    </div>
                    @endif
                </td>
                <td><strong style="color:var(--brown);">{{ $p->name }}</strong></td>
                <td>{{ $p->category->name ?? '-' }}</td>
                <td>Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                <td>{{ $p->stock }}</td>
                <td>{{ $p->is_featured ? '⭐' : '—' }}</td>
                <td style="display:flex; gap:6px;">
                    <a href="{{ route('product.detail', $p->id) }}" target="_blank" class="abtn abtn-outline">Lihat</a>
                    <a href="{{ route('admin.products.edit', $p->id) }}" class="abtn abtn-pink">Edit</a>
                    <form method="POST" action="{{ route('admin.products.destroy', $p->id) }}" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="abtn" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca;">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
function addComboRowAdd() {
    const container = document.getElementById('add-combo-rows');
    const row = document.createElement('div');
    row.style.cssText = 'display:grid; grid-template-columns:90px 1fr 80px 36px; gap:8px; align-items:center; background:#fff8f9; border:1.5px solid #f0dde2; border-radius:10px; padding:8px 12px;';
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
        <button type="button" onclick="this.closest('div').remove()"
                style="background:none; border:none; color:#e0a0b0; cursor:pointer; font-size:18px; line-height:1; padding:0; text-align:center;">×</button>
    `;
    container.appendChild(row);
}

function toggleAddSection() {
    const select = document.getElementById('add_category_select');
    const selected = select.options[select.selectedIndex];
    const slug = selected.getAttribute('data-slug');
    const isAccessory = slug === 'accessories';
    document.getElementById('add-section-clothing').style.display = isAccessory ? 'none' : 'block';
    document.getElementById('add-section-accessories').style.display = isAccessory ? 'block' : 'none';
}
</script>

@endsection