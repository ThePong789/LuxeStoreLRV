<?php $__env->startSection('title', 'Orders'); ?>
<?php $__env->startSection('page-title', 'Orders'); ?>
<?php $__env->startSection('breadcrumb', 'Admin / Orders'); ?>

<?php $__env->startSection('content'); ?>
<?php $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'staff'; ?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <form method="GET" style="display:flex;gap:.75rem;">
        <input type="text" name="search" class="form-control" placeholder="Order number..." value="<?php echo e(request('search')); ?>" style="width:200px;">
        <select name="status" class="form-control" style="width:150px;">
            <option value="">All Status</option>
            <?php $__currentLoopData = ['pending','processing','shipped','delivered','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($s); ?>" <?php echo e(request('status') == $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="btn btn-outline" type="submit"><i class="fas fa-search"></i></button>
    </form>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order No.</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $colors = ['pending'=>'warning','processing'=>'info','shipped'=>'info','delivered'=>'success','cancelled'=>'danger']; ?>
                <tr>
                    <td><strong><?php echo e($order->order_id); ?></strong></td>
                    <td><?php echo e($order->user->username ?? 'N/A'); ?></td>
                    <td><strong>$<?php echo e(number_format($order->total_price, 2)); ?></strong></td>
                    <td>
                        <?php if($order->payment): ?>
                            <span class="badge badge-<?php echo e($order->payment->status === 'paid' ? 'success' : 'warning'); ?>"><?php echo e(ucfirst($order->payment->status)); ?></span>
                            <div style="font-size:.75rem;color:var(--text-muted);"><?php echo e($order->payment->payment_method); ?></div>
                        <?php else: ?> <span class="badge badge-secondary">N/A</span> <?php endif; ?>
                    </td>
                    <td><span class="badge badge-<?php echo e($colors[$order->status] ?? 'secondary'); ?>"><?php echo e(ucfirst($order->status)); ?></span></td>
                    <td style="color:var(--text-muted);font-size:.8rem;"><?php echo e($order->created_at->format('M d, Y')); ?></td>
                    <td>
                        <a href="<?php echo e(route($routePrefix.'.orders.show', $order->order_id)); ?>" class="btn btn-outline btn-xs"><i class="fas fa-eye"></i> View</a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-shopping-cart"></i><h3>No orders found</h3></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;"><?php echo e($orders->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lucksing\Downloads\ecommerce-laravel-v6-fixed (2)\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>