@extends('admin.layout')

@section('title', 'Manage Sliders')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold mb-0">Manage Sliders</h1>
                    <p class="text-muted mb-0">Manage homepage slider banners (Maximum 4 sliders)</p>
                </div>
                @if($sliders->count() < 4)
                    <a href="{{ route('admin.sliders.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle me-2"></i>Add New Slider
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if($sliders->count() >= 4)
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div>You have reached the maximum limit of 4 sliders. Please remove an existing slider to add a new one.</div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">All Sliders</h5>
                </div>
                <div class="card-body">
                    @if($sliders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="80">Image</th>
                                        <th>Main Topic</th>
                                        <th>Description</th>
                                        <th>Button Text</th>
                                        <th>Sort Order</th>
                                        <th>Status</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sliders-table">
                                    @foreach($sliders as $slider)
                                        <tr data-slider-id="{{ $slider->id }}" style="cursor: move;">
                                            <td>
                                                <img src="{{ $slider->image_url }}" alt="{{ $slider->main_topic }}" class="img-thumbnail" style="width: 60px; height: 40px; object-fit: cover;">
                                            </td>
                                            <td>
                                                <strong>{{ Str::limit($slider->main_topic, 50) }}</strong>
                                                @if($slider->subtopic)
                                                    <br><small class="text-muted">{{ $slider->subtopic }}</small>
                                                @endif
                                            </td>
                                            <td>{{ Str::limit(strip_tags($slider->description), 80) }}</td>
                                            <td>{{ $slider->button_text }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $slider->sort_order }}</span>
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.sliders.toggle', $slider) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $slider->is_active ? 'btn-success' : 'btn-secondary' }}">
                                                        {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <form action="{{ route('admin.sliders.destroy', $slider) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this slider?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bi bi-info-circle me-1"></i>
                                Drag and drop rows to reorder sliders. The order will be reflected on the homepage.
                            </small>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-images display-1 text-muted"></i>
                            <h4 class="mt-3 text-muted">No Sliders Found</h4>
                            <p class="text-muted">Create your first slider to display on the homepage.</p>
                            <a href="{{ route('admin.sliders.create') }}" class="btn btn-success">
                                <i class="bi bi-plus-circle me-2"></i>Create First Slider
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sortable for reordering
    const table = document.getElementById('sliders-table');
    if (table) {
        new Sortable(table, {
            animation: 150,
            handle: 'tr',
            onEnd: function(evt) {
                const order = Array.from(table.children).map(row => row.dataset.sliderId);
                
                fetch('{{ route("admin.sliders.reorder") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        order: order
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Show success message
                        const alert = document.createElement('div');
                        alert.className = 'alert alert-success alert-dismissible fade show';
                        alert.innerHTML = `
                            <i class="bi bi-check-circle-fill me-2"></i>
                            ${data.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        `;
                        document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.row'));
                        
                        // Auto dismiss after 3 seconds
                        setTimeout(() => {
                            alert.remove();
                        }, 3000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            }
        });
    }
});
</script>
@endpush
