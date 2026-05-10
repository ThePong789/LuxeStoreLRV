<?php $__env->startSection('title', 'Blog'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .blog-page { padding: 4rem 0; }
    .blog-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; }
    .blog-card { background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid var(--border); transition: all .3s; }
    .blog-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.1); }
    .blog-card-img { height: 210px; background: linear-gradient(135deg, var(--black), #2d2d4e); display: flex; align-items: center; justify-content: center; color: var(--gold); font-size: 3rem; overflow: hidden; }
    .blog-card-img img { width: 100%; height: 100%; object-fit: cover; }
    .blog-card-body { padding: 1.5rem; }
    .blog-meta { font-size: .75rem; color: var(--gray); margin-bottom: .5rem; display: flex; align-items: center; gap: .75rem; }
    .blog-meta .author { display: flex; align-items: center; gap: .3rem; }
    .blog-card-title { font-family: var(--font-display); font-size: 1.1rem; color: var(--black); margin-bottom: .5rem; line-height: 1.4; }
    .blog-card-title a { text-decoration: none; color: inherit; }
    .blog-card-title a:hover { color: var(--gold); }
    .blog-card-excerpt { font-size: .85rem; color: var(--gray); line-height: 1.6; margin-bottom: 1rem; }
    @media(max-width:1024px) { .blog-grid { grid-template-columns: repeat(2,1fr); } }
    @media(max-width:640px) { .blog-grid { grid-template-columns: 1fr; } }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-hero">
    <h1>Blog</h1>
    <p>Stories, tips and style inspiration</p>
    <div class="breadcrumb"><a href="<?php echo e(route('home')); ?>">Home</a> <span>/</span> <span>Blog</span></div>
</div>

<div class="container">
    <div class="blog-page">
        <?php if($blogs->isEmpty()): ?>
            <div style="text-align:center;padding:5rem 2rem;color:var(--gray);">
                <i class="fas fa-newspaper" style="font-size:3rem;margin-bottom:1rem;display:block;opacity:.3;"></i>
                <h3>No posts yet</h3>
            </div>
        <?php else: ?>
        <div class="blog-grid">
            <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="blog-card">
                <div class="blog-card-img">
                    <?php if($blog->blog_image): ?><img src="<?php echo e(asset('storage/'.$blog->blog_image)); ?>" alt="<?php echo e($blog->title); ?>">
                    <?php else: ?> <i class="fas fa-newspaper"></i> <?php endif; ?>
                </div>
                <div class="blog-card-body">
                    <div class="blog-meta">
                        <span class="author"><i class="fas fa-user-circle"></i> <?php echo e($blog->blog->author_name ?? 'LuxeStore'); ?></span>
                        <span>·</span>
                        <span><?php echo e($blog->created_at->format('M d, Y')); ?></span>
                        <?php if($blog->tags): ?><span>·</span><span><i class="fas fa-tag"></i> <?php echo e($blog->tags); ?></span><?php endif; ?>
                    </div>
                    <h3 class="blog-card-title"><a href="<?php echo e(route('blog.show', $blog->blog_detail_id)); ?>"><?php echo e($blog->title); ?></a></h3>
                    <p class="blog-card-excerpt"><?php echo e(Str::limit($blog->subtitle ?? strip_tags($blog->description), 100)); ?></p>
                    <a href="<?php echo e(route('blog.show', $blog->blog_detail_id)); ?>" class="btn btn-outline btn-sm">Read More <i class="fas fa-arrow-right"></i></a>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div style="margin-top:3rem;"><?php echo e($blogs->links()); ?></div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Lucksing\Downloads\ecommerce-laravel-v6-fixed (2)\ecommerce-laravel-v6-fixed\ecommerce-laravel-fixed\resources\views/blog/index.blade.php ENDPATH**/ ?>