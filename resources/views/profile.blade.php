@extends('layouts.app')

@section('title', 'Profil — The Daily Outfit')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
* { font-family: 'Nunito', sans-serif !important; }

.profile-wrap { background: var(--pink-50); min-height: 100vh; padding: 2rem 0; }
.profile-container { max-width: 960px; margin: 0 auto; padding: 0 1rem; }
.profile-grid { display: grid; grid-template-columns: 260px 1fr; gap: 1.5rem; align-items: start; }
@media(max-width:720px){ .profile-grid { grid-template-columns: 1fr; } }

/* SIDEBAR */
.profile-sidebar { position: sticky; top: 90px; }
.profile-avatar-card {
    background: linear-gradient(135deg, #fce7f3, #fff0f6);
    border-radius: 20px; box-shadow: var(--shadow);
    padding: 1.75rem 1.5rem; text-align: center; margin-bottom: 1rem;
    border: 1.5px solid #fbb6ce;
}
.profile-avatar-circle {
    width: 84px; height: 84px; border-radius: 50%;
    background: linear-gradient(135deg, #f472b6, #be185d);
    display: flex; align-items: center; justify-content: center;
    font-size: 2.25rem; font-weight: 800; color: white;
    margin: 0 auto 1rem;
    box-shadow: 0 6px 20px rgba(244,114,182,0.4);
    border: 3px solid white;
}
.profile-name { font-size: 17px; font-weight: 800; color: var(--brown); margin: 0 0 4px; }
.profile-email { font-size: 12px; color: var(--gray-400); margin: 0 0 12px; font-weight: 500; }
.profile-role {
    display: inline-block; font-size: 12px; font-weight: 700;
    padding: 4px 14px; border-radius: 50px;
    background: white; color: var(--pink-600);
    border: 1.5px solid #fbb6ce;
}

/* NAV */
.profile-nav { background: white; border-radius: 16px; box-shadow: var(--shadow); overflow: hidden; border: 1.5px solid #fce7f3; }

/* Base style untuk semua item nav (button maupun link) */
.profile-nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 13px 18px;
    font-size: 14px;
    font-weight: 600;
    color: var(--gray-600);
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: all 0.2s;
    width: 100%;
    text-align: left;
    cursor: pointer;
    box-sizing: border-box;
    background: none;
    /* Reset border untuk button agar sama dengan link */
    border-top: none;
    border-right: none;
    border-bottom: none;
}
.profile-nav-item:hover { background: #fdf2f8; color: var(--pink-600); }
.profile-nav-item.active { background: #fdf2f8; color: var(--pink-600); border-left-color: var(--pink-600); font-weight: 700; }
.profile-nav-item.logout { color: #ef4444; }
.profile-nav-item.logout:hover { background: #fef2f2; }

/* Khusus untuk <a> tag agar border-left bisa tampil dengan benar */
a.profile-nav-item {
    border: none;
    border-left: 3px solid transparent;
    display: flex;
}
a.profile-nav-item:hover {
    background: #fdf2f8;
    color: var(--pink-600);
    border-left-color: var(--pink-600);
}

.profile-nav-divider { height: 1px; background: #fce7f3; }

/* STATS */
.profile-stats {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.25rem;
}
.stat-card {
    background: white; border-radius: 16px; box-shadow: var(--shadow);
    padding: 1.25rem; display: flex; flex-direction: column; align-items: center;
    text-align: center; gap: 6px;
    border: 1.5px solid #fce7f3;
    transition: transform 0.2s;
    cursor: pointer;
}
.stat-card:hover { transform: translateY(-2px); }
.stat-icon { font-size: 2rem; }
.stat-value { font-size: 20px; font-weight: 800; color: var(--brown); display: block; }
.stat-label { font-size: 12px; color: var(--gray-400); display: block; font-weight: 600; }

/* CONTENT CARD */
.profile-card {
    background: white; border-radius: 20px; box-shadow: var(--shadow);
    padding: 1.75rem; margin-bottom: 1rem;
    border: 1.5px solid #fce7f3;
}
.profile-card-title {
    font-size: 16px; font-weight: 800; color: var(--gray-800);
    margin-bottom: 1.25rem; padding-bottom: 0.75rem;
    border-bottom: 2px dashed #fce7f3;
    display: flex; align-items: center; gap: 8px;
}

/* FORM */
.pf-form-group { margin-bottom: 1rem; }
.pf-label {
    display: block; font-size: 13px; font-weight: 700;
    color: var(--pink-600); margin-bottom: 6px;
}
.pf-input {
    width: 100%; padding: 11px 16px;
    border: 1.5px solid #fce7f3; border-radius: 12px;
    font-size: 14px; font-weight: 600; color: var(--gray-800);
    transition: border 0.2s; font-family: 'Nunito', sans-serif !important;
    background: #fffafb;
    box-sizing: border-box;
}
.pf-input:focus { outline: none; border-color: #f472b6; background: white; }
.pf-input[readonly] { background: var(--gray-50); opacity: 0.7; }
.pf-textarea { resize: vertical; min-height: 80px; }
.pf-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
@media(max-width:500px){ .pf-grid { grid-template-columns: 1fr; } }

.btn-save {
    padding: 12px 32px; background: linear-gradient(135deg, #f472b6, #be185d);
    color: white; border: none; border-radius: 50px;
    font-size: 14px; font-weight: 700; cursor: pointer;
    transition: all 0.2s; box-shadow: 0 4px 12px rgba(244,114,182,0.4);
    font-family: 'Nunito', sans-serif !important;
}
.btn-save:hover { opacity: 0.88; transform: translateY(-1px); }

/* Danger zone */
.danger-card {
    background: #fff5f5; border: 1.5px solid #fecaca;
    border-radius: 16px; padding: 1.25rem; margin-top: 1rem;
}
.danger-title { font-size: 14px; font-weight: 800; color: #b91c1c; margin-bottom: 6px; }
.danger-desc { font-size: 13px; color: #7f1d1d; margin-bottom: 12px; font-weight: 500; }
.btn-danger {
    padding: 10px 24px; background: #ef4444; color: white;
    border: none; border-radius: 50px; font-size: 13px; font-weight: 700;
    cursor: pointer; transition: all 0.2s;
    font-family: 'Nunito', sans-serif !important;
}
.btn-danger:hover { opacity: 0.85; transform: translateY(-1px); }
</style>

<div class="profile-wrap">
<div class="profile-container">
<div class="profile-grid">

    {{-- SIDEBAR --}}
    <div class="profile-sidebar">
        <div class="profile-avatar-card">
            <div class="profile-avatar-circle">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</div>
            <p class="profile-name">{{ $user->name }}</p>
            <p class="profile-email">{{ $user->email }}</p>
            <span class="profile-role">{{ $user->role === 'admin' ? '⚙️ Admin' : '🛍️ Customer' }}</span>
        </div>

        <nav class="profile-nav">
            <button class="profile-nav-item active" id="nav-profil" onclick="switchTab('profil')">
                👤 Data Diri
            </button>
            <div class="profile-nav-divider"></div>
            <button class="profile-nav-item" id="nav-password" onclick="switchTab('password')">
                🔒 Ganti Password
            </button>
            <div class="profile-nav-divider"></div>

            {{-- FIX: pakai onclick window.location agar pasti redirect --}}
            <a href="{{ route('orders') }}"
               class="profile-nav-item"
               onclick="window.location.href='{{ route('orders') }}'; return false;">
                📦 Pesanan Saya
            </a>

            <div class="profile-nav-divider"></div>
            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="profile-nav-item logout">🚪 Logout</button>
            </form>
        </nav>
    </div>

    {{-- MAIN --}}
    <div>
        @if(session('success'))
        <div style="background:#f0fdf4; color:#166534; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:700; margin-bottom:1rem; border:1.5px solid #bbf7d0;">
            ✅ {{ session('success') }}
        </div>
        @endif
        @if($errors->any())
        <div style="background:#fff0f3; color:#c94f7c; padding:12px 16px; border-radius:12px; font-size:13px; font-weight:700; margin-bottom:1rem; border:1.5px solid #ffd6df;">
            ⚠️ {{ $errors->first() }}
        </div>
        @endif

        {{-- STATS — klik untuk ke pesanan --}}
        <div class="profile-stats">
            <a href="{{ route('orders') }}" style="text-decoration:none;">
                <div class="stat-card">
                    <span class="stat-icon">📦</span>
                    <div>
                        <span class="stat-value">{{ $stats['total_orders'] }}</span>
                        <span class="stat-label">Total Pesanan</span>
                    </div>
                </div>
            </a>
            <a href="{{ route('orders') }}" style="text-decoration:none;">
                <div class="stat-card">
                    <span class="stat-icon">✅</span>
                    <div>
                        <span class="stat-value">{{ $stats['completed'] }}</span>
                        <span class="stat-label">Selesai</span>
                    </div>
                </div>
            </a>
            <div class="stat-card">
                <span class="stat-icon">💸</span>
                <div>
                    <span class="stat-value" style="font-size:15px;">Rp {{ number_format($stats['total_spent'], 0, ',', '.') }}</span>
                    <span class="stat-label">Total Belanja</span>
                </div>
            </div>
        </div>

        {{-- TAB: DATA DIRI --}}
        <div id="tab-profil">
            <div class="profile-card">
                <div class="profile-card-title">👤 Data Diri</div>
                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    <div class="pf-grid">
                        <div class="pf-form-group">
                            <label class="pf-label">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ $user->name }}" required class="pf-input">
                        </div>
                        <div class="pf-form-group">
                            <label class="pf-label">Email</label>
                            <input type="email" value="{{ $user->email }}" readonly class="pf-input">
                        </div>
                        <div class="pf-form-group">
                            <label class="pf-label">No. HP</label>
                            <input type="text" name="phone" value="{{ $user->phone }}" class="pf-input" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                    <div class="pf-form-group">
                        <label class="pf-label">Alamat Lengkap</label>
                        <textarea name="address" class="pf-input pf-textarea" placeholder="Jl. Nama Jalan No. X, Kota">{{ $user->address }}</textarea>
                    </div>
                    <button type="submit" class="btn-save">💾 Simpan Perubahan</button>
                </form>
            </div>
        </div>

        {{-- TAB: GANTI PASSWORD --}}
        <div id="tab-password" style="display:none;">
            <div class="profile-card">
                <div class="profile-card-title">🔒 Ganti Password</div>
                <form method="POST" action="{{ route('profile.password') }}">
                    @csrf
                    <div class="pf-form-group">
                        <label class="pf-label">Password Lama</label>
                        <input type="password" name="old_password" required class="pf-input" placeholder="Masukkan password lama">
                    </div>
                    <div class="pf-grid">
                        <div class="pf-form-group">
                            <label class="pf-label">Password Baru</label>
                            <input type="password" name="new_password" required class="pf-input" placeholder="Min. 8 karakter">
                        </div>
                        <div class="pf-form-group">
                            <label class="pf-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" required class="pf-input" placeholder="Ulangi password baru">
                        </div>
                    </div>
                    <button type="submit" class="btn-save">🔑 Ubah Password</button>
                </form>
            </div>

            {{-- DANGER ZONE --}}
            <div class="danger-card">
                <p class="danger-title">⚠️ Danger Zone</p>
                <p class="danger-desc">Menghapus akun akan menghapus semua data kamu secara permanen dan tidak dapat dipulihkan kembali.</p>
                <button class="btn-danger" onclick="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan.')">
                    🗑️ Hapus Akun Saya
                </button>
            </div>
        </div>

    </div>
</div>
</div>
</div>

@push('scripts')
<script>
function switchTab(tab) {
    document.querySelectorAll('[id^="tab-"]').forEach(t => t.style.display = 'none');
    document.querySelectorAll('[id^="nav-"]').forEach(n => n.classList.remove('active'));
    document.getElementById('tab-' + tab).style.display = 'block';
    document.getElementById('nav-' + tab).classList.add('active');
}
</script>
@endpush

@endsection