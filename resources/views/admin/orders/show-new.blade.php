@extends('admin.layout')
@section('title', 'Order Details #' . $order->id)
@section('content')
<div class="container-fluid py-4">
    <!-- Order Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>
            <p class="text-muted mb-0">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-info" onclick="printOrder({{ $order->id }})">
                <i class="bi bi-printer me-2"></i>Print
            </button>
            <button type="button" class="btn btn-outline-success" onclick="updateStatus({{ $order->id }})">
                <i class="bi bi-arrow-repeat me-2"></i>Update Status
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Order Status & Payment -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Status</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @include('admin.orders.partials.status-badge', ['status' => $order->status])
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Payment Method</label>
                        <span class="badge bg-light text-dark">{{ $order->payment_method_display }}</span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Order Total</label>
                        <h4 class="text-primary">LKR {{ number_format($order->total, 2) }}</h4>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar me-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->customer_name) }}&background=4e73df&color=fff" class="rounded-circle" style="width: 60px; height: 60px;">
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $order->customer_name }}</h6>
                            <p class="text-muted mb-0">{{ $order->customer_email }}</p>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-bold">Phone</label>
                        <p class="mb-0">{{ $order->customer_phone }}</p>
                    </div>
                    @if($order->user)
                    <div class="mb-2">
                        <label class="form-label fw-bold">Account</label>
                        <p class="mb-0">
                            <a href="{{ route('admin.customers.show', $order->user) }}" class="text-primary">
                                View Customer Profile
                            </a>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items & Address -->
        <div class="col-lg-8">
            <!-- Order Items -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Items ({{ count($order->items ?? []) }})</h6>
                </div>
                <div class="card-body">
                    @if($order->items && count($order->items) > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item['image'] ?? false)
                                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded me-3">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item['name'] ?? 'Unknown Product' }}</h6>
                                                    <small class="text-muted">{{ $item['category'] ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item['quantity'] ?? 1 }}</td>
                                        <td>LKR {{ number_format($item['price'] ?? 0, 2) }}</td>
                                        <td><strong>LKR {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                        <td><strong>LKR {{ number_format($order->total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                            No items found for this order
                        </div>
                    @endif
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Shipping Address</h6>
                </div>
                <div class="card-body">
                    @if($order->address_line_1 || $order->city)
                        <div class="mb-2">
                            <label class="form-label fw-bold">Delivery Address</label>
                            <p class="mb-0">
                                {{ $order->address_line_1 }}<br>
                                @if($order->address_line_2){{ $order->address_line_2 }}<br>@endif
                                @if($order->city){{ $order->city }}<br>@endif
                                @if($order->district){{ $order->district }}<br>@endif
                                @if($order->postal_code){{ $order->postal_code }}@endif
                            </p>
                        </div>
                    @else
                        <p class="text-muted mb-0">No shipping address provided</p>
                    @endif
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Order Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Order Placed</h6>
                                <small class="text-muted">{{ $order->created_at->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                        </div>
                        @if($order->updated_at != $order->created_at)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Last Updated</h6>
                                <small class="text-muted">{{ $order->updated_at->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                        </div>
                        @endif
                        @if($order->status == 'completed')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Order Completed</h6>
                                <small class="text-muted">{{ $order->updated_at->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
@include('admin.orders.partials.status-modal')

@push('scripts')
<script>
function updateStatus(orderId) {
    // Load current status and show update modal
    fetch(`/admin/orders/${orderId}/status`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('updateOrderId').value = orderId;
            document.getElementById('currentStatus').value = data.current_status;
            document.querySelector(`input[name="status"][value="${data.current_status}"]`).checked = true;
            new bootstrap.Modal(document.getElementById('statusUpdateModal')).show();
        })
        .catch(error => console.error('Error:', error));
}

function printOrder(orderId) {
    window.open(`/admin/orders/${orderId}/print`, '_blank');
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -23px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e3e6f0;
}

.timeline-content h6 {
    font-size: 0.9rem;
    font-weight: 600;
    color: #5a5c69;
}
</style>
@endpush
@endsection
