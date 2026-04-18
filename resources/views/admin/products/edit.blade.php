@extends('admin.layout')

@section('title', 'Edit Product - Products Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Edit Product</h1>
            <p class="text-muted mb-0">Update product information for {{ $product->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-modern">
                <i class="bi bi-arrow-left me-2"></i>Back to Products
            </a>
        </div>
    </div>

    <!-- Product Info Bar -->
    <div class="card shadow-sm mb-4 info-bar">
        <div class="card-body py-3">
            <div class="d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="product-thumb">
                    @else
                        <img src="{{ asset('images/default-avatar.png') }}" alt="{{ $product->name }}" class="product-thumb">
                    @endif
                    <div class="product-info">
                        <div class="product-name-bar">{{ $product->name }}</div>
                        <div class="product-meta">
                            <span class="meta-item">
                                <i class="bi bi-tag me-1"></i>{{ $product->category->name ?? 'Uncategorized' }}
                            </span>
                            <span class="meta-item">
                                <i class="bi bi-currency-exchange me-1"></i>LKR {{ number_format($product->price, 2) }}
                            </span>
                            <span class="meta-item">
                                <i class="bi bi-box me-1"></i>{{ $product->stock }} in stock
                            </span>
                        </div>
                    </div>
                </div>
                <div class="product-actions">
                    <button type="button" class="btn btn-sm btn-outline-info" onclick="quickView()">
                        <i class="bi bi-eye me-1"></i>View Live
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" id="productEditForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Left Column - Main Information -->
            <div class="col-lg-8">
                <!-- Basic Information Card -->
                <div class="card shadow modern-card mb-4">
                    <div class="card-header modern-card-header">
                        <h6 class="modern-card-title">
                            <i class="bi bi-box-seam me-2"></i>Basic Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label for="name" class="form-label-modern">
                                    Product Name <span class="required">*</span>
                                </label>
                                <div class="input-group-modern">
                                    <i class="bi bi-tag input-icon"></i>
                                    <input type="text" 
                                           name="name" 
                                           class="form-control-modern @error('name') is-invalid @enderror" 
                                           placeholder="Enter product name..." 
                                           value="{{ old('name', $product->name) }}" 
                                           required
                                           maxlength="255">
                                    <div class="input-counter">
                                        <span id="nameCounter">{{ strlen($product->name) }}</span>/255
                                    </div>
                                </div>
                                @error('name')
                                    <div class="error-feedback">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12">
                                <label for="description" class="form-label-modern">
                                    Description <span class="optional">(Optional)</span>
                                </label>
                                <div class="textarea-modern">
                                    <textarea name="description" 
                                              class="form-control-modern @error('description') is-invalid @enderror" 
                                              placeholder="Describe your product in detail..." 
                                              rows="6"
                                              maxlength="1000">{{ old('description', $product->description) }}</textarea>
                                    <div class="textarea-footer">
                                        <span class="char-counter">
                                            <span id="descCounter">{{ strlen($product->description ?? '') }}</span>/1000
                                        </span>
                                        <span class="text-hint">Markdown supported</span>
                                    </div>
                                </div>
                                @error('description')
                                    <div class="error-feedback">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Image Card -->
                <div class="card shadow modern-card mb-4">
                    <div class="card-header modern-card-header">
                        <h6 class="modern-card-title">
                            <i class="bi bi-image me-2"></i>Product Image
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="image-edit-area" id="imageEditArea">
                            <input type="file" 
                                   name="image" 
                                   class="image-input" 
                                   id="imageInput"
                                   accept="image/*">
                            <div class="current-image" id="currentImage">
                                @if($product->image)
                                    <div class="image-display">
                                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
                                        <div class="image-overlay">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeImage()">
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="image-display">
                                        <img src="{{ asset('images/default-avatar.png') }}" alt="{{ $product->name }}">
                                        <div class="image-overlay">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeImage()">
                                                <i class="bi bi-trash"></i> Remove
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @error('image')
                            <div class="error-feedback">
                                <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Right Column - Settings -->
            <div class="col-lg-4">
                <!-- Product Settings Card -->
                <div class="card shadow modern-card mb-4">
                    <div class="card-header modern-card-header">
                        <h6 class="modern-card-title">
                            <i class="bi bi-gear me-2"></i>Product Settings
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="space-y-4">
                            <div>
                                <label for="category_id" class="form-label-modern">
                                    Category <span class="required">*</span>
                                </label>
                                <div class="select-modern">
                                    <i class="bi bi-folder select-icon"></i>
                                    <select name="category_id" 
                                            class="form-control-modern @error('category_id') is-invalid @enderror" 
                                            required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('category_id')
                                    <div class="error-feedback">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="price" class="form-label-modern">
                                    Price (LKR) <span class="required">*</span>
                                </label>
                                <div class="price-input-wrapper">
                                    <span class="currency-symbol">LKR</span>
                                    <input type="number" 
                                           name="price" 
                                           class="form-control-modern @error('price') is-invalid @enderror" 
                                           placeholder="0.00" 
                                           step="0.01" 
                                           min="0" 
                                           value="{{ old('price', $product->price) }}" 
                                           required>
                                </div>
                                @error('price')
                                    <div class="error-feedback">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label-modern">
                                    Unit <span class="required">*</span>
                                </label>
                                <div class="unit-input-group">
                                    <div class="unit-value-wrapper">
                                        <input type="number" 
                                               name="unit_value" 
                                               class="form-control-modern @error('unit_value') is-invalid @enderror" 
                                               placeholder="1" 
                                               step="0.01" 
                                               min="0.01" 
                                               value="{{ old('unit_value', $product->unit_value ?? 1) }}" 
                                               required>
                                    </div>
                                    <div class="select-modern unit-select-wrapper">
                                        <i class="bi bi-rulers select-icon"></i>
                                        <select name="unit" 
                                                class="form-control-modern @error('unit') is-invalid @enderror" 
                                                required>
                                            <option value="">Select Unit</option>
                                            <option value="pcs" {{ old('unit', $product->unit ?? 'pcs') == 'pcs' ? 'selected' : '' }}>Pieces</option>
                                            <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilogram</option>
                                            <option value="g" {{ old('unit', $product->unit) == 'g' ? 'selected' : '' }}>Gram</option>
                                            <option value="l" {{ old('unit', $product->unit) == 'l' ? 'selected' : '' }}>Liter</option>
                                            <option value="ml" {{ old('unit', $product->unit) == 'ml' ? 'selected' : '' }}>Milliliter</option>
                                            <option value="m" {{ old('unit', $product->unit) == 'm' ? 'selected' : '' }}>Meter</option>
                                            <option value="cm" {{ old('unit', $product->unit) == 'cm' ? 'selected' : '' }}>Centimeter</option>
                                            <option value="box" {{ old('unit', $product->unit) == 'box' ? 'selected' : '' }}>Box</option>
                                            <option value="pack" {{ old('unit', $product->unit) == 'pack' ? 'selected' : '' }}>Pack</option>
                                            <option value="bottle" {{ old('unit', $product->unit) == 'bottle' ? 'selected' : '' }}>Bottle</option>
                                            <option value="can" {{ old('unit', $product->unit) == 'can' ? 'selected' : '' }}>Can</option>
                                            <option value="bag" {{ old('unit', $product->unit) == 'bag' ? 'selected' : '' }}>Bag</option>
                                        </select>
                                    </div>
                                </div>
                                @error('unit_value')
                                    <div class="error-feedback">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                                @error('unit')
                                    <div class="error-feedback">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div>
                                <label for="stock" class="form-label-modern">
                                    Stock Quantity <span class="required">*</span>
                                </label>
                                <div class="stock-input-wrapper">
                                    <i class="bi bi-box input-icon"></i>
                                    <input type="number" 
                                           name="stock" 
                                           class="form-control-modern @error('stock') is-invalid @enderror" 
                                           placeholder="0" 
                                           min="0" 
                                           value="{{ old('stock', $product->stock) }}" 
                                           required>
                                    <div class="stock-status" id="stockStatus">
                                        @if($product->stock > 10)
                                            <i class="bi bi-check-circle text-success"></i>
                                            <span>In Stock</span>
                                        @elseif($product->stock > 0)
                                            <i class="bi bi-exclamation-triangle text-warning"></i>
                                            <span>Low Stock</span>
                                        @else
                                            <i class="bi bi-x-circle text-danger"></i>
                                            <span>Out of Stock</span>
                                        @endif
                                    </div>
                                </div>
                                @error('stock')
                                    <div class="error-feedback">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="stock-alerts">
                                <label class="form-label-modern">Stock Alerts</label>
                                <div class="alert-options">
                                    <label class="alert-option">
                                        <input type="checkbox" name="low_stock_alert" value="1" {{ $product->low_stock_alert ?? false ? 'checked' : '' }}>
                                        <span class="checkmark"></span>
                                        Alert when stock is low
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Stats Card -->
                <div class="card shadow-sm stats-card mb-4">
                    <div class="card-body">
                        <h6 class="stats-title">Product Statistics</h6>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">{{ $product->created_at ? $product->created_at->format('M d, Y') : 'N/A' }}</div>
                                <div class="stat-label">Created</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $product->updated_at ? $product->updated_at->diffForHumans() : 'N/A' }}</div>
                                <div class="stat-label">Last Updated</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card shadow-sm actions-card mb-4">
                    <div class="card-body">
                        <h6 class="stats-title">Quick Actions</h6>
                        <div class="action-grid">
                            <button type="button" class="action-btn" onclick="duplicateProduct()">
                                <i class="bi bi-copy"></i>
                                <span>Duplicate</span>
                            </button>
                            <button type="button" class="action-btn" onclick="viewAnalytics()">
                                <i class="bi bi-graph-up"></i>
                                <span>Analytics</span>
                            </button>
                            <button type="button" class="action-btn" onclick="exportProduct()">
                                <i class="bi bi-download"></i>
                                <span>Export</span>
                            </button>
                            <button type="button" class="action-btn danger" onclick="archiveProduct()">
                                <i class="bi bi-archive"></i>
                                <span>Archive</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <div class="d-flex gap-3 justify-content-between align-items-center">
                <div class="save-indicator">
                    <i class="bi bi-cloud-arrow-up"></i>
                    <span>Ready to save</span>
                </div>
                <div class="action-buttons">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-modern">
                        <i class="bi bi-x-lg me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-modern" id="submitBtn">
                        <i class="bi bi-check-lg me-2"></i>Update Product
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
// Form validation and interactions
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.querySelector('input[name="name"]');
    const descTextarea = document.querySelector('textarea[name="description"]');
    const stockInput = document.querySelector('input[name="stock"]');
    const priceInput = document.querySelector('input[name="price"]');
    const imageInput = document.getElementById('imageInput');
    const form = document.getElementById('productEditForm');

    // Character counters
    nameInput.addEventListener('input', function() {
        document.getElementById('nameCounter').textContent = this.value.length;
    });

    descTextarea.addEventListener('input', function() {
        document.getElementById('descCounter').textContent = this.value.length;
    });

    // Stock status update
    stockInput.addEventListener('input', function() {
        const stockStatus = document.getElementById('stockStatus');
        
        if (this.value > 10) {
            stockStatus.innerHTML = '<i class="bi bi-check-circle text-success"></i><span>In Stock</span>';
        } else if (this.value > 0) {
            stockStatus.innerHTML = '<i class="bi bi-exclamation-triangle text-warning"></i><span>Low Stock</span>';
        } else {
            stockStatus.innerHTML = '<i class="bi bi-x-circle text-danger"></i><span>Out of Stock</span>';
        }
    });

    // Image upload preview
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        const currentImage = document.getElementById('currentImage');
        
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                currentImage.innerHTML = `
                    <div class="image-display">
                        <img src="${e.target.result}" alt="Preview">
                        <div class="image-overlay">
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeImage()">
                                <i class="bi bi-trash"></i> Remove
                            </button>
                        </div>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Updating...';
        submitBtn.disabled = true;
    });
});

// Quick action functions
function quickView() {
    // Open product in new tab or modal
    console.log('Viewing product live');
}

function duplicateProduct() {
    if (confirm('Create a duplicate of this product?')) {
        // Redirect to create with pre-filled data
        console.log('Duplicating product');
    }
}

function viewAnalytics() {
    // Show product analytics
    console.log('Viewing analytics');
}

function exportProduct() {
    // Export product data
    console.log('Exporting product');
}

function archiveProduct() {
    if (confirm('Archive this product? It will be hidden from the catalog.')) {
        // Archive product
        console.log('Archiving product');
    }
}

function removeImage() {
    const currentImage = document.getElementById('currentImage');
    currentImage.innerHTML = `
        <div class="upload-content">
            <i class="bi bi-cloud-upload upload-icon"></i>
            <h6 class="upload-title">Add product image</h6>
            <p class="upload-text">Click to browse or drag and drop</p>
            <p class="upload-hint">Max size: 2MB • JPG, PNG, GIF</p>
        </div>
    `;
}
</script>
@endpush

<style>
/* Modern Form Styles */
.form-label-modern {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.75rem;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.required {
    color: #dc3545;
}

.optional {
    color: #6c757d;
    font-size: 12px;
}

/* Info Bar */
.info-bar {
    border-radius: 15px;
    border: none;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.product-thumb {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    object-fit: cover;
    margin-right: 15px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.product-thumb-placeholder {
    width: 60px;
    height: 60px;
    border-radius: 10px;
    background: #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 20px;
    margin-right: 15px;
}

.product-info {
    flex-grow: 1;
}

.product-name-bar {
    font-weight: 700;
    color: #2c3e50;
    font-size: 16px;
    margin-bottom: 4px;
}

.product-meta {
    display: flex;
    gap: 20px;
}

.meta-item {
    font-size: 13px;
    color: #6c757d;
    display: flex;
    align-items: center;
}

/* Input Groups */
.input-group-modern {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 15px;
    color: #6c757d;
    z-index: 10;
    font-size: 16px;
}

.form-control-modern {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 12px 15px 12px 45px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    background: white;
}

.input-counter {
    position: absolute;
    right: 15px;
    color: #6c757d;
    font-size: 12px;
    font-weight: 500;
}

/* Textarea */
.textarea-modern {
    position: relative;
}

.textarea-modern textarea {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 15px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #f8f9fa;
    resize: vertical;
}

.textarea-modern textarea:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    background: white;
}

.textarea-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 8px;
    font-size: 12px;
    color: #6c757d;
}

/* Price Input */
.price-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.currency-symbol {
    position: absolute;
    left: 15px;
    color: #6c757d;
    font-weight: 600;
    z-index: 10;
}

.price-input-wrapper input {
    padding-left: 60px;
}

/* Stock Input */
.stock-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.stock-status {
    position: absolute;
    right: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 12px;
    font-weight: 600;
}

/* Select */
.select-modern {
    position: relative;
    display: flex;
    align-items: center;
}

.select-icon {
    position: absolute;
    left: 15px;
    color: #6c757d;
    z-index: 10;
    font-size: 16px;
}

.select-modern select {
    padding-left: 45px;
}

/* Image Edit */
.image-edit-area {
    position: relative;
}

.image-input {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.image-display {
    position: relative;
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
}

.image-display img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 15px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.image-overlay {
    position: absolute;
    top: 10px;
    right: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.image-display:hover .image-overlay {
    opacity: 1;
}

.upload-content {
    padding: 3rem;
    text-align: center;
    border: 2px dashed #dee2e6;
    border-radius: 15px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.upload-content:hover {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
}

.upload-icon {
    font-size: 3rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

.upload-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.upload-text {
    color: #6c757d;
    margin-bottom: 0.5rem;
}

.upload-hint {
    font-size: 0.875rem;
    color: #adb5bd;
}

/* Error Feedback */
.error-feedback {
    display: flex;
    align-items: center;
    gap: 5px;
    color: #dc3545;
    font-size: 13px;
    margin-top: 5px;
}

/* Modern Card */
.modern-card {
    border-radius: 15px;
    border: none;
    overflow: hidden;
}

.modern-card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.25rem 1.5rem;
}

.modern-card-title {
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 14px;
    margin: 0;
}

/* Stats Card */
.stats-card {
    border-radius: 15px;
    border: none;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.stats-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stats-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: white;
    border-radius: 10px;
}

.stat-value {
    font-weight: 700;
    color: #2c3e50;
    font-size: 14px;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 11px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Actions Card */
.actions-card {
    border-radius: 15px;
    border: none;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.action-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem;
    background: white;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    gap: 5px;
}

.action-btn:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    transform: translateY(-2px);
}

.action-btn i {
    font-size: 20px;
    color: #667eea;
}

.action-btn span {
    font-size: 11px;
    font-weight: 600;
    color: #495057;
}

.action-btn.danger i {
    color: #dc3545;
}

/* Stock Alerts */
.stock-alerts {
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e9ecef;
}

.alert-option {
    display: flex;
    align-items: center;
    cursor: pointer;
    font-size: 14px;
    color: #495057;
}

.alert-option input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #dee2e6;
    border-radius: 4px;
    margin-right: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.alert-option input:checked + .checkmark {
    background: #667eea;
    border-color: #667eea;
}

.alert-option input:checked + .checkmark::after {
    content: '✓';
    color: white;
    font-size: 12px;
}

/* Form Actions */
.form-actions {
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    margin-top: 2rem;
}

.save-indicator {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #198754;
    font-size: 14px;
    font-weight: 600;
}

.action-buttons {
    display: flex;
    gap: 12px;
}

/* Modern Button */
.btn-modern {
    border-radius: 25px;
    font-weight: 600;
    padding: 10px 20px;
    border: none;
    transition: all 0.3s ease;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 13px;
}

.btn-primary.btn-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
}

.btn-outline-secondary.btn-modern {
    background: white;
    border: 2px solid #e9ecef;
    color: #6c757d;
}

.btn-outline-secondary.btn-modern:hover {
    border-color: #6c757d;
    background: #f8f9fa;
}

.space-y-4 > * + * {
    margin-top: 1.5rem;
}

/* Unit Input Group */
.unit-input-group {
    display: flex;
    gap: 8px;
    align-items: center;
}

.unit-value-wrapper {
    flex: 0 0 80px;
}

.unit-value-wrapper input {
    text-align: center;
    padding: 12px 8px;
}

.unit-select-wrapper {
    flex: 1;
}

.unit-select-wrapper select {
    padding-left: 35px;
}
</style>
@endsection
