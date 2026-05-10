@extends('layouts.app')

@section('title', 'Order '.$order->order_number)

@push('styles')
<style>
    .order-detail-wrap { max-width: 860px; margin: 3rem auto; }
    .detail-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; margin-bottom: 1.5rem; overflow: hidden; }
    .detail-card-header { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); background: var(--warm-white); display: flex; align-items: center; justify-content: space-between; }
    .detail-card-header h3 { font-family: var(--font-display); font-size: 1.05rem; }
    .detail-card-body { padding: 1.5rem; }
    .order-status-track { display: flex; align-items: center; padding: 1.5rem; gap: 0; }
    .status-step { flex: 1; text-align: center; position: relative; }
    .status-step::before { content: ''; position: absolute; top: 16px; left: -50%; width: 100%; height: 2px; background: var(--border); z-index: 0; }
    .status-step:first-child::before { display: none; }
    .status-dot { width: 32px; height: 32px; border-radius: 50%; background: var(--border); display: flex; align-items: center; justify-content: center; margin: 0 auto .5rem; position: relative; z-index: 1; font-size: .75rem; color: #fff; }
    .status-dot.done { background: var(--gold); }
    .status-dot.active { background: var(--black); }
    .status-label { font-size: .75rem; color: var(--gray); }
    .status-label.done, .status-label.active { color: var(--black); font-weight: 600; }
    @media(max-width:768px) { .ship-pay-grid { grid-template-columns: 1fr !important; } }
</style>
@endpush

@section('content')
<div class="page-hero" style="padding:2.5rem;">
    <div class="breadcrumb">
        <a href="{{ route('home') }}">Home</a> <span>/</span>
        <a href="{{ route('orders.index') }}">Orders</a> <span>/</span>
        <span>{{ $order->order_number }}</span>
    </div>
</div>

<div class="container">
    <div class="order-detail-wrap">
        <!-- STATUS TRACKER -->
        @php
            $steps = ['pending','processing','shipped','delivered'];
            $currentIdx = array_search($order->status, $steps);
        @endphp
        @if($order->status !== 'cancelled')
        <div class="detail-card">
            <div class="order-status-track">
                @foreach($steps as $i => $step)
                <div class="status-step">
                    <div class="status-dot {{ $currentIdx !== false && $i < $currentIdx ? 'done' : ($currentIdx === $i ? 'active' : '') }}">
                        <i class="fas fa-{{ ['pending'=>'clock','processing'=>'cogs','shipped'=>'truck','delivered'=>'check'][$step] }}"></i>
                    </div>
                    <div class="status-label {{ $currentIdx !== false && $i <= $currentIdx ? ($currentIdx === $i ? 'active' : 'done') : '' }}">{{ ucfirst($step) }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:12px;padding:1.25rem 1.5rem;margin-bottom:1.5rem;color:#991b1b;display:flex;align-items:center;gap:.75rem;">
            <i class="fas fa-times-circle fa-lg"></i> This order has been cancelled.
        </div>
        @endif

        <!-- ITEMS -->
        <div class="detail-card">
            <div class="detail-card-header">
                <h3>Order Items</h3>
                <span style="font-size:.85rem;color:var(--gray);">{{ $order->order_number }} · {{ $order->created_at->format('M d, Y') }}</span>
            </div>
            <div class="detail-card-body" style="padding:0;">
                @foreach($order->items as $item)
                <div style="display:flex;align-items:center;gap:1rem;padding:1.1rem 1.5rem;border-bottom:1px solid #f5f5f5;">
                    <div style="width:60px;height:60px;background:var(--warm-white);border-radius:8px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--gray);">
                        @if($item->product && $item->product->product_image)
                            <img src="{{ asset('storage/'.$item->product->product_image) }}" style="width:100%;height:100%;object-fit:cover;">
                        @else <i class="fas fa-tshirt"></i> @endif
                    </div>
                    <div style="flex:1;">
                        <div style="font-weight:600;font-size:.9rem;">{{ $item->product->product_name ?? 'Product' }}</div>
                        <div style="font-size:.8rem;color:var(--gray);">Size: {{ $item->size->size_name ?? 'N/A' }} · Qty: {{ $item->qty }}</div>
                    </div>
                    <div style="text-align:right;">
                        <div style="font-weight:700;">${{ number_format($item->price * $item->qty, 2) }}</div>
                        <div style="font-size:.75rem;color:var(--gray);">${{ number_format($item->price, 2) }} each</div>
                    </div>
                </div>
                @endforeach
                <div style="padding:1.1rem 1.5rem;display:flex;justify-content:flex-end;">
                    <div style="text-align:right;">
                        <div style="font-size:.875rem;color:var(--gray);">Order Total</div>
                        <div style="font-size:1.5rem;font-family:var(--font-display);font-weight:700;">${{ number_format($order->total_price, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;" class="ship-pay-grid">
            <!-- SHIPPING -->
            @if($order->shipping)
            <div class="detail-card">
                <div class="detail-card-header"><h3><i class="fas fa-map-marker-alt" style="color:var(--gold);margin-right:.4rem;"></i> Shipping Address</h3></div>
                <div class="detail-card-body" style="font-size:.875rem;line-height:1.8;">
                    <strong>{{ $order->shipping->full_name }}</strong><br>
                    {{ $order->shipping->address }}<br>
                    {{ $order->shipping->city }}, {{ $order->shipping->province }}<br>
                    @if($order->shipping->postal_code){{ $order->shipping->postal_code }}<br>@endif
                    <i class="fas fa-phone" style="color:var(--gray);"></i> {{ $order->shipping->phone_number }}
                </div>
            </div>
            @endif

            <!-- PAYMENT -->
            @if($order->payment)
            <div class="detail-card">
                <div class="detail-card-header"><h3><i class="fas fa-credit-card" style="color:var(--gold);margin-right:.4rem;"></i> Payment</h3></div>
                <div class="detail-card-body" style="font-size:.875rem;">
                    <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;"><span>Method</span><strong>{{ ucfirst(str_replace('_',' ',$order->payment->payment_method)) }}</strong></div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;"><span>Amount</span><strong>${{ number_format($order->payment->amount, 2) }}</strong></div>
                    <div style="display:flex;justify-content:space-between;"><span>Status</span>
                        <span class="badge {{ $order->payment->status === 'paid' ? 'badge-success' : 'badge-warning' }}">{{ ucfirst($order->payment->status) }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div style="margin-top:1.5rem;display:flex;gap:1rem;">
            <a href="{{ route('orders.index') }}" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Back to Orders</a>
            <a href="{{ route('shop') }}" class="btn btn-primary">Continue Shopping</a>
        </div>
    </div>
</div>
@endsection