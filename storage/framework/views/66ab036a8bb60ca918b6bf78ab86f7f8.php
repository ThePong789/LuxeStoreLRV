<?php $__env->startSection('title', 'Staff Dashboard'); ?>
<?php $__env->startSection('page-title', 'Staff Dashboard'); ?>
<?php $__env->startSection('breadcrumb', 'Overview'); ?>

<?php $__env->startSection('content'); ?>
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-val"><?php echo e($stats['pending_orders']); ?></div>
            <div class="stat-label">Pending Orders</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-cogs"></i></div>
        <div>
            <div class="stat-val"><?php echo e($stats['processing_orders']); ?></div>
            <div class="stat-label">Processing</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-truck"></i></div>
        <div>
            <div class="stat-val"><?php echo e($stats['shipped_orders']); ?></div>
            <div class="stat-label">Shipped</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="stat-val"><?php echo e($stats['low_stock']); ?></div>
            <div class="stat-label">Low Stock Items</div>
        </div>
    </div>
</div>

<div class="row col-2">
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-shopping-cart" style="color:var(--accent);margin-right:.5rem;"></i> Recent Orders</h3>
            <a href="<?php echo e(route('staff.orders.index')); ?>" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Status</th><th></th></tr></thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php $colors = ['pending'=>'warning','processing'=>'info','shipped'=>'info','delivered'=>'success','cancelled'=>'danger']; ?>
                    <tr>
                        <td><strong>#<?php echo e($order->order_id); ?></strong></td>
                        <td><?php echo e($order->user->username ?? 'N/A'); ?></td>
                        <td>$<?php echo e(number_format($order->total_price, 2)); ?></td>
                        <td><span class="badge badge-<?php echo e($colors[$order->status] ?? 'secondary'); ?>"><?php echo e(ucfirst($order->status)); ?></span></td>
                        <td><a href="<?php echo e(route('staff.orders.show', $order->order_id)); ?>" class="btn btn-outline btn-xs">View</a></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" style="text-align:center;padding:2rem;color:var(--text-muted);">No orders yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-star" style="color:var(--accent);margin-right:.5rem;"></i> Pending Reviews</h3>
            <a href="<?php echo e(route('staff.reviews.index')); ?>" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding:0;">
            <?php $__empty_1 = true; $__currentLoopData = $pendingReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="display:flex;align-items:center;justify-content:space-between;padding:.85rem 1.25rem;border-bottom:1px solid var(--border);">
                <div>
                    <div style="font-size:.875rem;font-weight:500;"><?php echo e($r->review_title); ?></div>
                    <div style="font-size:.75rem;color:var(--text-muted);"><?php echo e($r->user->username ?? 'N/A'); ?> · <?php echo e($r->product->product_name ?? 'General'); ?></div>
                    <div style="color:#f59e0b;font-size:.75rem;margin-top:2px;">
                        <?php for($i=1;$i<=5;$i++): ?><i class="fas fa-star" style="<?php echo e($i > $r->rating ? 'color:#e5e7eb' : ''); ?>"></i><?php endfor; ?>
                    </div>
                </div>
                <form action="<?php echo e(route('staff.reviews.approve', $r->review_id)); ?>" method="POST">
                    <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                    <button class="btn btn-success btn-xs" type="submit"><i class="fas fa-check"></i> Approve</button>
                </form>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="padding:2rem;text-align:center;color:var(--text-muted);font-size:.875rem;">No pending reviews.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lucksing\Downloads\ecommerce-laravel-v6-fixed (2)\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/staff/dashboard.blade.php ENDPATH**/ ?>