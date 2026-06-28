<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'The Daily Outfit')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=4">
    <style>
    * { box-sizing: border-box; }
    .navbar { display: flex !important; align-items: center !important; justify-content: space-between !important; padding: 0 2.5rem !important; height: 68px !important; background: #fff !important; border-bottom: 1px solid #e8e8e8 !important; position: sticky !important; top: 0 !important; z-index: 100 !important; }
    .navbar-nav { display: flex !important; list-style: none !important; gap: 2rem !important; margin: 0 !important; padding: 0 !important; }
    .navbar-actions { display: flex !important; align-items: center !important; gap: 1rem !important; }
    .section { padding: 4rem 0 !important; }
    .container { max-width: 1200px !important; margin: 0 auto !important; padding: 0 2rem !important; }
    .category-img-grid { display: grid !important; grid-template-columns: repeat(3, 1fr) !important; gap: 1.5rem !important; }
    .category-img-card { position: relative !important; overflow: hidden !important; border-radius: 12px !important; display: block !important; aspect-ratio: 3/4 !important; }
    .category-img-card img { width: 100% !important; height: 100% !important; object-fit: cover !important; }
    .category-img-label { position: absolute !important; bottom: 0 !important; left: 0 !important; right: 0 !important; padding: 1rem !important; background: linear-gradient(to top, rgba(0,0,0,0.6), transparent) !important; color: white !important; font-weight: 600 !important; }
    .product-grid { display: grid !important; grid-template-columns: repeat(4, 1fr) !important; gap: 1.5rem !important; }
    .product-card { background: white !important; border-radius: 12px !important; overflow: hidden !important; }
    .product-img { position: relative !important; aspect-ratio: 3/4 !important; overflow: hidden !important; }
    .product-img img { width: 100% !important; height: 100% !important; object-fit: cover !important; }
    .section-title { font-size: 1.75rem !important; font-weight: 700 !important; margin-bottom: 1.5rem !important; }
    .promo-banner { background: #5a3e35 !important; color: white !important; padding: 3rem 0 !important; text-align: center !important; }
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