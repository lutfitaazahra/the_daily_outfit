<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-brand">The Daily Outfit</a>

    <ul class="navbar-nav">
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
        <li><a href="{{ route('shop') }}" class="{{ request()->routeIs('shop') && !request('sort') ? 'active' : '' }}">Toko</a></li>
        <li><a href="{{ route('new-arrivals') }}" class="{{ request()->routeIs('new-arrivals') ? 'active' : '' }}">Terbaru</a></li>
        @auth
            @if(auth()->user()->role === 'admin')
                <li><a href="{{ route('admin.dashboard') }}">Admin</a></li>
            @endif
        @endauth
    </ul>

    <form action="{{ route('shop') }}" method="GET" class="navbar-search">
        @if(request()->routeIs('shop') && request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
        @endif
        <span class="navbar-search-icon">🔍</span>
        <input
            type="text"
            name="q"
            placeholder="Cari produk..."
            value="{{ request('q') }}"
            class="navbar-search-input"
        >
        <button type="submit" class="navbar-search-btn">Cari</button>
    </form>

    <div class="navbar-actions">
        @auth
            @php
                $cartCount = \App\Models\Cart::where('user_id', auth()->id())->sum('quantity');
                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
            @endphp

            <a href="{{ route('notifications') }}" class="navbar-icon" title="Notifikasi" style="position:relative;">
                🔔
                <span class="cart-badge" style="{{ $unreadCount > 0 ? '' : 'display:none;' }}">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            </a>

            <a href="{{ route('profile') }}" class="navbar-icon" title="Profil">👤</a>

            <a href="{{ route('cart') }}" class="navbar-icon" title="Keranjang" style="position:relative;">
                🛒
                <span class="cart-badge" style="{{ $cartCount > 0 ? '' : 'display:none;' }}">
                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                </span>
            </a>

            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;font-size:13px;color:#666;font-family:var(--font-sans);letter-spacing:0.5px;">Keluar</button>
            </form>
        @else
            <a href="{{ route('login') }}" style="font-size:13px;color:#666;font-weight:500;">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-dark" style="padding:8px 20px;font-size:12px;">Daftar</a>
        @endauth
    </div>
</nav>

<style>
.navbar-search {
    display: flex;
    align-items: center;
    background: #fff8f9;
    border: 1.5px solid #f0dde2;
    border-radius: 30px;
    padding: 4px 4px 4px 16px;
    gap: 8px;
    transition: border-color 0.2s, box-shadow 0.2s;
    max-width: 280px;
}
.navbar-search:focus-within {
    border-color: #c94f7c;
    box-shadow: 0 0 0 3px rgba(201,79,124,0.12);
    background: white;
}
.navbar-search-icon {
    font-size: 13px;
    opacity: 0.5;
    flex-shrink: 0;
}
.navbar-search-input {
    border: none;
    background: transparent;
    outline: none;
    font-size: 13px;
    color: #4a3840;
    width: 160px;
    padding: 8px 0;
}
.navbar-search-input::placeholder {
    color: #b09098;
}
.navbar-search-btn {
    background: #c94f7c;
    color: white;
    border: none;
    border-radius: 24px;
    padding: 8px 18px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
    flex-shrink: 0;
}
.navbar-search-btn:hover {
    background: #b03d68;
}
</style>