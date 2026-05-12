@extends('layouts.admin')

@section('title', 'Order '.$order->order_number)
@section('page-title', 'Order Details')
@section('breadcrumb', 'Admin / Orders / '.$order->order_number)

@section('content')
@php $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'staff'; @endphp
<div class="row col-2" style="align-items:start;">
    <div style="display:flex;flex-direction:column;gap:1.5rem;">

        <!-- ORDER ITEMS -->
        <div class="card">
            <div class="card-header">
                <h3>Order Items</h3>
                @php $colors = ['pending'=>'warning','processing'=>'info','shipped'=>'info','delivered'=>'success','cancelled'=>'danger']; @endphp
                <span class="badge badge-{{ $colors[$order->status] ?? 'secondary' }}">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Product</th><th>Size</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:.5rem;">
                                    <div style="width:36px;height:36px;background:var(--bg);border-radius:6px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:.8rem;">
                                        @if($item->product && $item->product->product_image)
                                            <img src="{{ asset('storage/'.$item->product->product_image) }}" style="width:100%;height:100%;object-fit:cover;">
                                        @else <i class="fas fa-tshirt"></i> @endif
                                    </div>
                                    {{ $item->product->product_name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>{{ $item->size->size_name ?? 'N/A' }}</td>
                            <td>{{ $item->qty }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td><strong>${{ number_format($item->price * $item->qty, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background:#f9fafb;">
                            <td colspan="4" style="text-align:right;font-weight:600;padding:.85rem 1rem;">Total</td>
                            <td style="font-weight:700;font-size:1.05rem;padding:.85rem 1rem;">${{ number_format($order->total_price, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- UPDATE STATUS -->
        <div class="card">
            <div class="card-header"><h3>Update Status</h3></div>
            <div class="card-body">
                <form action="{{ route($routePrefix.'.orders.status', $order->order_id) }}" method="POST" style="display:flex;gap:.75rem;align-items:flex-end;">
                    @csrf @method('PATCH')
                    <div style="flex:1;">
                        <label class="form-label">New Status</label>
                        <select name="status" class="form-control">
                            @foreach(['pending','processing','shipped','delivered','cancelled'] as $s)
                                <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-accent" type="submit"><i class="fas fa-save"></i> Update</button>
                </form>
            </div>
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:1.5rem;">

        <!-- CUSTOMER -->
        <div class="card">
            <div class="card-header"><h3>Customer</h3></div>
            <div class="card-body">
                <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1rem;">
                    <div style="width:40px;height:40px;background:var(--accent-light);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--accent);">
                        {{ strtoupper(substr($order->user->username ?? 'U', 0, 1)) }}
                    </div>
                    <div>
                        <div style="font-weight:600;">{{ $order->user->username ?? 'N/A' }}</div>
                        <div style="font-size:.8rem;color:var(--text-muted);">{{ $order->user->email ?? '' }}</div>
                    </div>
                </div>
                <div style="font-size:.85rem;color:var(--text-muted);">
                    Order placed on {{ $order->created_at->format('F d, Y \a\t H:i') }}
                </div>
            </div>
        </div>

        <!-- SHIPPING -->
        @if($order->shipping)
        <div class="card">
            <div class="card-header"><h3>Shipping Address</h3></div>
            <div class="card-body" style="font-size:.875rem;line-height:1.8;">
                <strong>{{ $order->shipping->full_name }}</strong><br>
                {{ $order->shipping->address }}<br>
                {{ $order->shipping->city }}, {{ $order->shipping->province }}<br>
                {{ $order->shipping->postal_code }}<br>
                <i class="fas fa-phone" style="color:var(--text-muted);margin-right:.3rem;"></i>{{ $order->shipping->phone_number }}
            </div>
        </div>
        @endif

        <!-- PAYMENT -->
        @if($order->payment)
        <div class="card">
            <div class="card-header"><h3>Payment</h3></div>
            <div class="card-body" style="font-size:.875rem;">
                <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;">
                    <span>Method</span>
                    <strong>{{ ucfirst(str_replace('_', ' ', $order->payment->payment_method)) }}</strong>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;">
                    <span>Amount</span>
                    <strong>${{ number_format($order->payment->amount, 2) }}</strong>
                </div>
                @if($order->payment->paid_at)
                <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;">
                    <span>Paid At</span>
                    <span style="color:var(--text-muted);">{{ $order->payment->paid_at->format('M d, Y H:i') }}</span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:.75rem;border-top:1px solid var(--border);margin-top:.5rem;">
                    <span>Status</span>
                    <span class="badge badge-{{ $order->payment->status === 'paid' ? 'success' : ($order->payment->status === 'failed' ? 'danger' : ($order->payment->status === 'refunded' ? 'secondary' : 'warning')) }}">
                        {{ ucfirst($order->payment->status) }}
                    </span>
                </div>
                <form action="{{ route($routePrefix.'.orders.payment-status', $order->order_id) }}" method="POST" style="display:flex;gap:.6rem;align-items:flex-end;margin-top:1rem;">
                    @csrf @method('PATCH')
                    <div style="flex:1;">
                        <label style="font-size:.8rem;font-weight:500;display:block;margin-bottom:.3rem;">Update Payment Status</label>
                        <select name="payment_status" class="form-control">
                            @foreach(['pending','paid','failed','refunded'] as $ps)
                                <option value="{{ $ps }}" {{ $order->payment->status === $ps ? 'selected' : '' }}>{{ ucfirst($ps) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn-accent btn-sm" type="submit"><i class="fas fa-save"></i> Save</button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
