<?php $__env->startSection('title', 'Edit Product'); ?>
<?php $__env->startSection('page-title', 'Edit Product'); ?>
<?php $__env->startSection('breadcrumb', 'Admin / Products / Edit'); ?>

<?php $__env->startSection('content'); ?>
<?php $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'staff'; ?>
<form action="<?php echo e(route($routePrefix.'.products.update', $product->product_id)); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="row col-2" style="align-items:start;">
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            <div class="card">
                <div class="card-header"><h3>Product Information</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Product Name *</label>
                        <input type="text" name="product_name" class="form-control" value="<?php echo e(old('product_name', $product->product_name)); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-control" required>
                            <option value="">Select category</option>
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat->category_id); ?>" <?php echo e(old('category_id', $product->category_id) == $cat->category_id ? 'selected' : ''); ?>><?php echo e($cat->category_name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="5"><?php echo e(old('description', $product->description)); ?></textarea>
                    </div>
                    <div style="display:flex;gap:1rem;">
                        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                            <input type="checkbox" name="is_featured" value="1" <?php echo e($product->is_featured ? 'checked' : ''); ?>> Featured
                        </label>
                        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                            <input type="checkbox" name="is_active" value="1" <?php echo e($product->is_active ? 'checked' : ''); ?>> Active
                        </label>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Sizes & Pricing</h3>
                    <button type="button" class="btn btn-outline btn-sm" onclick="addSize()"><i class="fas fa-plus"></i> Add Size</button>
                </div>
                <div class="card-body">
                    <div id="sizes-container">
                        <?php $__currentLoopData = $product->sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $size): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="size-row" style="display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:.75rem;align-items:end;margin-bottom:.75rem;">
                            <div>
                                <label class="form-label">Size *</label>
                                <select name="sizes[<?php echo e($i); ?>][size_id]" class="form-control" required>
                                    <?php $__currentLoopData = $sizes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($s->size_id); ?>" <?php echo e($s->size_id == $size->size_id ? 'selected' : ''); ?>><?php echo e($s->size_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Price ($) *</label>
                                <input type="number" name="sizes[<?php echo e($i); ?>][price]" class="form-control" step="0.01" min="0" value="<?php echo e($size->pivot->price); ?>" required>
                            </div>
                            <div>
                                <label class="form-label">Stock *</label>
                                <input type="number" name="sizes[<?php echo e($i); ?>][stock]" class="form-control" min="0" value="<?php echo e($size->pivot->stock_qty); ?>" required>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.size-row').remove()"><i class="fas fa-times"></i></button>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            <div class="card">
                <div class="card-header"><h3>Product Image</h3></div>
                <div class="card-body">
                    <div id="img-preview" style="width:100%;height:220px;background:var(--bg);border-radius:8px;border:2px dashed var(--border);overflow:hidden;cursor:pointer;margin-bottom:1rem;" onclick="document.getElementById('imgInput').click()">
                        <?php if($product->product_image): ?>
                            <img src="<?php echo e(asset('storage/'.$product->product_image)); ?>" style="width:100%;height:100%;object-fit:cover;">
                        <?php else: ?>
                            <div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--text-muted);text-align:center;">
                                <div><i class="fas fa-cloud-upload-alt" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>Click to upload</div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="file" id="imgInput" name="product_image" accept="image/*" style="display:none;" onchange="previewImg(this)">
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <button type="submit" class="btn btn-accent" style="width:100%;justify-content:center;padding:.75rem;">
                        <i class="fas fa-save"></i> Update Product
                    </button>
                    <a href="<?php echo e(route($routePrefix.'.products.index')); ?>" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:.5rem;">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
let sizeIdx = <?php echo e($product->sizes->count()); ?>;
const sizes = <?php echo json_encode($sizes, 15, 512) ?>;
function addSize() {
    const container = document.getElementById('sizes-container');
    const opts = sizes.map(s => `<option value="${s.size_id}">${s.size_name}</option>`).join('');
    const row = document.createElement('div');
    row.className = 'size-row';
    row.style.cssText = 'display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:.75rem;align-items:end;margin-bottom:.75rem;';
    row.innerHTML = `
        <div><label class="form-label">Size *</label><select name="sizes[${sizeIdx}][size_id]" class="form-control" required><option value="">Select</option>${opts}</select></div>
        <div><label class="form-label">Price ($) *</label><input type="number" name="sizes[${sizeIdx}][price]" class="form-control" step="0.01" min="0" required></div>
        <div><label class="form-label">Stock *</label><input type="number" name="sizes[${sizeIdx}][stock]" class="form-control" min="0" required></div>
        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.size-row').remove()"><i class="fas fa-times"></i></button>`;
    container.appendChild(row);
    sizeIdx++;
}
function previewImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => { document.getElementById('img-preview').innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`; };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lucksing\Downloads\ecommerce-laravel-v6-fixed (2)\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/admin/products/edit.blade.php ENDPATH**/ ?>