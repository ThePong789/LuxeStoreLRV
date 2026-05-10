<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>
<?php $__env->startSection('breadcrumb', 'Overview of your store'); ?>

<?php $__env->startSection('content'); ?>

<!-- STATS -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-val"><?php echo e(number_format($stats['total_users'])); ?></div>
            <div class="stat-label">Total Customers</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold"><i class="fas fa-box"></i></div>
        <div>
            <div class="stat-val"><?php echo e(number_format($stats['total_products'])); ?></div>
            <div class="stat-label">Products</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-shopping-cart"></i></div>
        <div>
            <div class="stat-val"><?php echo e(number_format($stats['total_orders'])); ?></div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-dollar-sign"></i></div>
        <div>
            <div class="stat-val">$<?php echo e(number_format($stats['total_revenue'], 0)); ?></div>
            <div class="stat-label">Total Revenue</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-clock"></i></div>
        <div>
            <div class="stat-val"><?php echo e(number_format($stats['pending_orders'])); ?></div>
            <div class="stat-label">Pending Orders</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fas fa-exclamation-triangle"></i></div>
        <div>
            <div class="stat-val"><?php echo e(number_format($stats['low_stock'])); ?></div>
            <div class="stat-label">Low Stock Items</div>
        </div>
    </div>
</div>

<div class="row col-2" style="margin-bottom:1.5rem;">
    <!-- RECENT ORDERS -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-shopping-cart" style="color:var(--accent);margin-right:.5rem;"></i> Recent Orders</h3>
            <a href="<?php echo e(route('admin.orders.index')); ?>" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order No.</th>
                        <th>Customer</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentOrders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><strong><?php echo e($order->order_id); ?></strong></td>
                        <td><?php echo e($order->user->username ?? 'N/A'); ?></td>
                        <td>$<?php echo e(number_format($order->total_price, 2)); ?></td>
                        <td>
                            <?php $colors = ['pending'=>'warning','processing'=>'info','shipped'=>'info','delivered'=>'success','cancelled'=>'danger']; ?>
                            <span class="badge badge-<?php echo e($colors[$order->status] ?? 'secondary'); ?>"><?php echo e(ucfirst($order->status)); ?></span>
                        </td>
                        <td><?php echo e($order->created_at->format('M d')); ?></td>
                        <td><a href="<?php echo e(route('admin.orders.show', $order->order_id)); ?>" class="btn btn-outline btn-xs">View</a></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="empty-state" style="padding:2rem;text-align:center;color:var(--text-muted)">No orders yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TOP PRODUCTS & PENDING REVIEWS -->
    <div style="display:flex;flex-direction:column;gap:1.5rem;">
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-fire" style="color:var(--accent);margin-right:.5rem;"></i> Top Products</h3>
            </div>
            <div class="card-body" style="padding:0;">
                <?php $__empty_1 = true; $__currentLoopData = $topProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div style="display:flex;align-items:center;gap:.75rem;padding:.85rem 1.25rem;border-bottom:1px solid var(--border);">
                    <div style="width:28px;height:28px;background:var(--accent-light);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:var(--accent);"><?php echo e($i+1); ?></div>
                    <div style="flex:1;font-size:.875rem;font-weight:500;"><?php echo e($p->product_name); ?></div>
                    <div style="font-size:.8rem;color:var(--text-muted);"><?php echo e($p->total_sold); ?> sold</div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="padding:2rem;text-align:center;color:var(--text-muted);font-size:.875rem;">No data yet.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-star" style="color:var(--accent);margin-right:.5rem;"></i> Pending Reviews</h3>
                <a href="<?php echo e(route('admin.reviews.index')); ?>" class="btn btn-outline btn-sm">Manage</a>
            </div>
            <div class="card-body" style="padding:0;">
                <?php $__empty_1 = true; $__currentLoopData = $pendingReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:.85rem 1.25rem;border-bottom:1px solid var(--border);">
                    <div>
                        <div style="font-size:.875rem;font-weight:500;"><?php echo e($r->review_title); ?></div>
                        <div style="font-size:.75rem;color:var(--text-muted);">by <?php echo e($r->user->username ?? 'N/A'); ?></div>
                    </div>
                    <form action="<?php echo e(route('admin.reviews.approve', $r->review_id)); ?>" method="POST">
                        <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                        <button class="btn btn-success btn-xs" type="submit">Approve</button>
                    </form>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div style="padding:2rem;text-align:center;color:var(--text-muted);font-size:.875rem;">No pending reviews.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\vesot\Downloads\Telegram Desktop\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>