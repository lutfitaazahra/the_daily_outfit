<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar-brand">The Daily <span>Outfit</span></a>

    <ul class="navbar-nav">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('shop') }}">Shop</a></li>
        @auth
            @if(auth()->user()->is_admin)
                <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            @endif
        @endauth
    </ul>

    <div class="navbar-actions">
        @auth
            <a href="{{ route('profile') }}">{{ auth()->user()->name }}</a>
            <a href="{{ route('cart') }}" class="cart-icon">
                🛒
            </a>
            <form method="POST" action="{{ route('logout') }}" style="display:inline">
                @csrf
                <button type="submit" style="background:none;border:none;cursor:pointer;font-size:14px;color:#525252;">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary" style="padding:8px 18px;">Daftar</a>
        @endauth
    </div>
</nav>