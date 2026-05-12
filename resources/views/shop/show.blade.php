@extends('layouts.app')

@section('title', $product->product_name)

@push('styles')
<style>
    .product-detail { padding: 4rem 0; }

    .product-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 4rem;
        align-items: start;
    }

    .product-img-wrap {
        position: sticky;
        top: 90px;
    }

    .product-main-img {
        width: 100%;
        aspect-ratio: 1;
        background: var(--warm-white);
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 6rem;
        color: var(--gray);
    }

    .product-main-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-info h1 {
        font-family: var(--font-display);
        font-size: 2rem;
        color: var(--black);
        margin-bottom: .5rem;
        line-height: 1.3;
    }

    .product-cat-badge {
        display: inline-block;
        background: var(--gold-light);
        color: #8a6d20;
        padding: .25rem .75rem;
        border-radius: 50px;
        font-size: .75rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .product-rating {
        display: flex;
        align-items: center;
        gap: .5rem;
        margin-bottom: 1.5rem;
    }

    .product-rating .stars { font-size: 1rem; }

    .product-rating span {
        font-size: .875rem;
        color: var(--gray);
    }

    .product-price {
        font-family: var(--font-display);
        font-size: 2rem;
        color: var(--black);
        margin-bottom: 1.5rem;
    }

    .product-desc {
        color: var(--gray);
        line-height: 1.8;
        font-size: .95rem;
        margin-bottom: 2rem;
        border-top: 1px solid var(--border);
        padding-top: 1.5rem;
    }

    .size-label {
        font-size: .85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
        margin-bottom: .75rem;
        display: block;
    }

    .size-options {
        display: flex;
        flex-wrap: wrap;
        gap: .5rem;
        margin-bottom: 1.5rem;
    }

    .size-btn {
        padding: .5rem 1.1rem;
        border: 1.5px solid var(--border);
        border-radius: 6px;
        font-size: .875rem;
        cursor: pointer;
        background: #fff;
        color: var(--charcoal);
        font-family: var(--font-body);
        transition: all .2s;
    }

    .size-btn:hover { border-color: var(--black); }

    .size-btn.active {
        border-color: var(--black);
        background: var(--black);
        color: #fff;
    }

    .size-btn:disabled {
        opacity: .4;
        cursor: not-allowed;
    }

    .qty-wrap {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .qty-control {
        display: flex;
        align-items: center;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
    }

    .qty-control button {
        width: 38px;
        height: 38px;
        background: #fff;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        color: var(--charcoal);
        transition: background .2s;
    }

    .qty-control button:hover {
        background: var(--warm-white);
    }

    .qty-control input {
        width: 50px;
        text-align: center;
        border: none;
        font-size: .95rem;
        font-family: var(--font-body);
    }

    .qty-control input:focus { outline: none; }

    .add-cart-btn { flex: 1; }

    .stock-info {
        font-size: .8rem;
        color: var(--gray);
        margin-top: .25rem;
    }

    .stock-info.low { color: #dc3545; }

    .tab-nav {
        display: flex;
        border-bottom: 1px solid var(--border);
        margin: 4rem 0 2rem;
    }

    .tab-btn {
        padding: .75rem 1.5rem;
        border: none;
        background: none;
        font-family: var(--font-body);
        font-size: .9rem;
        font-weight: 500;
        cursor: pointer;
        color: var(--gray);
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
    }

    .tab-btn.active {
        color: var(--black);
        border-bottom-color: var(--black);
    }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

    .review-item {
        padding: 1.5rem 0;
        border-bottom: 1px solid var(--border);
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(4,1fr);
        gap: 1.5rem;
        padding: 3rem 0;
    }

    /* RESPONSIVE */
    @media(max-width:1024px) {
        .product-layout {
            grid-template-columns: 1fr;
            gap: 2rem;
        }

        .product-img-wrap {
            position: static;
        }

        .related-grid {
            grid-template-columns: repeat(2,1fr);
        }
    }

    @media(max-width:768px) {
        .related-grid {
            grid-template-columns: repeat(1,1fr);
        }
    }
</style>
@endpush

@section('content')
<div class="page-hero" style="padding:2.5rem;">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> <span>/</span>
        <a href="{{ route('shop') }}">Shop</a> <span>/</span>
        <span>{{ $product->product_name }}</span>
    </div>
</div>

<div class="container">
    <div class="product-detail">
        <div class="product-layout">

            <!-- IMAGE -->
            <div class="product-img-wrap">
                <div class="product-main-img">
                    @if($product->product_image)
                        <img src="{{ asset('storage/'.$product->product_image) }}" alt="{{ $product->product_name }}">
                    @else
                        <i class="fas fa-tshirt"></i>
                    @endif
                </div>
            </div>

            <!-- INFO -->
            <div class="product-info">
                <span class="product-cat-badge">{{ $product->category->category_name ?? '' }}</span>

                <h1>{{ $product->product_name }}</h1>

                <div class="product-rating">
                    @php
                        $avgRating = round($product->reviews->where('is_approved',true)->avg('rating') ?? 0);
                    @endphp

                    <div class="stars">
                        @for($i=1;$i<=5;$i++)
                            <i class="fas fa-star" style="{{ $i > $avgRating ? 'color:#ddd' : 'color:var(--gold)' }}"></i>
                        @endfor
                    </div>

                    <span>{{ $product->reviews->where('is_approved',true)->count() }} reviews</span>
                </div>

                <div class="product-price" id="displayed-price">
                    @if($product->sizes->isNotEmpty())
                        From ${{ number_format($product->sizes->min('pivot.price'), 2) }}
                    @else
                        N/A
                    @endif
                </div>

                @if($product->sizes->isNotEmpty())
                <form action="{{ route('cart.add') }}" method="POST">
                    @csrf

                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                    <input type="hidden" name="size_id" id="selected-size-id">

                    <span class="size-label">Select Size</span>

                    <div class="size-options">
                        @foreach($product->sizes as $size)
                        <button type="button"
                            class="size-btn"
                            data-size-id="{{ $size->size_id }}"
                            data-price="{{ $size->pivot->price }}"
                            data-stock="{{ $size->pivot->stock_qty }}"
                            {{ $size->pivot->stock_qty == 0 ? 'disabled' : '' }}
                            onclick="selectSize(this)">
                            {{ $size->size_name }}
                        </button>
                        @endforeach
                    </div>

                    <div id="stock-info" class="stock-info" style="display:none;"></div>

                    <div class="qty-wrap">
                        <div class="qty-control">
                            <button type="button" onclick="changeQty(-1)">−</button>
                            <input type="number" name="qty" id="qty-input" value="1" min="1">
                            <button type="button" onclick="changeQty(1)">+</button>
                        </div>

                        <button type="submit" class="btn btn-primary add-cart-btn" id="add-cart-btn" disabled>
                            Add to Cart
                        </button>
                    </div>
                </form>
                @endif

                @if($product->description)
                <div class="product-desc">
                    {{ $product->description }}
                </div>
                @endif
            </div>
        </div>

        <!-- RELATED -->
        @if($related->isNotEmpty())
        <div class="related-grid">
            @foreach($related as $rp)
            <div class="product-card">
                <a href="{{ route('shop.show', $rp->product_id) }}">
                    <div class="product-card-img">
                        @if($rp->product_image)
                            <img src="{{ asset('storage/'.$rp->product_image) }}">
                        @else
                            <i class="fas fa-tshirt"></i>
                        @endif
                    </div>
                </a>
                <div class="product-card-body">
                    <h3>{{ $rp->product_name }}</h3>
                </div>
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection