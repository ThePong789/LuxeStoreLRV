<?php $__env->startSection('title', 'Checkout'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .checkout-layout { display: grid; grid-template-columns: 1fr 380px; gap: 2.5rem; padding: 3rem 0; align-items: start; }
    .checkout-section { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; margin-bottom: 1.5rem; }
    .checkout-section h3 { font-family: var(--font-display); font-size: 1.1rem; margin-bottom: 1.25rem; padding-bottom: .75rem; border-bottom: 1px solid var(--border); }
    .address-card { border: 1.5px solid var(--border); border-radius: 8px; padding: 1rem 1.25rem; cursor: pointer; margin-bottom: .75rem; transition: all .2s; display: flex; align-items: flex-start; gap: .75rem; }
    .address-card:hover { border-color: var(--gold); }
    .address-card.selected { border-color: var(--black); background: var(--warm-white); }
    .address-card input[type=radio] { margin-top: 3px; accent-color: var(--black); }
    .address-detail { font-size: .875rem; line-height: 1.6; color: var(--charcoal); }
    .address-detail strong { font-size: .9rem; }
    .payment-option { border: 1.5px solid var(--border); border-radius: 8px; padding: 1rem 1.25rem; cursor: pointer; margin-bottom: .75rem; transition: all .2s; display: flex; align-items: center; gap: .75rem; }
    .payment-option:hover { border-color: var(--gold); }
    .payment-option.selected { border-color: var(--black); background: var(--warm-white); }
    .payment-option input { accent-color: var(--black); }
    .payment-option label { cursor: pointer; display: flex; align-items: center; gap: .75rem; flex: 1; font-size: .9rem; font-weight: 500; }
    .payment-option i { font-size: 1.1rem; color: var(--gold); width: 24px; text-align: center; }
    .order-summary-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 1.75rem; position: sticky; top: 90px; }
    .order-item { display: flex; align-items: center; gap: .75rem; padding: .75rem 0; border-bottom: 1px solid #f5f5f5; }
    .order-item:last-of-type { border-bottom: none; }
    .order-item-img { width: 52px; height: 52px; background: var(--warm-white); border-radius: 6px; overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: var(--gray); font-size: .8rem; }
    .order-item-img img { width: 100%; height: 100%; object-fit: cover; }
    .new-address-form { background: var(--warm-white); border-radius: 8px; padding: 1.25rem; margin-top: 1rem; display: none; }
    .new-address-form.show { display: block; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
    .form-field label { display: block; font-size: .8rem; font-weight: 500; margin-bottom: .35rem; }
    .form-field input, .form-field select { width: 100%; padding: .55rem .85rem; border: 1.5px solid var(--border); border-radius: 6px; font-size: .875rem; font-family: var(--font-body); background: #fff; }
    .form-field input:focus, .form-field select:focus { outline: none; border-color: var(--gold); }
    @media(max-width:768px) { .checkout-layout { grid-template-columns: 1fr; } .order-summary-card { position: static; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-hero">
    <h1>Checkout</h1>
    <div class="breadcrumb"><a href="<?php echo e(route('home')); ?>">Home</a> <span>/</span> <a href="<?php echo e(route('cart')); ?>">Cart</a> <span>/</span> <span>Checkout</span></div>
</div>

<div class="container">
    <div class="checkout-layout">
        <div>
            <form action="<?php echo e(route('checkout.place')); ?>" method="POST" id="checkout-form">
                <?php echo csrf_field(); ?>

                <!-- SHIPPING ADDRESS -->
                <div class="checkout-section">
                    <h3><i class="fas fa-map-marker-alt" style="color:var(--gold);margin-right:.5rem;"></i> Shipping Address</h3>

                    <?php if($addresses->isNotEmpty()): ?>
                        <?php $__currentLoopData = $addresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $addr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <label class="address-card <?php echo e($loop->first ? 'selected' : ''); ?>" onclick="selectAddress(this)">
                            <input type="radio" name="shipping_id" value="<?php echo e($addr->shipping_id); ?>" <?php echo e($loop->first ? 'checked' : ''); ?>>
                            <div class="address-detail">
                                <strong><?php echo e($addr->full_name ?? auth()->user()->username); ?></strong>
                                <br><?php echo e($addr->address); ?>, <?php echo e($addr->city); ?>, <?php echo e($addr->province); ?>

                                <?php if($addr->postal_code): ?>, <?php echo e($addr->postal_code); ?><?php endif; ?>
                                <br><i class="fas fa-phone fa-xs" style="color:var(--gray);"></i> <?php echo e($addr->phone_number); ?>

                            </div>
                        </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <button type="button" class="btn btn-outline btn-sm" style="margin-top:.5rem;" onclick="toggleNewAddress()">
                            <i class="fas fa-plus"></i> Use Different Address
                        </button>
                    <?php endif; ?>

                    <div class="new-address-form <?php echo e($addresses->isEmpty() ? 'show' : ''); ?>" id="new-address-form">
                        <?php if($addresses->isNotEmpty()): ?><p style="font-size:.85rem;margin-bottom:1rem;font-weight:600;">New Address</p><?php endif; ?>
                        <div class="form-row" style="margin-bottom:.75rem;">
                            <div class="form-field"><label>Full Name *</label><input type="text" name="new_address[full_name]" class="new-addr-field"></div>
                            <div class="form-field"><label>Phone Number *</label><input type="text" name="new_address[phone_number]" class="new-addr-field"></div>
                        </div>
                        <div class="form-field" style="margin-bottom:.75rem;"><label>Address *</label><input type="text" name="new_address[address]" class="new-addr-field"></div>
                        <div class="form-row" style="margin-bottom:.75rem;">
                            <div class="form-field"><label>City</label><input type="text" name="new_address[city]"></div>
                            <div class="form-field"><label>Province *</label><input type="text" name="new_address[province]" class="new-addr-field"></div>
                        </div>
                        <div class="form-field"><label>Postal Code</label><input type="text" name="new_address[postal_code]"></div>
                    </div>
                </div>

                <!-- PAYMENT METHOD -->
                <div class="checkout-section">
                    <h3><i class="fas fa-credit-card" style="color:var(--gold);margin-right:.5rem;"></i> Payment Method</h3>
                    <div class="payment-option selected" onclick="selectPayment(this, 'cod')">
                        <input type="radio" name="payment_method" value="cod" id="pay-cod" checked>
                        <label for="pay-cod"><i class="fas fa-money-bill-wave"></i> Cash on Delivery</label>
                    </div>
                    <div class="payment-option" onclick="selectPayment(this, 'bank_transfer')">
                        <input type="radio" name="payment_method" value="bank_transfer" id="pay-bank">
                        <label for="pay-bank"><i class="fas fa-university"></i> Bank Transfer</label>
                    </div>
                    <div class="payment-option" onclick="selectPayment(this, 'credit_card')">
                        <input type="radio" name="payment_method" value="credit_card" id="pay-card">
                        <label for="pay-card"><i class="fas fa-credit-card"></i> Credit / Debit Card</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:1rem;font-size:1rem;">
                    <i class="fas fa-lock"></i> Place Order
                </button>
            </form>
        </div>

        <!-- ORDER SUMMARY -->
        <div class="order-summary-card">
            <h3 style="font-family:var(--font-display);font-size:1.15rem;margin-bottom:1.25rem;">Order Summary</h3>
            <?php $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="order-item">
                <div class="order-item-img">
                    <?php if($item->product && $item->product->product_image): ?>
                        <img src="<?php echo e(asset('storage/'.$item->product->product_image)); ?>" alt="">
                    <?php else: ?> <i class="fas fa-tshirt"></i> <?php endif; ?>
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:.85rem;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?php echo e($item->product->product_name ?? ''); ?></div>
                    <div style="font-size:.75rem;color:var(--gray);">Size: <?php echo e($item->size->size_name ?? ''); ?> · Qty: <?php echo e($item->qty); ?></div>
                </div>
                <div style="font-size:.875rem;font-weight:600;white-space:nowrap;">$<?php echo e(number_format($item->price * $item->qty, 2)); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php $subtotal = $cart->items->sum(fn($i) => $i->price * $i->qty); ?>
            <div style="border-top:1px solid var(--border);margin-top:.75rem;padding-top:1rem;">
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.35rem 0;"><span>Subtotal</span><span>$<?php echo e(number_format($subtotal, 2)); ?></span></div>
                <div style="display:flex;justify-content:space-between;font-size:.875rem;padding:.35rem 0;"><span>Shipping</span><span style="color:<?php echo e($subtotal >= 100 ? 'green' : 'var(--gray)'); ?>;"><?php echo e($subtotal >= 100 ? 'FREE' : 'TBD'); ?></span></div>
                <div style="display:flex;justify-content:space-between;font-weight:700;font-size:1.05rem;padding:.75rem 0 0;border-top:1px solid var(--border);margin-top:.5rem;"><span>Total</span><span>$<?php echo e(number_format($subtotal, 2)); ?></span></div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function setNewAddressRequired(required) {
    document.querySelectorAll('.new-addr-field').forEach(el => {
        if (required) el.setAttribute('required', '');
        else el.removeAttribute('required');
    });
}

// On page load: if addresses exist, new form is hidden → not required
<?php if($addresses->isNotEmpty()): ?>
setNewAddressRequired(false);
<?php else: ?>
setNewAddressRequired(true);
<?php endif; ?>

function selectAddress(el) {
    document.querySelectorAll('.address-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input').checked = true;
    // Hide new address form and remove required
    const form = document.getElementById('new-address-form');
    form.classList.remove('show');
    setNewAddressRequired(false);
}
function selectPayment(el, method) {
    document.querySelectorAll('.payment-option').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    el.querySelector('input').checked = true;
}
function toggleNewAddress() {
    const form = document.getElementById('new-address-form');
    form.classList.toggle('show');
    if (form.classList.contains('show')) {
        document.querySelectorAll('.address-card').forEach(c => { c.classList.remove('selected'); c.querySelector('input').checked = false; });
        setNewAddressRequired(true);
    } else {
        setNewAddressRequired(false);
        // Re-select first address
        const first = document.querySelector('.address-card');
        if (first) { first.classList.add('selected'); first.querySelector('input').checked = true; }
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lucksing\Downloads\ecommerce-laravel-v6-fixed (2)\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/checkout/index.blade.php ENDPATH**/ ?>