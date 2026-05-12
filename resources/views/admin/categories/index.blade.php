@extends('layouts.admin')

@section('title', 'Categories')
@section('page-title', 'Categories')
@section('breadcrumb', 'Admin / Categories')

@section('content')
    @php $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'staff'; @endphp
    <div class="row col-2" style="align-items:start;">
        <!-- ADD CATEGORY -->
        <div class="card">
            <div class="card-header">
                <h3>Add Category</h3>
            </div>
            <div class="card-body">
                <form action="{{ route($routePrefix . '.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Category Name *</label>
                        <input type="text" name="category_name" class="form-control" value="{{ old('category_name') }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
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
                        @forelse($categories as $cat)
                            <tr>
                                <td>
                                    <div style="display:flex;align-items:center;gap:.75rem;">
                                        <div
                                            style="width:40px;height:40px;background:var(--bg);border-radius:8px;overflow:hidden;flex-shrink:0;display:flex;align-items:center;justify-content:center;color:var(--text-muted);">
                                            @if ($cat->category_image)
                                                <img src="{{ asset('storage/' . $cat->category_image) }}"
                                                    style="width:100%;height:100%;object-fit:cover;">
                                            @else
                                                <i class="fas fa-tag"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <div style="font-weight:500;font-size:.875rem;">{{ $cat->category_name }}</div>
                                            <div style="font-size:.75rem;color:var(--text-muted);">
                                                {{ Str::limit($cat->description, 40) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge badge-info">{{ $cat->products_count }}</span></td>
                                <td>
                                    <div style="display:flex;gap:.4rem;">
                                        <button class="btn btn-outline btn-xs"
                                            onclick="editCat({{ $cat->category_id }}, '{{ addslashes($cat->category_name) }}', '{{ addslashes($cat->description) }}', '{{ $cat->category_image }}')"><i
                                                class="fas fa-edit"></i></button>
                                        <form action="{{ route($routePrefix . '.categories.destroy', $cat->category_id) }}"
                                            method="POST" onsubmit="return confirm('Delete category?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger btn-xs" type="submit"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state" style="padding:2rem;"><i class="fas fa-tags"></i>
                                        <h3>No categories yet</h3>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding:1rem;">{{ $categories->links() }}</div>
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
                @csrf @method('PATCH')
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
    @endsection
    @push('scripts')
        <script>
            function editCat(id, name, desc, image) {
                document.getElementById('edit-cat-form').action = '/{{ $routePrefix }}/categories/' + id;
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
    @endpush
