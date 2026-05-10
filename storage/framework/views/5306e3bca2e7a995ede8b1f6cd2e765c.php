<?php $__env->startSection('title', 'Edit Post'); ?>
<?php $__env->startSection('page-title', 'Edit Blog Post'); ?>
<?php $__env->startSection('breadcrumb', 'Admin / Blog / Edit'); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e(route('admin.blog.update', $blog->blog_detail_id)); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="row col-2" style="align-items:start;">
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            <div class="card">
                <div class="card-header"><h3>Post Content</h3></div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Title *</label>
                        <input type="text" name="title" class="form-control" value="<?php echo e(old('title', $blog->title)); ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Subtitle</label>
                        <input type="text" name="subtitle" class="form-control" value="<?php echo e(old('subtitle', $blog->subtitle)); ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Content *</label>
                        <textarea name="description" class="form-control" rows="12" required><?php echo e(old('description', $blog->description)); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tags</label>
                        <input type="text" name="tags" class="form-control" value="<?php echo e(old('tags', $blog->tags)); ?>">
                    </div>
                </div>
            </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            <div class="card">
                <div class="card-header"><h3>Featured Image</h3></div>
                <div class="card-body">
                    <div id="blog-img-preview" style="width:100%;height:180px;background:var(--bg);border-radius:8px;border:2px dashed var(--border);overflow:hidden;cursor:pointer;margin-bottom:1rem;" onclick="document.getElementById('blogImgInput').click()">
                        <?php if($blog->blog_image): ?>
                            <img src="<?php echo e(asset('storage/'.$blog->blog_image)); ?>" style="width:100%;height:100%;object-fit:cover;">
                        <?php else: ?>
                            <div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--text-muted);text-align:center;">
                                <div><i class="fas fa-image" style="font-size:2rem;display:block;margin-bottom:.5rem;"></i>Click to upload</div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <input type="file" id="blogImgInput" name="blog_image" accept="image/*" style="display:none;" onchange="previewBlogImg(this)">
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;margin-bottom:1rem;">
                        <input type="checkbox" name="is_published" value="1" <?php echo e($blog->is_published ? 'checked' : ''); ?>> Published
                    </label>
                    <button type="submit" class="btn btn-accent" style="width:100%;justify-content:center;"><i class="fas fa-save"></i> Update Post</button>
                    <a href="<?php echo e(route('admin.blog.index')); ?>" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:.5rem;">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
function previewBlogImg(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('blog-img-preview').innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lucksing\Downloads\ecommerce-laravel-v6-fixed (2)\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/admin/blog/edit.blade.php ENDPATH**/ ?>