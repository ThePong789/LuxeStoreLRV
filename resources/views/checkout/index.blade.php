@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
    .checkout-layout { display: grid; grid-template-columns: 1fr 380px; gap: 2.5rem; padding: 3rem 0; align-items: start; }

    .checkout-section {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
    }

    .checkout-section h3 {
        font-family: var(--font-display);
        font-size: 1.1rem;
        margin-bottom: 1.25rem;
        padding-bottom: .75rem;
        border-bottom: 1px solid var(--border);
    }

    /* ADDRESS */
    .address-card {
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        cursor: pointer;
        margin-bottom: .75rem;
        transition: all .2s;
        display: flex;
        align-items: flex-start;
        gap: .75rem;
    }

    .address-card:hover { border-color: var(--gold); }
    .address-card.selected { border-color: var(--black); background: var(--warm-white); }
    .address-card input[type=radio] { margin-top: 3px; accent-color: var(--black); }

    .address-detail {
        font-size: .875rem;
        line-height: 1.6;
        color: var(--charcoal);
    }

    .address-detail strong { font-size: .9rem; }

    /* PAYMENT */
    .payment-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: .75rem;
        margin-bottom: 1rem;
    }

    .payment-card {
        border: 2px solid var(--border);
        border-radius: 10px;
        padding: .9rem .75rem;
        cursor: pointer;
        transition: all .2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .5rem;
        text-align: center;
        position: relative;
        background: #fff;
    }

    .payment-card:hover {
        border-color: var(--gold);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,.07);
    }

    .payment-card.selected {
        border-color: var(--black);
        background: var(--warm-white);
    }

    .payment-card input[type=radio] {
        position: absolute;
        top: .6rem;
        right: .6rem;
        accent-color: var(--black);
    }

    .pay-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .icon-aba { background: #e8f4ff; color: #0066cc; }
    .icon-acleda { background: #fff0e8; color: #e05c00; }
    .icon-cod { background: #e8f8ee; color: #1a9e4a; }

    .pay-label { font-size: .78rem; font-weight: 600; }
    .pay-badge { font-size: .65rem; background: var(--gold-light); padding: .1rem .5rem; border-radius: 20px; }

    .payment-detail-panel {
        display: none;
        background: var(--warm-white);
        border-radius: 10px;
        padding: 1rem;
        margin-top: .5rem;
        border: 1.5px solid var(--border);
    }

    .payment-detail-panel.show { display: block; }

    /* SUMMARY */
    .order-summary-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.75rem;
        position: sticky;
        top: 90px;
    }

    .order-item {
        display: flex;
        align-items: center;
        gap: .75rem;
        padding: .75rem 0;
        border-bottom: 1px solid #f5f5f5;
    }

    .order-item-img {
        width: 52px;
        height: 52px;
        background: var(--warm-white);
        border-radius: 6px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .order-item-img img { width: 100%; height: 100%; object-fit: cover; }

    .new-address-form {
        display: none;
        background: var(--warm-white);
        padding: 1rem;
        border-radius: 8px;
    }

    .new-address-form.show { display: block; }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: .75rem;
    }

    .form-field input {
        width: 100%;
        padding: .55rem .85rem;
        border: 1.5px solid var(--border);
        border-radius: 6px;
    }

    @media(max-width:768px) {
        .checkout-layout { grid-template-columns: 1fr; }
        .order-summary-card { position: static; }
        .payment-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<div class="page-hero">
    <h1>Checkout</h1>
</div>

<div class="container">
    <div class="checkout-layout">

        <!-- FORM -->
        <form action="{{ route('checkout.place') }}" method="POST" id="checkout-form">
            @csrf

            <!-- ADDRESS -->
            <div class="checkout-section">
                <h3>Shipping Address</h3>

                @foreach($addresses as $addr)
                <label class="address-card {{ $loop->first ? 'selected' : '' }}" onclick="selectAddress(this)">
                    <input type="radio" name="shipping_id" value="{{ $addr->shipping_id }}" {{ $loop->first ? 'checked' : '' }}>
                    <div class="address-detail">
                        <strong>{{ $addr->full_name }}</strong><br>
                        {{ $addr->address }}, {{ $addr->city }}
                    </div>
                </label>
                @endforeach

                <button type="button" onclick="toggleNewAddress()">+ New Address</button>

                <div class="new-address-form" id="new-address-form">
                    <input type="text" name="new_address[full_name]" placeholder="Full Name">
                    <input type="text" name="new_address[address]" placeholder="Address">
                </div>
            </div>

            <!-- PAYMENT -->
            <div class="checkout-section">
                <h3>Payment Method</h3>

                <div class="payment-grid">

                    <div class="payment-card selected" onclick="selectPayment(this, 'aba')">
                        <input type="radio" name="payment_method" value="aba" checked>
                        <div class="pay-icon icon-aba">ABA</div>
                        <div class="pay-label">ABA Bank</div>
                        <div class="pay-badge">QR</div>
                    </div>

                    <div class="payment-card" onclick="selectPayment(this, 'acleda')">
                        <input type="radio" name="payment_method" value="acleda">
                        <div class="pay-icon icon-acleda">ACL</div>
                        <div class="pay-label">ACLEDA</div>
                        <div class="pay-badge">QR</div>
                    </div>

                    <div class="payment-card" onclick="selectPayment(this, 'cod')">
                        <input type="radio" name="payment_method" value="cod">
                        <div class="pay-icon icon-cod">COD</div>
                        <div class="pay-label">Cash</div>
                    </div>

                </div>

                <div class="payment-detail-panel show" id="panel-aba">
                    ABA QR Payment
                </div>

                <div class="payment-detail-panel" id="panel-acleda">
                    ACLEDA QR Payment
                </div>

                <div class="payment-detail-panel" id="panel-cod">
                    Pay on delivery
                </div>

            </div>

            <button type="submit">Place Order</button>
        </form>

        <!-- SUMMARY -->
        <div class="order-summary-card">
            <h3>Order Summary</h3>

            @php $subtotal = $cart->items->sum(fn($i) => $i->price * $i->qty); @endphp

            @foreach($cart->items as $item)
            <div class="order-item">
                <div class="order-item-img">
                    <img src="{{ asset('storage/'.$item->product->product_image) }}">
                </div>
                <div>
                    {{ $item->product->product_name }}
                </div>
            </div>
            @endforeach

            <hr>

            <div>Total: ${{ number_format($subtotal, 2) }}</div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
let currentMethod = 'aba';

function selectPayment(el, method) {
    document.querySelectorAll('.payment-card').forEach(e => e.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input').checked = true;
    currentMethod = method;

    document.querySelectorAll('.payment-detail-panel').forEach(p => p.classList.remove('show'));
    const panel = document.getElementById('panel-' + method);
    if (panel) panel.classList.add('show');
}

function selectAddress(el) {
    document.querySelectorAll('.address-card').forEach(a => a.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input').checked = true;
}

function toggleNewAddress() {
    document.getElementById('new-address-form').classList.toggle('show');
}
</script>
@endpush