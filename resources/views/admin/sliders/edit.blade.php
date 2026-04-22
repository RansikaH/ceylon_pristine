@extends('admin.layout')

@section('title', 'Edit Slider')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-0">Edit Slider</h1>
                    <p class="text-muted mb-0">Update slider banner details</p>
                </div>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Sliders
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Slider Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.sliders.update', $slider) }}" method="POST" enctype="multipart/form-data" id="updateSliderForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="main_topic" class="form-label fw-medium">Main Topic <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="main_topic" name="main_topic" value="{{ old('main_topic', $slider->main_topic) }}" required maxlength="255">
                                    <div class="form-text">The main heading of the slider (e.g., "Experience the Freshness of Nature")</div>
                                    @error('main_topic')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label fw-medium">Description <span class="text-danger">*</span></label>
                                    <div class="mb-2">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <button type="button" class="btn btn-outline-secondary" onclick="formatText('bold')"><i class="bi bi-type-bold"></i></button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="formatText('italic')"><i class="bi bi-type-italic"></i></button>
                                            <button type="button" class="btn btn-outline-secondary" onclick="formatText('underline')"><i class="bi bi-type-underline"></i></button>
                                            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                                <i class="bi bi-palette"></i> Color
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="#" onclick="formatText('foreColor', '#000000')"><span style="display: inline-block; width: 20px; height: 20px; background: #000000; border: 1px solid #ccc;"></span> Black</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="formatText('foreColor', '#50946c')"><span style="display: inline-block; width: 20px; height: 20px; background: #50946c; border: 1px solid #ccc;"></span> Green</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="formatText('foreColor', '#dc3545')"><span style="display: inline-block; width: 20px; height: 20px; background: #dc3545; border: 1px solid #ccc;"></span> Red</a></li>
                                                <li><a class="dropdown-item" href="#" onclick="formatText('foreColor', '#007bff')"><span style="display: inline-block; width: 20px; height: 20px; background: #007bff; border: 1px solid #ccc;"></span> Blue</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $slider->description) }}</textarea>
                                    <div class="form-text">Detailed description of the slider content. Use the formatting tools to style your text.</div>
                                    @error('description')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="subtopic" class="form-label fw-medium">Subtopic</label>
                                    <input type="text" class="form-control" id="subtopic" name="subtopic" value="{{ old('subtopic', $slider->subtopic) }}" maxlength="255">
                                    <div class="form-text">Optional subheading below the main topic</div>
                                    @error('subtopic')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="button_text" class="form-label fw-medium">Button Text <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="button_text" name="button_text" value="{{ old('button_text', $slider->button_text) }}" required maxlength="100">
                                            <div class="form-text">Text displayed on the call-to-action button</div>
                                            @error('button_text')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="button_url" class="form-label fw-medium">Button URL <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="button_url" name="button_url" value="{{ old('button_url', $slider->button_url) }}" required maxlength="255">
                                            <div class="form-text">URL where the button should navigate to (e.g., /shop/full)</div>
                                            @error('button_url')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="image" class="form-label fw-medium">Slider Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <div class="form-text">Leave empty to keep current image. Recommended size: 1200x600px. Max file size: 2MB</div>
                                    @error('image')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="image_preview" class="form-label fw-medium">Current Image</label>
                                    <div id="image_preview" class="border rounded p-3 text-center bg-light" style="min-height: 200px;">
                                        <img src="{{ $slider->image_url }}" alt="Current Image" class="img-fluid rounded" style="max-height: 200px;">
                                        <p class="text-muted mt-2 mb-0 small">Current: {{ $slider->image }}</p>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="sort_order" class="form-label fw-medium">Sort Order</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" value="{{ old('sort_order', $slider->sort_order) }}" min="0" max="3">
                                    <div class="form-text">Order of display (0 = first, 3 = last)</div>
                                    @error('sort_order')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            <strong>Active</strong>
                                        </label>
                                        <div class="form-text">Enable this slider to display on homepage</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <a href="{{ route('admin.sliders.index') }}" class="btn btn-outline-secondary">
                                            <i class="bi bi-x-circle me-2"></i>Cancel
                                        </a>
                                    </div>
                                    <button type="submit" form="updateSliderForm" class="btn btn-success">
                                        <i class="bi bi-check-circle me-2"></i>Update Slider
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete form moved outside the main form -->
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this slider?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-trash me-2"></i>Delete Slider
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image_preview');

    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="img-fluid rounded" style="max-height: 200px;">
                    <p class="text-muted mt-2 mb-0 small">New: ${file.name}</p>
                `;
            };
            reader.readAsDataURL(file);
        }
    });

    // Rich text editor functionality
    const description = document.getElementById('description');
    description.style.minHeight = '150px';
});

function formatText(command, value = null) {
    document.execCommand(command, false, value);
    document.getElementById('description').focus();
}

// Prevent dropdown from closing when clicking formatting buttons
document.addEventListener('DOMContentLoaded', function() {
    const dropdownMenus = document.querySelectorAll('.dropdown-menu');
    dropdownMenus.forEach(menu => {
        menu.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    });
});
</script>
@endpush
