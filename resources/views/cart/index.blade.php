@push('styles')
<style>
    .cart-layout { display: grid; grid-template-columns: 1fr 360px; gap: 2.5rem; padding: 3rem 0; align-items: start; }
    .cart-table { background: #fff; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
    .cart-table table { width: 100%; border-collapse: collapse; }

    .cart-table thead th {
        background: var(--warm-white);
        padding: .9rem 1.25rem;
        text-align: left;
        font-size: .8rem;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 600;
        color: var(--gray);
        border-bottom: 1px solid var(--border);
    }

    .cart-table tbody td {
        padding: 1.25rem;
        border-bottom: 1px solid #f5f5f5;
        vertical-align: middle;
    }

    .cart-product { display: flex; align-items: center; gap: 1rem; }

    .cart-product-img {
        width: 72px; height: 72px;
        background: var(--warm-white);
        border-radius: 8px;
        overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        color: var(--gray);
        flex-shrink: 0;
    }

    .cart-product-img img { width: 100%; height: 100%; object-fit: cover; }

    .qty-control {
        display: inline-flex;
        align-items: center;
        border: 1.5px solid var(--border);
        border-radius: 6px;
        overflow: hidden;
    }

    .qty-control button {
        width: 32px; height: 32px;
        background: #fff;
        border: none;
        cursor: pointer;
    }

    .qty-control input {
        width: 40px;
        text-align: center;
        border: none;
    }

    .cart-summary {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 1.75rem;
        position: sticky;
        top: 90px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: .6rem 0;
    }

    .summary-row.total {
        border-top: 1px solid var(--border);
        margin-top: .5rem;
        padding-top: 1rem;
        font-weight: 700;
    }

    /* MOBILE */
    @media(max-width: 768px) {
        .cart-layout { grid-template-columns: 1fr; padding: 1.5rem 0; }
        .cart-summary { position: static; }

        .cart-table { display: none; }

        .cart-cards { display: flex; flex-direction: column; gap: .75rem; }

        .cart-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1rem;
            display: flex;
            gap: .9rem;
        }

        .cart-card-img {
            width: 72px; height: 72px;
            background: var(--warm-white);
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cart-card-img img { width: 100%; height: 100%; object-fit: cover; }

        .cart-card-body { flex: 1; }

        .cart-card-name { font-weight: 600; font-size: .9rem; }

        .cart-card-size { font-size: .78rem; color: var(--gray); margin-bottom: .5rem; }

        .cart-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .cart-card-remove {
            background: none;
            border: none;
            color: #dc3545;
            cursor: pointer;
        }
    }

    @media(min-width: 769px) {
        .cart-cards { display: none; }
    }
</style>
@endpush