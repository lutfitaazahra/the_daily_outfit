<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'The Daily Outfit')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=3">
    <style>
    * { box-sizing: border-box; }
    .navbar { display: flex !important; align-items: center !important; justify-content: space-between !important; padding: 0 2.5rem !important; height: 68px !important; background: #fff !important; border-bottom: 1px solid #e8e8e8 !important; position: sticky !important; top: 0 !important; z-index: 100 !important; }
    .navbar-nav { display: flex !important; list-style: none !important; gap: 2rem !important; margin: 0 !important; padding: 0 !important; }
    .navbar-actions { display: flex !important; align-items: center !important; gap: 1rem !important; }
    .product-grid { display: grid !important; grid-template-columns: repeat(4, 1fr) !important; gap: 1.5rem !important; }
    .category-grid { display: grid !important; grid-template-columns: repeat(4, 1fr) !important; gap: 1rem !important; }
    </style>
    @stack('styles')
</head>
<body>
    @include('layouts.navbar')
    <main>@yield('content')</main>
    @include('layouts.footer')
    @stack('scripts')
</body>
</html>