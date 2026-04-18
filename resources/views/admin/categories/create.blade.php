@extends('admin.layout')
@section('title', 'Add Category')
@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Add Category</h1>
            <p class="text-muted mb-0">Create a new product category</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary btn-modern">
                <i class="bi bi-arrow-left me-2"></i>Back to Categories
            </a>
        </div>
    </div>

    <!-- Create Category Form -->
    <div class="card shadow modern-card">
        <div class="card-header modern-card-header">
            <h6 class="modern-card-title">
                <i class="bi bi-plus-circle me-2"></i>
                Category Information
            </h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                
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
                                   value="{{ old('name') }}"
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
                                       value="{{ old('slug') }}">
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
                                      placeholder="Provide a brief description of this category...">{{ old('description') }}</textarea>
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
                        <!-- Quick Tips Card -->
                        <div class="card border-0 bg-light">
                            <div class="card-body">
                                <h6 class="card-title fw-bold">
                                    <i class="bi bi-lightbulb me-2 text-warning"></i>Quick Tips
                                </h6>
                                <ul class="small text-muted mb-0">
                                    <li class="mb-2">Use descriptive names that customers will easily understand</li>
                                    <li class="mb-2">Keep category names short and memorable</li>
                                    <li class="mb-2">Use lowercase letters and hyphens in slugs</li>
                                    <li class="mb-0">Consider how categories will organize your products</li>
                                </ul>
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
                                        <div class="preview-name" id="preview-name">Category Name</div>
                                        <div class="preview-slug" id="preview-slug">category-slug</div>
                                    </div>
                                </div>
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
                            <i class="bi bi-check-circle me-2"></i>Create Category
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
