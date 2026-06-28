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
                <input type="text" name="name" required style="width:100%; padding:10px 14px; border:1px solid #f0dde2; border-radius:10px; font-size:14px;">
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Kategori</label>
                <select name="category_id" style="width:100%; padding:10px 14px; border:1px solid #f0dde2; border-radius:10px; font-size:14px; background:white;">
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="grid-column:span 2;">
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Deskripsi</label>
                <textarea name="description" rows="3" style="width:100%; padding:10px 14px; border:1px solid #f0dde2; border-radius:10px; font-size:14px; resize:vertical;"></textarea>
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Harga (Rp)</label>
                <input type="number" name="price" min="0" required style="width:100%; padding:10px 14px; border:1px solid #f0dde2; border-radius:10px; font-size:14px;">
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Total Stok</label>
                <input type="number" name="stock" min="0" value="0" style="width:100%; padding:10px 14px; border:1px solid #f0dde2; border-radius:10px; font-size:14px;">
            </div>

            <!-- STOK PER UKURAN -->
            <div style="grid-column:span 2;">
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Stok per Ukuran</label>
                <div style="display:flex; gap:1rem; flex-wrap:wrap; margin-bottom:1.5rem;">
                    @foreach(['S','M','L','XL'] as $sz)
                    <div style="display:flex; flex-direction:column; align-items:center; gap:4px;">
                        <span style="font-size:12px; font-weight:600; color:var(--brown);">{{ $sz }}</span>
                        <input type="number" name="size_{{ $sz }}" value="0" min="0" style="width:60px; padding:8px; text-align:center; border:1px solid #f0dde2; border-radius:8px;">
                    </div>
                    @endforeach
                </div>

                <!-- STOK PER WARNA -->
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Stok per Warna</label>
                <div id="add-color-rows" style="display:flex; flex-direction:column; gap:10px; margin-bottom:10px;">
                    <div class="color-row" style="display:flex; gap:10px; align-items:center;">
                        <input type="text" name="color_name[]" placeholder="Nama warna (cth: Dusty Pink)"
                               style="flex:1; padding:9px 14px; border:1px solid #f0dde2; border-radius:8px; font-size:13px;">
                        <input type="number" name="color_stock[]" min="0" placeholder="Stok"
                               style="width:90px; padding:9px 14px; border:1px solid #f0dde2; border-radius:8px; font-size:13px; text-align:center;">
                        <button type="button" onclick="this.parentElement.remove()"
                                style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:8px; width:36px; height:36px; cursor:pointer; font-size:14px;">×</button>
                    </div>
                </div>
                <button type="button" onclick="addColorRowAdd()" class="abtn abtn-outline" style="padding:8px 16px; font-size:12px;">+ Tambah Warna</button>
                <p style="font-size:12px; color:#b09098; margin-top:8px;">Kosongkan nama warna untuk baris yang tidak dipakai.</p>
            </div>

            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#9a8a8e; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px;">Foto Produk</label>
                <input type="file" name="image" accept="image/*" style="width:100%; padding:8px; border:1px solid #f0dde2; border-radius:10px; font-size:13px;">
            </div>
            <div style="display:flex; align-items:center; padding-top:1.5rem;">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:14px; color:var(--brown);">
                    <input type="checkbox" name="is_featured" value="1"> Tampilkan di Halaman Utama
                </label>
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
function addColorRowAdd() {
    const container = document.getElementById('add-color-rows');
    const row = document.createElement('div');
    row.className = 'color-row';
    row.style.cssText = 'display:flex; gap:10px; align-items:center;';
    row.innerHTML = `
        <input type="text" name="color_name[]" placeholder="Nama warna (cth: Dusty Pink)"
               style="flex:1; padding:9px 14px; border:1px solid #f0dde2; border-radius:8px; font-size:13px;">
        <input type="number" name="color_stock[]" min="0" placeholder="Stok"
               style="width:90px; padding:9px 14px; border:1px solid #f0dde2; border-radius:8px; font-size:13px; text-align:center;">
        <button type="button" onclick="this.parentElement.remove()"
                style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:8px; width:36px; height:36px; cursor:pointer; font-size:14px;">×</button>
    `;
    container.appendChild(row);
}
</script>

@endsection