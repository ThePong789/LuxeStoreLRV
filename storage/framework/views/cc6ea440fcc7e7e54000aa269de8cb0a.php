<?php $__env->startSection('title', 'Reviews'); ?>
<?php $__env->startSection('page-title', 'Reviews'); ?>
<?php $__env->startSection('breadcrumb', 'Admin / Reviews'); ?>

<?php $__env->startSection('content'); ?>
<?php $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'staff'; ?>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Review</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="font-weight:500;font-size:.875rem;"><?php echo e($review->review_title); ?></div>
                        <div style="font-size:.75rem;color:var(--text-muted);margin-top:2px;"><?php echo e(Str::limit($review->description, 60)); ?></div>
                    </td>
                    <td style="font-size:.875rem;"><?php echo e($review->user->username ?? 'N/A'); ?></td>
                    <td style="font-size:.875rem;"><?php echo e($review->product->product_name ?? 'General'); ?></td>
                    <td>
                        <div style="color:#f59e0b;">
                            <?php for($i=1;$i<=5;$i++): ?><i class="fas fa-star" style="<?php echo e($i > $review->rating ? 'color:#e5e7eb' : ''); ?>"></i><?php endfor; ?>
                        </div>
                        <div style="font-size:.75rem;color:var(--text-muted);"><?php echo e($review->rating); ?>/5</div>
                    </td>
                    <td>
                        <span class="badge <?php echo e($review->is_approved ? 'badge-success' : 'badge-warning'); ?>">
                            <?php echo e($review->is_approved ? 'Approved' : 'Pending'); ?>

                        </span>
                    </td>
                    <td style="color:var(--text-muted);font-size:.8rem;"><?php echo e($review->created_at->format('M d, Y')); ?></td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            <?php if(!$review->is_approved): ?>
                            <form action="<?php echo e(route($routePrefix.'.reviews.approve', $review->review_id)); ?>" method="POST">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-success btn-xs" type="submit"><i class="fas fa-check"></i></button>
                            </form>
                            <?php endif; ?>
                            <form action="<?php echo e(route($routePrefix.'.reviews.destroy', $review->review_id)); ?>" method="POST" onsubmit="return confirm('Delete review?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-danger btn-xs" type="submit"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="7"><div class="empty-state"><i class="fas fa-star"></i><h3>No reviews yet</h3></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;"><?php echo e($reviews->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\vesot\Downloads\Telegram Desktop\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/admin/reviews/index.blade.php ENDPATH**/ ?>