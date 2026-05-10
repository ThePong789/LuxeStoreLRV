<?php $__env->startSection('title', 'Blog'); ?>
<?php $__env->startSection('page-title', 'Blog Posts'); ?>
<?php $__env->startSection('breadcrumb', 'Admin / Blog'); ?>

<?php $__env->startSection('content'); ?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <div></div>
    <a href="<?php echo e(route('admin.blog.create')); ?>" class="btn btn-accent"><i class="fas fa-plus"></i> New Post</a>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Post</th>
                    <th>Author</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.75rem;">
                            <div style="width:52px;height:40px;background:var(--bg);border-radius:6px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                                <?php if($blog->blog_image): ?>
                                    <img src="<?php echo e(asset('storage/'.$blog->blog_image)); ?>" style="width:100%;height:100%;object-fit:cover;">
                                <?php else: ?> <i class="fas fa-newspaper"></i> <?php endif; ?>
                            </div>
                            <div>
                                <div style="font-weight:500;font-size:.875rem;"><?php echo e($blog->title); ?></div>
                                <div style="font-size:.75rem;color:var(--text-muted);"><?php echo e(Str::limit($blog->subtitle, 50)); ?></div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.875rem;"><?php echo e($blog->blog->author_name ?? 'N/A'); ?></td>
                    <td><span class="badge <?php echo e($blog->is_published ? 'badge-success' : 'badge-secondary'); ?>"><?php echo e($blog->is_published ? 'Published' : 'Draft'); ?></span></td>
                    <td style="color:var(--text-muted);font-size:.8rem;"><?php echo e($blog->created_at->format('M d, Y')); ?></td>
                    <td>
                        <div style="display:flex;gap:.4rem;">
                            <a href="<?php echo e(route('blog.show', $blog->blog_detail_id)); ?>" target="_blank" class="btn btn-outline btn-xs"><i class="fas fa-eye"></i></a>
                            <a href="<?php echo e(route('admin.blog.edit', $blog->blog_detail_id)); ?>" class="btn btn-outline btn-xs"><i class="fas fa-edit"></i></a>
                            <form action="<?php echo e(route('admin.blog.destroy', $blog->blog_detail_id)); ?>" method="POST" onsubmit="return confirm('Delete post?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-danger btn-xs" type="submit"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="5"><div class="empty-state"><i class="fas fa-newspaper"></i><h3>No blog posts yet</h3><a href="<?php echo e(route('admin.blog.create')); ?>" class="btn btn-accent" style="margin-top:1rem;">Create First Post</a></div></td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<div style="margin-top:1rem;"><?php echo e($blogs->links()); ?></div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\vesot\Downloads\Telegram Desktop\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/admin/blog/index.blade.php ENDPATH**/ ?>