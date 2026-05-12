<?php $__env->startSection('title', 'Order '.$order->order_number); ?>
<?php $__env->startSection('page-title', 'Order Details'); ?>
<?php $__env->startSection('breadcrumb', 'Admin / Orders / '.$order->order_number); ?>

<?php $__env->startSection('content'); ?>
<?php $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'staff'; ?>
<div class="row col-2" style="align-items:start;">
    <div style="display:flex;flex-direction:column;gap:1.5rem;">

        <!-- ORDER ITEMS -->
        <div class="card">
            <div class="card-header">
                <h3>Order Items</h3>
                <?php $colors = ['pending'=>'warning','processing'=>'info','shipped'=>'info','delivered'=>'success','cancelled'=>'danger']; ?>
                <span class="badge badge-<?php echo e($colors[$order->status] ?? 'secondary'); ?>"><?php echo e(ucfirst($order->status)); ?></span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Product</th><th>Size</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div style="display:flex;align-items:center;gap:.5rem;">
                                    <div style="width:36px;height:36px;background:var(--bg);border-radius:6px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--text-muted);font-size:.8rem;">
                                        <?php if($item->product && $item->product->product_image): ?>
                                            <img src="<?php echo e(asset('storage/'.$item->product->product_image)); ?>" style="width:100%;height:100%;object-fit:cover;">
                                        <?php else: ?> <i class="fas fa-tshirt"></i> <?php endif; ?>
                                    </div>
                                    <?php echo e($item->product->product_name ?? 'N/A'); ?>

                                </div>
                            </td>
                            <td><?php echo e($item->size->size_name ?? 'N/A'); ?></td>
                            <td><?php echo e($item->qty); ?></td>
                            <td>$<?php echo e(number_format($item->price, 2)); ?></td>
                            <td><strong>$<?php echo e(number_format($item->price * $item->qty, 2)); ?></strong></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr style="background:#f9fafb;">
                            <td colspan="4" style="text-align:right;font-weight:600;padding:.85rem 1rem;">Total</td>
                            <td style="font-weight:700;font-size:1.05rem;padding:.85rem 1rem;">$<?php echo e(number_format($order->total_price, 2)); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- UPDATE STATUS -->
        <div class="card">
            <div class="card-header"><h3>Update Status</h3></div>
            <div class="card-body">
                <form action="<?php echo e(route($routePrefix.'.orders.status', $order->order_id)); ?>" method="POST" style="display:flex;gap:.75rem;align-items:flex-end;">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <div style="flex:1;">
                        <label class="form-label">New Status</label>
                        <select name="status" class="form-control">
                            <?php $__currentLoopData = ['pending','processing','shipped','delivered','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($s); ?>" <?php echo e($order->status === $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                        <?php echo e(strtoupper(substr($order->user->username ?? 'U', 0, 1))); ?>

                    </div>
                    <div>
                        <div style="font-weight:600;"><?php echo e($order->user->username ?? 'N/A'); ?></div>
                        <div style="font-size:.8rem;color:var(--text-muted);"><?php echo e($order->user->email ?? ''); ?></div>
                    </div>
                </div>
                <div style="font-size:.85rem;color:var(--text-muted);">
                    Order placed on <?php echo e($order->created_at->format('F d, Y \a\t H:i')); ?>

                </div>
            </div>
        </div>

        <!-- SHIPPING -->
        <?php if($order->shipping): ?>
        <div class="card">
            <div class="card-header"><h3>Shipping Address</h3></div>
            <div class="card-body" style="font-size:.875rem;line-height:1.8;">
                <strong><?php echo e($order->shipping->full_name); ?></strong><br>
                <?php echo e($order->shipping->address); ?><br>
                <?php echo e($order->shipping->city); ?>, <?php echo e($order->shipping->province); ?><br>
                <?php echo e($order->shipping->postal_code); ?><br>
                <i class="fas fa-phone" style="color:var(--text-muted);margin-right:.3rem;"></i><?php echo e($order->shipping->phone_number); ?>

            </div>
        </div>
        <?php endif; ?>

        <!-- PAYMENT -->
        <?php if($order->payment): ?>
        <div class="card">
            <div class="card-header"><h3>Payment</h3></div>
            <div class="card-body" style="font-size:.875rem;">
                <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;">
                    <span>Method</span>
                    <strong><?php echo e(ucfirst(str_replace('_', ' ', $order->payment->payment_method))); ?></strong>
                </div>
                <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;">
                    <span>Amount</span>
                    <strong>$<?php echo e(number_format($order->payment->amount, 2)); ?></strong>
                </div>
                <?php if($order->payment->paid_at): ?>
                <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;">
                    <span>Paid At</span>
                    <span style="color:var(--text-muted);"><?php echo e($order->payment->paid_at->format('M d, Y H:i')); ?></span>
                </div>
                <?php endif; ?>
                <div style="display:flex;justify-content:space-between;align-items:center;padding-top:.75rem;border-top:1px solid var(--border);margin-top:.5rem;">
                    <span>Status</span>
                    <span class="badge badge-<?php echo e($order->payment->status === 'paid' ? 'success' : ($order->payment->status === 'failed' ? 'danger' : ($order->payment->status === 'refunded' ? 'secondary' : 'warning'))); ?>">
                        <?php echo e(ucfirst($order->payment->status)); ?>

                    </span>
                </div>
                <form action="<?php echo e(route($routePrefix.'.orders.payment-status', $order->order_id)); ?>" method="POST" style="display:flex;gap:.6rem;align-items:flex-end;margin-top:1rem;">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <div style="flex:1;">
                        <label style="font-size:.8rem;font-weight:500;display:block;margin-bottom:.3rem;">Update Payment Status</label>
                        <select name="payment_status" class="form-control">
                            <?php $__currentLoopData = ['pending','paid','failed','refunded']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ps): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($ps); ?>" <?php echo e($order->payment->status === $ps ? 'selected' : ''); ?>><?php echo e(ucfirst($ps)); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <button class="btn btn-accent btn-sm" type="submit"><i class="fas fa-save"></i> Save</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\vesot\Downloads\Telegram Desktop\ecommerce-laravel-v6-fixed (2)\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/admin/orders/show.blade.php ENDPATH**/ ?>