@extends('admin.layout')

@section('title', 'Add Product - Products Management')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div>
                <h1 class="h3 mb-1">Add New Product</h1>
                <p class="text-muted mb-0">Create a new product for your catalog</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-modern">
                    <i class="bi bi-arrow-left me-2"></i>Back to Products
                </a>
            </div>
        </div>

        <!-- Progress Indicator -->
        <div class="card shadow-sm mb-4 progress-card">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="step-indicator active">
                            <i class="bi bi-info-circle"></i>
                        </div>
                        <div class="step-indicator">
                            <i class="bi bi-tag"></i>
                        </div>
                        <div class="step-indicator">
                            <i class="bi bi-image"></i>
                        </div>
                        <div class="step-indicator">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                    <div class="progress-steps">
                        <span class="step-text active">Basic Info</span>
                        <span class="step-text">Pricing</span>
                        <span class="step-text">Media</span>
                        <span class="step-text">Review</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

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
                                        <input type="text" name="name"
                                            class="form-control-modern @error('name') is-invalid @enderror"
                                            placeholder="Enter product name..." value="{{ old('name') }}" required
                                            maxlength="255">
                                        <div class="input-counter">
                                            <span id="nameCounter">0</span>/255
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
                                        <textarea name="description" class="form-control-modern @error('description') is-invalid @enderror"
                                            placeholder="Describe your product in detail..." rows="6" maxlength="1000">{{ old('description') }}</textarea>
                                        <div class="textarea-footer">
                                            <span class="char-counter">
                                                <span id="descCounter">0</span>/1000
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

                    <!-- Product Images Card -->
                    <div class="card shadow modern-card mb-4">
                        <div class="card-header modern-card-header">
                            <h6 class="modern-card-title">
                                <i class="bi bi-images me-2"></i>Product Images
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Main Image Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-medium">Main Product Image <span
                                        class="text-danger">*</span></label>
                                <div class="image-upload-area" id="mainImageUploadArea">
                                    <input type="file" name="main_image" class="image-input" id="mainImageInput"
                                        accept="image/*" required>
                                    <div class="upload-preview" id="mainUploadPreview">
                                        <div class="upload-content">
                                            <i class="bi bi-cloud-upload upload-icon"></i>
                                            <h6 class="upload-title">Drop main product image here</h6>
                                            <p class="upload-text">or click to browse</p>
                                            <p class="upload-hint">Max size: 2MB</p>
                                        </div>
                                    </div>
                                </div>
                                @error('main_image')
                                    <div class="error-feedback">
                                        <i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Additional Images Upload -->
                            <div class="mb-4">
                                <label class="form-label fw-medium">Additional Images <small
                                        class="text-muted">(Optional)</small></label>
                                <div class="multiple-images-upload" id="multipleImagesUpload">
                                    <div class="row g-3" id="additionalImagesContainer">
                                        <!-- Dynamic image previews will be added here -->
                                    </div>
                                    <button type="button" class="btn btn-outline-secondary btn-sm mt-3"
                                        onclick="addAdditionalImageInput()">
                                        <i class="bi bi-plus-circle me-2"></i>Add More Images
                                    </button>
                                </div>
                            </div>

                            <!-- Image Gallery Preview -->
                            <div id="imageGalleryPreview" class="mt-4" style="display: none;">
                                <label class="form-label fw-medium">Image Gallery Preview</label>
                                <div class="d-flex flex-wrap gap-2" id="galleryPreview">
                                    <!-- Gallery thumbnails will appear here -->
                                </div>
                            </div>
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
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                        <input type="number" name="price"
                                            class="form-control-modern @error('price') is-invalid @enderror"
                                            placeholder="0.00" step="0.01" min="0"
                                            value="{{ old('price') }}" required>
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
                                            <input type="number" name="unit_value"
                                                class="form-control-modern @error('unit_value') is-invalid @enderror"
                                                placeholder="1" step="0.01" min="0.01"
                                                value="{{ old('unit_value', 1) }}" required>
                                        </div>
                                        <div class="select-modern unit-select-wrapper">
                                            <i class="bi bi-rulers select-icon"></i>
                                            <select name="unit"
                                                class="form-control-modern @error('unit') is-invalid @enderror" required>
                                                <option value="">Select Unit</option>
                                                <option value="pcs"
                                                    {{ old('unit', 'pcs') == 'pcs' ? 'selected' : '' }}>Pieces</option>
                                                <option value="kg" {{ old('unit') == 'kg' ? 'selected' : '' }}>
                                                    Kilogram</option>
                                                <option value="g" {{ old('unit') == 'g' ? 'selected' : '' }}>Gram
                                                </option>
                                                <option value="l" {{ old('unit') == 'l' ? 'selected' : '' }}>Liter
                                                </option>
                                                <option value="ml" {{ old('unit') == 'ml' ? 'selected' : '' }}>
                                                    Milliliter</option>
                                                <option value="m" {{ old('unit') == 'm' ? 'selected' : '' }}>Meter
                                                </option>
                                                <option value="cm" {{ old('unit') == 'cm' ? 'selected' : '' }}>
                                                    Centimeter</option>
                                                <option value="box" {{ old('unit') == 'box' ? 'selected' : '' }}>Box
                                                </option>
                                                <option value="pack" {{ old('unit') == 'pack' ? 'selected' : '' }}>Pack
                                                </option>
                                                <option value="bottle" {{ old('unit') == 'bottle' ? 'selected' : '' }}>
                                                    Bottle</option>
                                                <option value="can" {{ old('unit') == 'can' ? 'selected' : '' }}>Can
                                                </option>
                                                <option value="bag" {{ old('unit') == 'bag' ? 'selected' : '' }}>Bag
                                                </option>
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
                                        <input type="number" name="stock"
                                            class="form-control-modern @error('stock') is-invalid @enderror"
                                            placeholder="0" min="0" value="{{ old('stock', 0) }}" required>
                                        <div class="stock-status" id="stockStatus">
                                            <i class="bi bi-check-circle text-success"></i>
                                            <span>In Stock</span>
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
                                            <input type="checkbox" name="low_stock_alert" value="1">
                                            <span class="checkmark"></span>
                                            Alert when stock is low
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats Card -->
                    <div class="card shadow-sm stats-card mb-4">
                        <div class="card-body">
                            <h6 class="stats-title">Product Preview</h6>
                            <div class="preview-content">
                                <div class="preview-image" id="previewImage">
                                    <i class="bi bi-image"></i>
                                </div>
                                <div class="preview-details">
                                    <div class="preview-name" id="previewName">Product Name</div>
                                    <div class="preview-category" id="previewCategory">Category</div>
                                    <div class="preview-price" id="previewPrice">LKR 0.00</div>
                                    <div class="preview-stock" id="previewStock">
                                        <i class="bi bi-check-circle"></i> In Stock
                                    </div>
                                </div>
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
                        <span>Auto-saved</span>
                    </div>
                    <div class="action-buttons">
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-modern">
                            <i class="bi bi-x-lg me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary btn-modern" id="submitBtn">
                            <i class="bi bi-check-lg me-2"></i>Create Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Global variables for image upload
            let additionalImageCount = 0;
            const uploadedImages = [];

            // Global functions for image upload
            function addAdditionalImageInput() {
                additionalImageCount++;
                const container = document.getElementById('additionalImagesContainer');
                const imageDiv = document.createElement('div');
                imageDiv.className = 'col-md-6';
                imageDiv.innerHTML = `
        <div class="image-upload-area additional-image-upload position-relative" data-index="${additionalImageCount}">
            <input type="file"
                   name="additional_images[]"
                   class="image-input"
                   id="additionalImage${additionalImageCount}"
                   accept="image/*"
                   onchange="handleAdditionalImageChange(${additionalImageCount}, this)">
            <div class="upload-preview" id="additionalPreview${additionalImageCount}">
                <div class="upload-content">
                    <i class="bi bi-cloud-upload upload-icon"></i>
                    <h6 class="upload-title">Additional image ${additionalImageCount}</h6>
                    <p class="upload-text">click to browse</p>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1" onclick="removeAdditionalImage(${additionalImageCount})">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;
                container.appendChild(imageDiv);
            }

            function handleAdditionalImageChange(index, input) {
                const file = input.files[0];
                const preview = document.getElementById(`additionalPreview${index}`);

                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.innerHTML = `
                <img src="${e.target.result}" alt="Additional Image ${index}" style="width: 100%; height: 150px; object-fit: cover;">
                <div class="text-center mt-2">
                    <small class="text-muted">${file.name}</small>
                </div>
            `;
                        updateGalleryPreview();
                    };
                    reader.readAsDataURL(file);
                }
            }

            function removeAdditionalImage(index) {
                const imageDiv = document.querySelector(`[data-index="${index}"]`).parentElement;
                imageDiv.remove();
                updateGalleryPreview();
            }

            function updateGalleryPreview() {
                const galleryPreview = document.getElementById('imageGalleryPreview');
                const galleryContainer = document.getElementById('galleryPreview');
                const mainImageInput = document.getElementById('mainImageInput');
                const mainImage = mainImageInput.files[0];

                let images = [];

                // Add main image
                if (mainImage) {
                    images.push({
                        url: URL.createObjectURL(mainImage),
                        name: 'Main Image',
                        is_primary: true
                    });
                }

                // Add additional images
                const additionalInputs = document.querySelectorAll('input[name="additional_images[]"]');
                additionalInputs.forEach((input, index) => {
                    if (input.files[0]) {
                        images.push({
                            url: URL.createObjectURL(input.files[0]),
                            name: `Additional ${index + 1}`,
                            is_primary: false
                        });
                    }
                });

                if (images.length > 0) {
                    galleryPreview.style.display = 'block';
                    galleryContainer.innerHTML = images.map(img => `
            <div class="gallery-thumbnail ${img.is_primary ? 'primary' : ''}">
                <img src="${img.url}" alt="${img.name}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                ${img.is_primary ? '<small class="text-primary d-block text-center mt-1">Main</small>' : ''}
            </div>
        `).join('');
                } else {
                    galleryPreview.style.display = 'none';
                }
            }

            // Form validation and interactions
            document.addEventListener('DOMContentLoaded', function() {
                const nameInput = document.querySelector('input[name="name"]');
                const descTextarea = document.querySelector('textarea[name="description"]');
                const stockInput = document.querySelector('input[name="stock"]');
                const priceInput = document.querySelector('input[name="price"]');
                const imageInput = document.getElementById('imageInput');
                const form = document.getElementById('productForm');

                // Character counters
                nameInput.addEventListener('input', function() {
                    document.getElementById('nameCounter').textContent = this.value.length;
                    document.getElementById('previewName').textContent = this.value || 'Product Name';
                });

                descTextarea.addEventListener('input', function() {
                    document.getElementById('descCounter').textContent = this.value.length;
                });

                // Stock status update
                stockInput.addEventListener('input', function() {
                    const stockStatus = document.getElementById('stockStatus');
                    const previewStock = document.getElementById('previewStock');

                    if (this.value > 10) {
                        stockStatus.innerHTML =
                            '<i class="bi bi-check-circle text-success"></i><span>In Stock</span>';
                        previewStock.innerHTML = '<i class="bi bi-check-circle"></i> In Stock';
                        previewStock.className = 'preview-stock in-stock';
                    } else if (this.value > 0) {
                        stockStatus.innerHTML =
                            '<i class="bi bi-exclamation-triangle text-warning"></i><span>Low Stock</span>';
                        previewStock.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Low Stock';
                        previewStock.className = 'preview-stock low-stock';
                    } else {
                        stockStatus.innerHTML =
                            '<i class="bi bi-x-circle text-danger"></i><span>Out of Stock</span>';
                        previewStock.innerHTML = '<i class="bi bi-x-circle"></i> Out of Stock';
                        previewStock.className = 'preview-stock out-of-stock';
                    }
                });

                // Price preview update
                priceInput.addEventListener('input', function() {
                    updatePricePreview();
                });

                // Unit preview update
                const unitSelect = document.querySelector('select[name="unit"]');
                const unitValueInput = document.querySelector('input[name="unit_value"]');

                unitSelect.addEventListener('change', function() {
                    updatePricePreview();
                });

                unitValueInput.addEventListener('input', function() {
                    updatePricePreview();
                });

                function updatePricePreview() {
                    const unitValue = parseFloat(unitValueInput.value || 1);
                    const unitText = unitSelect.options[unitSelect.selectedIndex].text;
                    const price = parseFloat(priceInput.value || 0).toFixed(2);

                    // Format unit value - remove decimal if whole number
                    const displayValue = unitValue === Math.floor(unitValue) ? Math.floor(unitValue) : unitValue;

                    let unitDisplay = '';
                    if (unitText === 'Pieces') {
                        unitDisplay = displayValue === 1 ? 'piece' : displayValue + ' pieces';
                    } else {
                        unitDisplay = displayValue + ' ' + unitText;
                    }

                    document.getElementById('previewPrice').textContent = `LKR ${price} / ${unitDisplay}`;
                }

                // Main image upload preview
                const mainImageInput = document.getElementById('mainImageInput');
                mainImageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const previewImage = document.getElementById('previewImage');

                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                            updateGalleryPreview();
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Category preview update
                const categorySelect = document.querySelector('select[name="category_id"]');
                categorySelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    document.getElementById('previewCategory').textContent = selectedOption.text || 'Category';
                });

                // Form submission
                form.addEventListener('submit', function(e) {
                    const submitBtn = document.getElementById('submitBtn');
                    submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creating...';
                    submitBtn.disabled = true;
                });
            });
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

        /* Textarea Modern */
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

        /* Select Modern */
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

        /* Image Upload */
        .image-upload-area {
            position: relative;
            border: 2px dashed #dee2e6;
            border-radius: 15px;
            background: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
            overflow: hidden;
        }

        .image-upload-area:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
        }

        .image-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-preview {
            padding: 3rem;
            text-align: center;
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

        /* Progress Card */
        .progress-card {
            border-radius: 15px;
            border: none;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .step-indicator {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .step-indicator.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .step-text {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            margin: 0 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .step-text.active {
            color: #667eea;
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

        .preview-content {
            display: flex;
            gap: 15px;
            align-items: start;
        }

        .preview-image {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            background: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 20px;
        }

        .preview-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .preview-details {
            flex-grow: 1;
        }

        .preview-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 2px;
        }

        .preview-category {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 4px;
        }

        .preview-price {
            font-weight: 700;
            color: #667eea;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .preview-stock {
            font-size: 12px;
            font-weight: 600;
        }

        .preview-stock.in-stock {
            color: #198754;
        }

        .preview-stock.low-stock {
            color: #ffc107;
        }

        .preview-stock.out-of-stock {
            color: #dc3545;
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

        .alert-option input:checked+.checkmark {
            background: #667eea;
            border-color: #667eea;
        }

        .alert-option input:checked+.checkmark::after {
            content: '✓';
            color: white;
            font-size: 12px;
        }

        .space-y-4>*+* {
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
