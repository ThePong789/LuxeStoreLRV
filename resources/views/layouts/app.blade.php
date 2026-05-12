<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LuxeStore') | LuxeStore</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --black: #0a0a0a;
            --cream: #faf8f4;
            --warm-white: #f5f2ed;
            --gold: #c9a84c;
            --gold-light: #e8d5a3;
            --charcoal: #2d2d2d;
            --gray: #888;
            --border: #e8e4de;
            --font-display: 'Playfair Display', serif;
            --font-body: 'DM Sans', sans-serif;
        }

        * { margin:0; padding:0; box-sizing:border-box; }

        body {
            font-family: var(--font-body);
            background: var(--cream);
            color: var(--charcoal);
            padding-top: 70px;
        }

        /* NAVBAR */
        .navbar {
            position: fixed;
            top:0; left:0; right:0;
            z-index:1000;
            background: rgba(250,248,244,.95);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:0 2.5rem;
            height:70px;
        }

        .nav-brand {
            font-family: var(--font-display);
            font-size:1.6rem;
            font-weight:700;
            text-decoration:none;
            color:var(--black);
        }
        .nav-brand span { color: var(--gold); }

        .nav-links {
            display:flex;
            gap:2rem;
            list-style:none;
        }

        .nav-links a {
            text-decoration:none;
            color:var(--charcoal);
            font-size:.85rem;
            text-transform:uppercase;
        }

        .nav-actions {
            display:flex;
            align-items:center;
            gap:1rem;
        }

        .nav-icon {
            color:var(--charcoal);
            position:relative;
            text-decoration:none;
        }

        .cart-badge {
            position:absolute;
            top:-8px;
            right:-8px;
            background:var(--gold);
            color:#fff;
            font-size:.65rem;
            width:18px;
            height:18px;
            border-radius:50%;
            display:flex;
            justify-content:center;
            align-items:center;
        }

        .btn-nav {
            background: var(--black);
            color:#fff;
            padding:.5rem 1rem;
            text-decoration:none;
            border-radius:4px;
        }

        .btn-gold {
            background: var(--gold);
            color:#fff;
            padding:.5rem 1rem;
            text-decoration:none;
            border-radius:4px;
        }

        /* MOBILE */
        .hamburger {
            display:none;
            background:none;
            border:none;
            font-size:1.3rem;
        }

        .mobile-nav {
            display:none;
            position:fixed;
            top:70px;
            left:0;
            right:0;
            background:#fff;
            border-top:1px solid var(--border);
            z-index:999;
        }

        .mobile-nav.open { display:block; }

        .mobile-nav ul { list-style:none; padding:1rem; }
        .mobile-nav a {
            display:block;
            padding:.8rem 0;
            text-decoration:none;
            color:var(--charcoal);
        }

        /* FOOTER */
        footer {
            background: var(--black);
            color:#aaa;
            padding:4rem 2rem;
        }

        .footer-grid {
            display:grid;
            grid-template-columns:2fr 1fr 1fr 1fr;
            gap:2rem;
            max-width:1200px;
            margin:auto;
        }

        .footer-brand {
            color:#fff;
            font-family: var(--font-display);
            font-size:1.4rem;
        }
        .footer-brand span { color: var(--gold); }

        .footer-bottom {
            text-align:center;
            margin-top:2rem;
            font-size:.8rem;
            opacity:.6;
        }

        @media(max-width:768px){
            .nav-links { display:none; }
            .hamburger { display:block; }
            .footer-grid { grid-template-columns:1fr 1fr; }
        }
    </style>

    @stack('styles')
</head>

<body>

<nav class="navbar">
    <a href="{{ route('home') }}" class="nav-brand">Luxe<span>Store</span></a>

    <ul class="nav-links">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('shop') }}">Shop</a></li>
        <li><a href="{{ route('blog') }}">Blog</a></li>
        <li><a href="{{ route('about') }}">About</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
    </ul>

    <div class="nav-actions">
        <button class="hamburger" onclick="toggleMobileNav()">
            <i class="fas fa-bars" id="icon"></i>
        </button>

        <a href="{{ route('shop') }}" class="nav-icon"><i class="fas fa-search"></i></a>

        @auth
        <a href="{{ route('cart') }}" class="nav-icon">
            <i class="fas fa-shopping-bag"></i>
            @php $count = auth()->user()->cart?->items->count() ?? 0; @endphp
            @if($count > 0)
                <span class="cart-badge">{{ $count }}</span>
            @endif
        </a>
        @else
            <a href="{{ route('login') }}" class="btn-nav">Login</a>
            <a href="{{ route('register') }}" class="btn-gold">Register</a>
        @endauth
    </div>
</nav>

<div class="mobile-nav" id="mobile-nav">
    <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('shop') }}">Shop</a></li>
        <li><a href="{{ route('blog') }}">Blog</a></li>
        <li><a href="{{ route('about') }}">About</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
    </ul>
</div>

@yield('content')

<footer>
    <div class="footer-grid">
        <div>
            <div class="footer-brand">Luxe<span>Store</span></div>
        </div>
    </div>
    <div class="footer-bottom">
        &copy; {{ date('Y') }} LuxeStore
    </div>
</footer>

<script>
function toggleMobileNav(){
    document.getElementById('mobile-nav').classList.toggle('open');
}
</script>

@stack('scripts')

</body>
</html>