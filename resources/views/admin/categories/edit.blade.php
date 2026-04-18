@extends('admin.layout')
@section('title', 'Edit Category')
@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Category</h1>
            <p class="text-muted mb-0">Update category information</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-modern">
                <i class="bi bi-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <!-- Edit Category Form -->
    <div class="card shadow modern-card">
        <div class="card-header modern-card-header">
            <h6 class="modern-card-title">
                <i class="bi bi-pencil me-2"></i>
                Edit Category: {{ $category->name }}
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <!-- Category Name -->
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">
                                <i class="bi bi-tag me-1"></i>Category Name
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   class="form-control form-control-modern" 
                                   placeholder="Enter category name (e.g., Electronics, Clothing, Food)"
                                   required 
                                   value="{{ old('name', $category->name) }}"
                                   oninput="generateSlug(this.value)">
                            @error('name')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Category Slug -->
                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold">
                                <i class="bi bi-link me-1"></i>URL Slug
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-globe"></i>
                                </span>
                                <input type="text" 
                                       name="slug" 
                                       id="slug" 
                                       class="form-control form-control-modern" 
                                       placeholder="url-friendly-category-name"
                                       required 
                                       value="{{ old('slug', $category->slug) }}">
                            </div>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                This will be used in the URL. Only letters, numbers, and hyphens are allowed.
                            </div>
                            @error('slug')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Category Description -->
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                <i class="bi bi-text-paragraph me-1"></i>Description
                                <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      class="form-control form-control-modern" 
                                      rows="4" 
                                      placeholder="Provide a brief description of this category...">{{ old('description', $category->description) }}</textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Help users understand what products belong in this category.
                            </div>
                            @error('description')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Category Info Card -->
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">
                                    <i class="bi bi-info-circle me-2 text-info"></i>Category Info
                                </h6>
                                <div class="category-info-display">
                                    <div class="info-item">
                                        <small class="text-muted">Created:</small>
                                        <div>{{ optional($category->created_at)->format('M d, Y h:i A') ?? 'N/A' }}</div>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted">Last Updated:</small>
                                        <div>{{ optional($category->updated_at)->format('M d, Y h:i A') ?? 'N/A' }}</div>
                                    </div>
                                    <div class="info-item">
                                        <small class="text-muted">Products:</small>
                                        <div>{{ $category->products_count ?? $category->products()->count() }} items</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Card -->
                        <div class="card border-0 bg-gradient mt-3">
                            <div class="card-body">
                                <h6 class="card-title fw-bold text-white">
                                    <i class="bi bi-eye me-2"></i>Preview
                                </h6>
                                <div class="category-preview">
                                    <div class="preview-icon">
                                        <i class="bi bi-tag-fill"></i>
                                    </div>
                                    <div class="preview-details">
                                        <div class="preview-name" id="preview-name">{{ $category->name }}</div>
                                        <div class="preview-slug" id="preview-slug">{{ $category->slug }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions Card -->
                        <div class="card border-0 bg-danger bg-opacity-10 mt-3">
                            <div class="card-body">
                                <h6 class="card-title fw-bold text-danger">
                                    <i class="bi bi-exclamation-triangle me-2"></i>Danger Zone
                                </h6>
                                <p class="small text-muted mb-3">
                                    Deleting this category will remove it permanently. Products in this category will need to be reassigned.
                                </p>
                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirmDelete(event, '{{ $category->name }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        <i class="bi bi-trash me-2"></i>Delete Category
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <div>
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Required fields are marked with <span class="text-danger">*</span>
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-modern">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-modern">
                            <i class="bi bi-check-circle me-2"></i>Update Category
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function generateSlug(name) {
    const slug = name
        .toLowerCase()
        .replace(/[^\w\s-]/g, '') // Remove special characters
        .replace(/\s+/g, '-') // Replace spaces with hyphens
        .replace(/-+/g, '-') // Replace multiple hyphens with single
        .trim();
    
    document.getElementById('slug').value = slug;
    updatePreview(name, slug);
}

function updatePreview(name, slug) {
    document.getElementById('preview-name').textContent = name || 'Category Name';
    document.getElementById('preview-slug').textContent = slug || 'category-slug';
}

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

// Initialize preview on page load
document.addEventListener('DOMContentLoaded', function() {
    const nameField = document.getElementById('name');
    const slugField = document.getElementById('slug');
    
    if (nameField.value) {
        updatePreview(nameField.value, slugField.value);
    }
    
    // Update preview when slug is manually changed
    slugField.addEventListener('input', function() {
        updatePreview(nameField.value, this.value);
    });
});
</script>

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

<style>
/* Form Controls */
.form-control-modern {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    padding: 12px 16px;
    transition: all 0.3s ease;
    font-size: 14px;
}

.form-control-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

/* Input Group */
.input-group-text {
    border-radius: 10px 0 0 10px;
    border: 2px solid #e9ecef;
    border-right: none;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.input-group .form-control-modern {
    border-radius: 0 10px 10px 0;
    border-left: none;
}

/* Category Info Display */
.category-info-display {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.info-item {
    padding: 8px 0;
    border-bottom: 1px solid #dee2e6;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item small {
    display: block;
    margin-bottom: 2px;
}

/* Category Preview */
.category-preview {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 10px;
    backdrop-filter: blur(10px);
}

.preview-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 16px;
}

.preview-details {
    flex-grow: 1;
}

.preview-name {
    font-weight: 600;
    color: white;
    font-size: 14px;
    margin-bottom: 2px;
}

.preview-slug {
    font-size: 11px;
    color: rgba(255, 255, 255, 0.8);
    font-family: 'Courier New', monospace;
}

/* Gradient Background */
.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Invalid Feedback */
.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

/* Form Text */
.form-text {
    font-size: 0.875em;
    color: #6c757d;
    margin-top: 0.25rem;
}

/* Responsive */
@media (max-width: 768px) {
    .col-md-4 {
        margin-top: 2rem;
    }
}
</style>
@endsection
