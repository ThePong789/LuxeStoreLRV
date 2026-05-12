@extends('layouts.app')

@section('title', 'Home')

@push('styles')
<style>
.hero {
    min-height: 90vh;
    display:grid;
    grid-template-columns:1fr 1fr;
    background:#0a0a0a;
}

.hero-content {
    padding:6rem;
    color:#fff;
    display:flex;
    flex-direction:column;
    justify-content:center;
}

.hero-title span { color:#c9a84c; }

.hero-image {
    background:url('https://www.cato.org/sites/cato.org/files/styles/aside_3x/public/2023-11/fast-fashion2.jpeg');
    background-size:cover;
    background-position:center;
}

.products-grid {
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:1.5rem;
}

@media(max-width:1024px){
    .products-grid { grid-template-columns:repeat(2,1fr); }
}

@media(max-width:768px){
    .hero { grid-template-columns:1fr; }
    .products-grid { grid-template-columns:1fr; }
}
</style>
@endpush

@section('content')

<section class="hero">
    <div class="hero-content">
        <h1 class="hero-title">Elevate Your <span>Style</span></h1>
        <p>Luxury fashion collection for modern lifestyle.</p>
        <a href="{{ route('shop') }}" class="btn btn-gold">Shop Now</a>
    </div>

    <div class="hero-image"></div>
</section>

@endsection