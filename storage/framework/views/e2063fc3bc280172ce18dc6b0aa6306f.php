<?php $__env->startSection('title', 'Products'); ?>
<?php $__env->startSection('page-title', 'Products'); ?>
<?php $__env->startSection('breadcrumb', 'Admin / Products'); ?>

<?php $__env->startSection('content'); ?>
<?php $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'staff'; ?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <div>
        <form method="GET" style="display:flex;gap:.75rem;">
            <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo e(request('search')); ?>" style="width:240px;">
            <select name="category" class="form-control" style="width:160px;">
                <option value="">All Categories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat->category_id); ?>" <?php echo e(request('category') == $cat->category_id ? 'selected' : ''); ?>><?php echo e($cat->category_name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="active" class="form-control" style="width:130px;">
                <option value="">All Status</option>
                <option value="1" <?php echo e(request('active') === '1' ? 'selected' : ''); ?>>Active</option>
                <option value="0" <?php echo e(request('active') === '0' ? 'selected' : ''); ?>>Inactive</option>
            </select>
            <button class="btn btn-outline" type="submit"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <a href="<?php echo e(route($routePrefix.'.products.create')); ?>" class="btn btn-accent"><i class="fas fa-plus"></i> Add Product</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Sizes / Price From</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <div style="width:44px;height:44px;background:var(--bg);border-radius:8px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                                <?php if($product->product_image): ?>
                                    <img src="<?php echo e(asset('storage/'.$product->product_image)); ?>" style="width:100%;height:100%;object-fit:cover;">
                                <?php else: ?>
                                    <i class="fas fa-tshirt"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <div style="font-weight:500;font-size:.875rem;"><?php echo e($product->product_name); ?></div>
                                <?php if($product->is_featured): ?><span class="badge badge-info" style="margin-top:2px;">Featured</span><?php endif; ?>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-secondary"><?php echo e($product->category->category_name ?? 'N/A'); ?></span></td>
                    <td>
                        <?php echo e($product->sizes->count()); ?> sizes
                        <?php if($product->sizes->isNotEmpty()): ?>
                            <br><small style="color:var(--text-muted);">from $<?php echo e(number_format($product->sizes->min('pivot.price'), 2)); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php $total = $product->sizes->sum('pivot.stock_qty'); ?>
                        <span class="badge <?php echo e($total < 5 ? 'badge-danger' : ($total < 20 ? 'badge-warning' : 'badge-success')); ?>"><?php echo e($total); ?></span>
                    </td>
                    <td><span class="badge <?php echo e($product->is_active ? 'badge-success' : 'badge-secondary'); ?>"><?php echo e($product->is_active ? 'Active' : 'Inactive'); ?></span></td>
                    <td style="color:var(--text-muted);font-size:.8rem;"><?php echo e($product->created_at->format('M d, Y')); ?></td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            <a href="<?php echo e(route($routePrefix.'.products.edit', $product->product_id)); ?>" class="btn btn-outline btn-xs"><i class="fas fa-edit"></i></a>
                            <form action="<?php echo e(route($routePrefix.'.products.destroy', $product->product_id)); ?>" method="POST" onsubmit="return confirm('Delete this product?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-danger btn-xs" type="submit"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h3>No products found</h3>
                            <p>Add your first product to get started.</p>
                            <a href="<?php echo e(route($routePrefix.'.products.create')); ?>" class="btn btn-accent" style="margin-top:1rem;">Add Product</a>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:1rem;"><?php echo e($products->links()); ?></div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\vesot\Downloads\Telegram Desktop\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/admin/products/index.blade.php ENDPATH**/ ?>