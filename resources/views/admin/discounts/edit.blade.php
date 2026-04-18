@extends('admin.layout')

@section('title', 'Edit Discount')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-pencil me-2"></i>Edit Discount: {{ $discount->name }}
        </h1>
        <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Back to Discounts
        </a>
    </div>

    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Discount Details</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.discounts.update', $discount) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Discount Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $discount->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description', $discount->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Discount Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="percentage" {{ old('type', $discount->type) == 'percentage' ? 'selected' : '' }}>
                                            Percentage (%)
                                        </option>
                                        <option value="fixed" {{ old('type', $discount->type) == 'fixed' ? 'selected' : '' }}>
                                            Fixed Amount ($)
                                        </option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="value" class="form-label">Discount Value *</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                               id="value" name="value" value="{{ old('value', $discount->value) }}" 
                                               step="0.01" min="0" required>
                                        <span class="input-group-text" id="valueLabel">{{ $discount->type == 'percentage' ? '%' : '$' }}</span>
                                    </div>
                                    @error('value')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="applicable_to" class="form-label">Applicable To *</label>
                            <select class="form-select @error('applicable_to') is-invalid @enderror" 
                                    id="applicable_to" name="applicable_to" required>
                                <option value="">Select Applicability</option>
                                <option value="all" {{ old('applicable_to', $discount->applicable_to) == 'all' ? 'selected' : '' }}>
                                    All Products
                                </option>
                                <option value="specific_products" {{ old('applicable_to', $discount->applicable_to) == 'specific_products' ? 'selected' : '' }}>
                                    Specific Products
                                </option>
                                <option value="specific_categories" {{ old('applicable_to', $discount->applicable_to) == 'specific_categories' ? 'selected' : '' }}>
                                    Specific Categories
                                </option>
                            </select>
                            @error('applicable_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="productSelection" style="display: {{ old('applicable_to', $discount->applicable_to) == 'specific_products' ? 'block' : 'none' }};">
                            <label class="form-label">Select Products</label>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                @foreach($products as $product)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="product_ids[]" value="{{ $product->id }}"
                                               {{ in_array($product->id, old('product_ids', $discount->product_ids ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $product->name }} - ${{ number_format($product->price, 2) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('product_ids')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="categorySelection" style="display: {{ old('applicable_to', $discount->applicable_to) == 'specific_categories' ? 'block' : 'none' }};">
                            <label class="form-label">Select Categories</label>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                @foreach($categories as $category)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="category_ids[]" value="{{ $category->id }}"
                                               {{ in_array($category->id, old('category_ids', $discount->category_ids ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('category_ids')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="minimum_order_amount" class="form-label">Minimum Order Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('minimum_order_amount') is-invalid @enderror" 
                                       id="minimum_order_amount" name="minimum_order_amount" 
                                       value="{{ old('minimum_order_amount', $discount->minimum_order_amount) }}" step="0.01" min="0">
                            </div>
                            <small class="form-text text-muted">Leave empty for no minimum amount requirement</small>
                            @error('minimum_order_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="usage_limit" class="form-label">Usage Limit</label>
                            <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                   id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $discount->usage_limit) }}" min="1">
                            <small class="form-text text-muted">Leave empty for unlimited usage</small>
                            @error('usage_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Current Usage</label>
                            <div class="form-control-plaintext">
                                {{ $discount->used_count }} used
                                @if($discount->usage_limit)
                                    / {{ $discount->usage_limit }} limit
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="starts_at" class="form-label">Start Date</label>
                                    <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" 
                                           id="starts_at" name="starts_at" value="{{ old('starts_at', $discount->starts_at?->format('Y-m-d\TH:i')) }}">
                                    <small class="form-text text-muted">Leave empty to start immediately</small>
                                    @error('starts_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="expires_at" class="form-label">Expiry Date</label>
                                    <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                           id="expires_at" name="expires_at" value="{{ old('expires_at', $discount->expires_at?->format('Y-m-d\TH:i')) }}">
                                    <small class="form-text text-muted">Leave empty for no expiry</small>
                                    @error('expires_at')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" 
                                       id="is_active" {{ old('is_active', $discount->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Active</strong>
                                    <br><small class="text-muted">Enable this discount for use</small>
                                </label>
                            </div>
                        </div>

                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Discount Status</h6>
                                <div id="discountStatus">
                                    @if($discount->isActive())
                                        <span class="badge bg-success mb-2">Active</span>
                                    @else
                                        @if(!$discount->is_active)
                                            <span class="badge bg-danger mb-2">Disabled</span>
                                        @elseif($discount->starts_at && $discount->starts_at->isFuture())
                                            <span class="badge bg-warning mb-2">Upcoming</span>
                                        @elseif($discount->expires_at && $discount->expires_at->isPast())
                                            <span class="badge bg-secondary mb-2">Expired</span>
                                        @else
                                            <span class="badge bg-danger mb-2">Limit Reached</span>
                                        @endif
                                    @endif
                                    <br>
                                    <small class="text-muted">
                                        Created: {{ $discount->created_at->format('M j, Y g:i A') }}<br>
                                        Last Updated: {{ $discount->updated_at->format('M j, Y g:i A') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('admin.discounts.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i> Update Discount
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Handle applicability change
        $('#applicable_to').on('change', function() {
            var value = $(this).val();
            $('#productSelection, #categorySelection').hide();
            
            if (value === 'specific_products') {
                $('#productSelection').show();
            } else if (value === 'specific_categories') {
                $('#categorySelection').show();
            }
        });

        // Handle discount type change
        $('#type').on('change', function() {
            var value = $(this).val();
            $('#valueLabel').text(value === 'percentage' ? '%' : '$');
        });
    });
</script>
@endpush
