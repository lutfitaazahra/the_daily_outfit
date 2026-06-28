<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - The Daily Outfit')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body { margin:0; font-family: var(--font-sans, 'Poppins', sans-serif); background:#faf7f8; }
        .admin-wrapper { display:flex; min-height:100vh; }

        /* SIDEBAR */
        .admin-sidebar {
            width:240px; background:linear-gradient(180deg, #fff0f3 0%, #fff 100%);
            border-right:1px solid #f3d9e0; padding:1.5rem 1rem; flex-shrink:0;
            display:flex; flex-direction:column; position:sticky; top:0; height:100vh;
        }
        .admin-sidebar-brand {
            font-family: var(--font, 'Playfair Display', serif);
            font-size:1.4rem; font-weight:700; color:var(--brown, #5a3825);
            margin-bottom:0.25rem; padding:0 0.5rem;
        }
        .admin-sidebar-sub { font-size:11px; color:#c94f7c; letter-spacing:2px; text-transform:uppercase; padding:0 0.5rem; margin-bottom:2rem; font-weight:600; }
        .admin-nav { display:flex; flex-direction:column; gap:4px; flex:1; }
        .admin-nav a {
            display:flex; align-items:center; gap:12px; padding:11px 14px;
            border-radius:10px; color:#7a5a63; text-decoration:none;
            font-size:14px; font-weight:500; transition:all 0.2s;
        }
        .admin-nav a:hover { background:#fff0f3; color:#c94f7c; }
        .admin-nav a.active { background:#c94f7c; color:white; box-shadow:0 4px 12px rgba(201,79,124,0.25); }
        .admin-nav .nav-icon { font-size:18px; width:22px; text-align:center; }
        .admin-sidebar-footer { border-top:1px solid #f3d9e0; padding-top:1rem; margin-top:1rem; }
        .admin-sidebar-footer a {
            display:flex; align-items:center; gap:12px; padding:11px 14px;
            border-radius:10px; color:#7a5a63; text-decoration:none; font-size:14px; font-weight:500;
        }
        .admin-sidebar-footer a:hover { background:#fff0f3; color:#c94f7c; }

        /* MAIN */
        .admin-main { flex:1; padding:2rem 2.5rem; min-width:0; }
        .admin-topbar {
            display:flex; align-items:center; justify-content:space-between;
            margin-bottom:2rem; flex-wrap:wrap; gap:1rem;
        }
        .admin-topbar h1 {
            font-family: var(--font, 'Playfair Display', serif);
            font-size:1.8rem; font-weight:700; color:var(--brown, #5a3825); margin:0;
        }
        .admin-topbar p { font-size:13px; color:#9a8a8e; margin:4px 0 0; }
        .admin-user-chip {
            display:flex; align-items:center; gap:10px; background:white;
            padding:8px 16px; border-radius:50px; box-shadow:0 2px 10px rgba(0,0,0,0.04);
        }
        .admin-user-avatar {
            width:34px; height:34px; border-radius:50%; background:#c94f7c; color:white;
            display:flex; align-items:center; justify-content:center; font-weight:600; font-size:14px;
        }

        /* STATS */
        .admin-stats-grid {
            display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr));
            gap:1.25rem; margin-bottom:2rem;
        }
        .admin-stat-card {
            background:white; border-radius:16px; padding:1.5rem;
            box-shadow:0 2px 12px rgba(0,0,0,0.04); position:relative; overflow:hidden;
            transition:transform 0.2s, box-shadow 0.2s;
        }
        .admin-stat-card:hover { transform:translateY(-3px); box-shadow:0 8px 24px rgba(201,79,124,0.12); }
        .admin-stat-icon {
            width:48px; height:48px; border-radius:14px; display:flex; align-items:center;
            justify-content:center; font-size:22px; margin-bottom:1rem;
        }
        .admin-stat-label { font-size:12px; color:#9a8a8e; font-weight:500; text-transform:uppercase; letter-spacing:1px; }
        .admin-stat-value { font-family: var(--font, 'Playfair Display', serif); font-size:1.6rem; font-weight:700; color:var(--brown, #5a3825); margin-top:4px; }

        /* CARD */
        .admin-panel {
            background:white; border-radius:16px; box-shadow:0 2px 12px rgba(0,0,0,0.04); overflow:hidden;
        }
        .admin-panel-header {
            display:flex; align-items:center; justify-content:space-between;
            padding:1.25rem 1.5rem; border-bottom:1px solid #f3e9eb;
        }
        .admin-panel-header h3 {
            font-family: var(--font, 'Playfair Display', serif); font-size:1.15rem; font-weight:700;
            color:var(--brown, #5a3825); margin:0;
        }

        /* TABLE */
        .admin-table-wrap { overflow-x:auto; }
        .admin-data-table { width:100%; border-collapse:collapse; font-size:13px; }
        .admin-data-table th {
            text-align:left; padding:12px 1.5rem; font-size:11px; font-weight:600;
            color:#b09098; text-transform:uppercase; letter-spacing:1px;
            border-bottom:1px solid #f3e9eb; background:#fdfafb;
        }
        .admin-data-table td { padding:14px 1.5rem; border-bottom:1px solid #f6eef0; color:#5a4a4d; }
        .admin-data-table tr:last-child td { border-bottom:none; }
        .admin-data-table tr:hover td { background:#fffafb; }

        /* BADGES */
        .pill { display:inline-flex; align-items:center; padding:4px 12px; border-radius:50px; font-size:11px; font-weight:600; }
        .pill-pending    { background:#fff7ed; color:#ea580c; }
        .pill-processing { background:#eff6ff; color:#2563eb; }
        .pill-shipped    { background:#f0f9ff; color:#0284c7; }
        .pill-delivered  { background:#f0fdf4; color:#16a34a; }
        .pill-cancelled  { background:#fef2f2; color:#dc2626; }
        .pill-paid       { background:#f0fdf4; color:#16a34a; }
        .pill-unpaid     { background:#fff0f3; color:#c94f7c; }

        /* BUTTONS */
        .abtn { display:inline-flex; align-items:center; gap:6px; padding:8px 16px; border-radius:8px; font-size:12px; font-weight:600; text-decoration:none; border:none; cursor:pointer; transition:all 0.2s; }
        .abtn-pink { background:#c94f7c; color:white; }
        .abtn-pink:hover { background:#b03e69; }
        .abtn-outline { background:white; color:#c94f7c; border:1.5px solid #fbd5e0; }
        .abtn-outline:hover { background:#fff0f3; }

        @media (max-width: 900px) {
            .admin-sidebar { display:none; }
            .admin-main { padding:1.25rem; }
        }
    </style>
    @yield('styles')
</head>
<body>
<div class="admin-wrapper">
    <!-- SIDEBAR -->
    <aside class="admin-sidebar">
        <div class="admin-sidebar-brand">The Daily Outfit</div>
        <div class="admin-sidebar-sub">Admin Panel</div>

        <nav class="admin-nav">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Dashboard
            </a>
            <a href="{{ route('admin.orders') }}" class="{{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <span class="nav-icon">📦</span> Pesanan
            </a>
            <a href="{{ route('admin.products') }}" class="{{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <span class="nav-icon">👗</span> Produk
            </a>
        </nav>

        <div class="admin-sidebar-footer">
            <a href="{{ route('home') }}">
                <span class="nav-icon">🏠</span> Lihat Toko
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="all:unset; display:flex; align-items:center; gap:12px; padding:11px 14px; border-radius:10px; color:#7a5a63; font-size:14px; font-weight:500; cursor:pointer; width:100%;" onmouseover="this.style.background='#fff0f3'; this.style.color='#c94f7c'" onmouseout="this.style.background='transparent'; this.style.color='#7a5a63'">
                    <span class="nav-icon">🚪</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN -->
    <main class="admin-main">
        @yield('content')
    </main>
</div>

@yield('scripts')
</body>
</html>