<?php $__env->startSection('title', 'Cart'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .cart-layout { display: grid; grid-template-columns: 1fr 360px; gap: 2.5rem; padding: 3rem 0; align-items: start; }
    .cart-table { background: #fff; border-radius: 12px; border: 1px solid var(--border); overflow: hidden; }
    .cart-table table { width: 100%; border-collapse: collapse; }
    .cart-table thead th { background: var(--warm-white); padding: .9rem 1.25rem; text-align: left; font-size: .8rem; text-transform: uppercase; letter-spacing: .5px; font-weight: 600; color: var(--gray); border-bottom: 1px solid var(--border); }
    .cart-table tbody td { padding: 1.25rem; border-bottom: 1px solid #f5f5f5; vertical-align: middle; }
    .cart-table tbody tr:last-child td { border-bottom: none; }
    .cart-product { display: flex; align-items: center; gap: 1rem; }
    .cart-product-img { width: 72px; height: 72px; background: var(--warm-white); border-radius: 8px; overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: var(--gray); }
    .cart-product-img img { width: 100%; height: 100%; object-fit: cover; }
    .cart-product-name { font-weight: 600; font-size: .9rem; color: var(--black); }
    .cart-product-size { font-size: .8rem; color: var(--gray); margin-top: .2rem; }
    .qty-control { display: inline-flex; align-items: center; border: 1.5px solid var(--border); border-radius: 6px; overflow: hidden; }
    .qty-control button { width: 32px; height: 32px; background: #fff; border: none; cursor: pointer; font-size: .95rem; color: var(--charcoal); }
    .qty-control button:hover { background: var(--warm-white); }
    .qty-control input { width: 40px; text-align: center; border: none; font-size: .875rem; font-family: var(--font-body); }
    .cart-summary { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; position: sticky; top: 90px; }
    .cart-summary h3 { font-family: var(--font-display); font-size: 1.25rem; margin-bottom: 1.5rem; }
    .summary-row { display: flex; justify-content: space-between; align-items: center; padding: .6rem 0; font-size: .9rem; }
    .summary-row.total { border-top: 1px solid var(--border); margin-top: .5rem; padding-top: 1rem; font-weight: 700; font-size: 1.05rem; }
    @media(max-width:768px) { .cart-layout { grid-template-columns: 1fr; } .cart-summary { position: static; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-hero">
    <h1>Shopping Cart</h1>
    <div class="breadcrumb"><a href="<?php echo e(route('home')); ?>">Home</a> <span>/</span> <span>Cart</span></div>
</div>

<div class="container">
    <div style="padding:3rem 0;">
        <?php if(!$cart || $cart->items->isEmpty()): ?>
            <div style="text-align:center;padding:6rem 2rem;">
                <i class="fas fa-shopping-bag" style="font-size:4rem;color:var(--gold-light);margin-bottom:1.5rem;display:block;"></i>
                <h2 style="font-family:var(--font-display);margin-bottom:.75rem;">Your cart is empty</h2>
                <p style="color:var(--gray);margin-bottom:2rem;">Add some products to get started.</p>
                <a href="<?php echo e(route('shop')); ?>" class="btn btn-primary" style="padding:.9rem 2.5rem;">Start Shopping</a>
            </div>
        <?php else: ?>
        <div class="cart-layout">
            <div>
                <div class="cart-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <div class="cart-product">
                                        <div class="cart-product-img">
                                            <?php if($item->product && $item->product->product_image): ?>
                                                <img src="<?php echo e(asset('storage/'.$item->product->product_image)); ?>" alt="">
                                            <?php else: ?> <i class="fas fa-tshirt"></i> <?php endif; ?>
                                        </div>
                                        <div>
                                            <div class="cart-product-name"><?php echo e($item->product->product_name ?? 'Product'); ?></div>
                                            <div class="cart-product-size">Size: <?php echo e($item->size->size_name ?? 'N/A'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>$<?php echo e(number_format($item->price, 2)); ?></td>
                                <td>
                                    <form action="<?php echo e(route('cart.update', $item->cart_item_id)); ?>" method="POST" style="display:inline;">
                                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                        <div class="qty-control">
                                            <button type="submit" name="qty" value="<?php echo e(max(1, $item->qty - 1)); ?>">−</button>
                                            <input type="number" value="<?php echo e($item->qty); ?>" min="1" readonly>
                                            <button type="submit" name="qty" value="<?php echo e($item->qty + 1); ?>">+</button>
                                        </div>
                                    </form>
                                </td>
                                <td><strong>$<?php echo e(number_format($item->price * $item->qty, 2)); ?></strong></td>
                                <td>
                                    <form action="<?php echo e(route('cart.remove', $item->cart_item_id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="submit" style="background:none;border:none;color:#dc3545;cursor:pointer;font-size:1rem;padding:.25rem;" title="Remove"><i class="fas fa-times"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <div style="margin-top:1rem;">
                    <a href="<?php echo e(route('shop')); ?>" class="btn btn-outline"><i class="fas fa-arrow-left"></i> Continue Shopping</a>
                </div>
            </div>

            <!-- SUMMARY -->
            <div class="cart-summary">
                <h3>Order Summary</h3>
                <?php $subtotal = $cart->items->sum(fn($i) => $i->price * $i->qty); ?>
                <div class="summary-row"><span>Subtotal (<?php echo e($cart->items->sum('qty')); ?> items)</span><span>$<?php echo e(number_format($subtotal, 2)); ?></span></div>
                <div class="summary-row"><span>Shipping</span><span style="color:var(--gray);"><?php echo e($subtotal >= 100 ? 'FREE' : 'Calculated at checkout'); ?></span></div>
                <div class="summary-row total"><span>Total</span><span>$<?php echo e(number_format($subtotal, 2)); ?></span></div>
                <a href="<?php echo e(route('checkout')); ?>" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:1.5rem;padding:.85rem;">
                    Proceed to Checkout <i class="fas fa-arrow-right"></i>
                </a>
                <div style="display:flex;justify-content:center;gap:1rem;margin-top:1rem;">
                    <i class="fas fa-lock" style="color:var(--gray);font-size:.8rem;"></i>
                    <span style="font-size:.75rem;color:var(--gray);">Secure checkout</span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\vesot\Downloads\Telegram Desktop\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/cart/index.blade.php ENDPATH**/ ?>