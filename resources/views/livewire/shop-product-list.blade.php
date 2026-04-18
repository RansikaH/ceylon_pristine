<div>
    <div class="container my-4">
        <div class="row g-2 align-items-end justify-content-between bg-white p-3 rounded shadow-sm mb-4">
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold mb-1"><i class="bi bi-funnel"></i> Category</label>
                <select wire:model="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-4">
                <label class="form-label fw-semibold mb-1"><i class="bi bi-search"></i> Search</label>
                <input type="text" wire:model.debounce.500ms="search" class="form-control" placeholder="Search products...">
            </div>
        </div>
    </div>
    <div class="container">
        <div class="alert alert-info mb-2">Selected Category: {{ $category ?? 'none' }}</div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="fw-bold mb-0">Shop Fresh Vegetables</h2>
            <span class="text-muted small">{{ $products->total() }} products</span>
        </div>
        <div class="row g-4">
            @forelse($products as $product)
    <div class="col-12 col-sm-6 col-md-3">
        @include('components.product-card', ['product' => $product])
    </div>
@empty
    <div class="col-12 col-sm-6 col-md-3">
        <div class="alert alert-warning">No products found.</div>
    </div>
@endforelse
        </div>
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>
    <style>
        .product-card:hover {
            box-shadow: 0 0.5rem 1.5rem rgba(40,167,69,.15) !important;
            transform: translateY(-4px) scale(1.03);
        }
    </style>
</div>
