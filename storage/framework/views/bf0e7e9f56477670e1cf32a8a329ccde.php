<?php $__env->startSection('title', 'Categories'); ?>
<?php $__env->startSection('page-title', 'Categories'); ?>
<?php $__env->startSection('breadcrumb', 'Admin / Categories'); ?>

<?php $__env->startSection('content'); ?>
    <?php $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'staff'; ?>
    <div class="row col-2" style="align-items:start;">
        <!-- ADD CATEGORY -->
        <div class="card">
            <div class="card-header">
                <h3>Add Category</h3>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route($routePrefix . '.categories.store')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label class="form-label">Category Name *</label>
                        <input type="text" name="category_name" class="form-control" value="<?php echo e(old('category_name')); ?>"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"><?php echo e(old('description')); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Image</label>
                        <input type="file" name="category_image" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-accent" style="width:100%;justify-content:center;"><i
                            class="fas fa-plus"></i> Add Category</button>
                </form>
            </div>
        </div>

        <!-- CATEGORY LIST -->
        <div class="card">
            <div class="card-header">
                <h3>All Categories</h3>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:.75rem;">
                                        <div
                                            style="width:40px;height:40px;background:var(--bg);border-radius:8px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                                            <?php if($cat->category_image): ?>
                                                <img src="<?php echo e(asset('storage/' . $cat->category_image)); ?>"
                                                    style="width:100%;height:100%;object-fit:cover;">
                                            <?php else: ?>
                                                <i class="fas fa-tag"></i>
                                            <?php endif; ?>
                                        </div>
                                        <div>
                                            <div style="font-weight:500;font-size:.875rem;"><?php echo e($cat->category_name); ?></div>
                                            <div style="font-size:.75rem;color:var(--text-muted);">
                                                <?php echo e(Str::limit($cat->description, 40)); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info"><?php echo e($cat->products_count); ?></span></td>
                                <td>
                                    <div style="display:flex;gap:.4rem;">
                                        <button class="btn btn-outline btn-xs"
                                            onclick="editCat(<?php echo e($cat->category_id); ?>, '<?php echo e(addslashes($cat->category_name)); ?>', '<?php echo e(addslashes($cat->description)); ?>', '<?php echo e($cat->category_image); ?>')"><i
                                                class="fas fa-edit"></i></button>
                                        <form action="<?php echo e(route($routePrefix . '.categories.destroy', $cat->category_id)); ?>"
                                            method="POST" onsubmit="return confirm('Delete category?')">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                            <button class="btn btn-danger btn-xs" type="submit"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state" style="padding:2rem;"><i class="fas fa-tags"></i>
                                        <h3>No categories yet</h3>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div style="padding:1rem;"><?php echo e($categories->links()); ?></div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="edit-cat-modal"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:12px;padding:2rem;width:400px;max-width:95vw;">
            <div style="display:flex;justify-content:space-between;margin-bottom:1.5rem;">
                <h3>Edit Category</h3>
                <button onclick="document.getElementById('edit-cat-modal').style.display='none'"
                    style="background:none;border:none;font-size:1.2rem;cursor:pointer;">&times;</button>
            </div>
            <form id="edit-cat-form" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                <div class="form-group">
                    <label class="form-label">Category Name *</label>
                    <input type="text" name="category_name" id="edit-cat-name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="edit-cat-desc" class="form-control" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Image</label>
                    <div id="edit-cat-current-img" style="margin-bottom:.5rem;"></div>
                    <input type="file" name="category_image" class="form-control" accept="image/*">
                    <small style="color:var(--text-muted);font-size:.75rem;">Leave empty to keep current image</small>
                </div>
                <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                </div>
        </div>
    <?php $__env->stopSection(); ?>
    <?php $__env->startPush('scripts'); ?>
        <script>
            function editCat(id, name, desc, image) {
                document.getElementById('edit-cat-form').action = '/<?php echo e($routePrefix); ?>/categories/' + id;
                document.getElementById('edit-cat-name').value = name;
                document.getElementById('edit-cat-desc').value = desc;
                const imgDiv = document.getElementById('edit-cat-current-img');
                if (image) {
                    imgDiv.innerHTML = '<img src="/storage/' + image +
                        '" style="width:55px;height:55px;object-fit:cover;border-radius:8px;border:1px solid #e5e7eb;"><small style="display:block;margin-top:.3rem;color:#888;font-size:.72rem;">Current image</small>';
                } else {
                    imgDiv.innerHTML = '<small style="color:#888;">No current image</small>';
                }
                document.getElementById('edit-cat-modal').style.display = 'flex';
            }
        </script>
    <?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\vesot\Downloads\Telegram Desktop\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/admin/categories/index.blade.php ENDPATH**/ ?>