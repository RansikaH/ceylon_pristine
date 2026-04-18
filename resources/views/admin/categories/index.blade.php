@extends('admin.layout')
@section('title', 'Categories Management')
@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Categories Management</h1>
            <p class="text-muted mb-0">Manage product categories and organization</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-modern">
                <i class="bi bi-plus-circle me-2"></i>Add Category
            </a>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="card shadow modern-card">
        <div class="card-header modern-card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="modern-card-title">
                    <i class="bi bi-tags me-2"></i>
                    All Categories ({{ $categories->count() }})
                </h6>
                <div class="d-flex gap-2">
                    <span class="badge bg-primary modern-badge">
                        <i class="bi bi-box-seam me-1"></i>
                        {{ isset($stats['total_products']) ? $stats['total_products'] : 0 }} Total Products
                    </span>
                    <span class="badge bg-success modern-badge">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ $categories->where('name', '!=', '')->count() }} Active Categories
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="modern-th">Category</th>
                            <th class="modern-th">Slug</th>
                            <th class="modern-th">Products</th>
                            <th class="modern-th">Created</th>
                            <th class="modern-th text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($categories->count() > 0)
                            @foreach($categories as $category)
                            <tr class="modern-row">
                                <td class="modern-td">
                                    <div class="category-info">
                                        <div class="category-icon-wrapper">
                                            <div class="category-icon">
                                                <i class="bi bi-tag-fill"></i>
                                            </div>
                                        </div>
                                        <div class="category-details">
                                            <div class="category-name">{{ $category->name }}</div>
                                            <div class="category-description">{{ $category->description ?? 'No description' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="modern-td">
                                    <span class="slug-badge">{{ $category->slug }}</span>
                                </td>
                                <td class="modern-td">
                                    <div class="product-count">
                                        <span class="count-number">{{ $category->products_count ?? $category->products()->count() }}</span>
                                        <span class="count-label">products</span>
                                    </div>
                                </td>
                                <td class="modern-td">
                                    <div class="date-info">
                                        <div class="date-value">{{ optional($category->created_at)->format('M d, Y') ?? 'N/A' }}</div>
                                        <div class="time-value">{{ optional($category->created_at)->format('h:i A') ?? 'N/A' }}</div>
                                    </div>
                                </td>
                                <td class="modern-td text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.categories.edit', $category) }}" 
                                           class="btn btn-sm btn-primary btn-action"
                                           title="Edit Category">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-info btn-action"
                                                title="View Products"
                                                onclick="window.location.href='{{ route('admin.products.index', ['category' => $category->id]) }}'">
                                            <i class="bi bi-box-seam"></i>
                                        </button>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" 
                                              method="POST" 
                                              style="display:inline-block"
                                              onsubmit="return confirmDelete(event, '{{ $category->name }}')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-danger btn-action"
                                                    title="Delete Category">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-tags empty-icon"></i>
                                        <h5 class="empty-title">No Categories Found</h5>
                                        <p class="empty-text">Start by creating your first product category</p>
                                        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-modern">
                                            <i class="bi bi-plus-circle me-2"></i>Add First Category
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
        @if($categories->hasPages())
        <div class="card-footer bg-transparent border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="text-muted small">
                    Showing {{ $categories->firstItem() }} to {{ $categories->lastItem() }} of {{ $categories->total() }} categories
                </div>
                {{ $categories->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(event, categoryName) {
    event.preventDefault();
    Swal.fire({
        title: 'Delete Category?',
        html: `Are you sure you want to delete <strong>${categoryName}</strong>?<br><br>This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            event.target.submit();
        }
    });
    return false;
}
</script>

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

<style>
/* Category Info */
.category-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.category-icon-wrapper {
    flex-shrink: 0;
}

.category-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 18px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
}

.category-icon:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

.category-details {
    flex-grow: 1;
}

.category-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 15px;
    margin-bottom: 2px;
}

.category-description {
    font-size: 12px;
    color: #6c757d;
    line-height: 1.4;
}

/* Slug Badge */
.slug-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: #e9ecef;
    border-radius: 15px;
    font-size: 11px;
    font-weight: 700;
    font-family: 'Courier New', monospace;
    color: #495057;
}

/* Product Count */
.product-count {
    text-align: center;
}

.count-number {
    display: block;
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
}

.count-label {
    font-size: 11px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Date Info */
.date-info {
    text-align: left;
}

.date-value {
    font-size: 13px;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 2px;
}

.time-value {
    font-size: 11px;
    color: #6c757d;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-action {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: scale(1.1);
}

/* Empty State */
.empty-state {
    padding: 3rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-title {
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .category-description {
        display: none;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }
}
</style>
@endsection
