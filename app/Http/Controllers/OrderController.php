<?php

namespace App\Http\Controllers;

use App\Models\{Cart, CartItem, Order, OrderItem, Payment, Shipping};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = Cart::with(['items.product', 'items.size'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()
                ->route('cart')
                ->with('error', 'Your cart is empty.');
        }

        $addresses = Shipping::where('user_id', Auth::id())->get();

        return view('checkout.index', compact('cart', 'addresses'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'shipping_id'    => 'nullable|exists:shipping,shipping_id',
            'payment_method' => 'required|in:cod,aba,acleda',
        ]);

        $cart = Cart::with(['items.product', 'items.size'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart');
        }

        $orderId = null;

        DB::transaction(function () use ($request, $cart, &$orderId) {

            $shippingId = $request->shipping_id;

            // Create new shipping address if needed
            if (!$shippingId && $request->has('new_address')) {

                $na = $request->new_address;

                $address = Shipping::create([
                    'user_id'      => Auth::id(),
                    'full_name'    => $na['full_name'] ?? '',
                    'phone_number' => $na['phone_number'] ?? '',
                    'address'      => $na['address'] ?? '',
                    'province'     => $na['province'] ?? '',
                    'city'         => $na['city'] ?? null,
                    'postal_code'  => $na['postal_code'] ?? null,
                ]);

                $shippingId = $address->shipping_id;
            }

            // Calculate total
            $total = $cart->items->sum(function ($item) {
                return $item->price * $item->qty;
            });

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id'      => Auth::id(),
                'shipping_id'  => $shippingId,
                'total_price'  => $total,
                'shipping_fee' => 0,
                'status'       => 'pending',
            ]);

            // Create order items
            foreach ($cart->items as $item) {

                OrderItem::create([
                    'order_id'   => $order->order_id,
                    'product_id' => $item->product_id,
                    'size_id'    => $item->size_id,
                    'qty'        => $item->qty,
                    'price'      => $item->price,
                ]);

                // Reduce stock
                DB::table('product_size')
                    ->where('product_id', $item->product_id)
                    ->where('size_id', $item->size_id)
                    ->decrement('stock_qty', $item->qty);
            }

            // Payment status
            $paymentStatus = $request->payment_method === 'cod'
                ? 'pending'
                : 'awaiting_payment';

            // Create payment
            Payment::create([
                'order_id'       => $order->order_id,
                'payment_method' => $request->payment_method,
                'amount'         => $total,
                'status'         => $paymentStatus,
            ]);

            // Clear cart
            $cart->items()->delete();

            $orderId = $order->order_id;
        });

        // Redirect QR payment methods
        if (in_array($request->payment_method, ['aba', 'acleda'])) {

            return redirect()
                ->route('orders.show', $orderId)
                ->with('success', 'Order placed! Please scan the QR code below.')
                ->with('show_payment_qr', true)
                ->with('payment_method', $request->payment_method);
        }

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order placed successfully!');
    }

    public function index()
    {
        $orders = Order::with(['items.product', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with([
                'items.product',
                'items.size',
                'shipping',
                'payment'
            ])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('orders.show', compact('order'));
    }
}