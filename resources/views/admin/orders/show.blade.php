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
            <select id="statusSelect" class="form-select" style="width: 150px;">
                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="button" class="btn btn-success" onclick="updateStatus({{ $order->id }})">
                <i class="bi bi-check-lg me-2"></i>Update Status
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
                        <span class="badge bg-info">{{ ucfirst($order->status) }}</span>
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
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Order Items ({{ count($order->items ?? []) }})</h6>
                    <button type="button" class="btn btn-primary btn-sm" onclick="saveQuantities({{ $order->id }})">
                        <i class="bi bi-save me-2"></i>Save Quantities
                    </button>
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
                                        <th>Discount</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item['image'] ?? false)
                                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" style="width: 50px; height: 50px; object-fit: cover;" class="rounded me-3" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiBmaWxsPSIjRjVGNUY1Ii8+CjxwYXRoIGQ9Ik0yNSAxNUMyNSAyMCAyMCAyNSAyMCAyNUMyMCAzMCAyNSAzNSAyNSAzNUMzMCAzNSAzNSAzMCAzNSAyNUMzNSAyNSAzMCAyMCAyNSAxNVoiIGZpbGw9IiNEMUQ1REIiLz4KPGNpcmNsZSBjeD0iMjUiIGN5PSIyMSIgcj0iMiIgZmlsbD0iI0QxRDVEQiIvPgo8L3N2Zz4K'">
                                                @else
                                                    <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNTAiIGhlaWdodD0iNTAiIHZpZXdCb3g9IjAgMCA1MCA1MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjUwIiBoZWlnaHQ9IjUwIiBmaWxsPSIjRjVGNUY1Ii8+CjxwYXRoIGQ9Ik0yNSAxNUMyNSAyMCAyMCAyNSAyMCAyNUMyMCAzMCAyNSAzNSAyNSAzNUMzMCAzNSAzNSAzMCAzNSAyNUMzNSAyNSAzMCAyMCAyNSAxNVoiIGZpbGw9IiNEMUQ1REIiLz4KPGNpcmNsZSBjeD0iMjUiIGN5PSIyMSIgcj0iMiIgZmlsbD0iI0QxRDVEQiIvPgo8L3N2Zz4K" alt="Default Product" style="width: 50px; height: 50px; object-fit: cover;" class="rounded me-3">
                                                @endif
                                                <div>
                                                    <h6 class="mb-0">{{ $item['name'] ?? 'Unknown Product' }}</h6>
                                                    <small class="text-muted">{{ $item['category'] ?? '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="quantity-control">
                                                <button type="button" 
                                                        class="quantity-btn-modern decrement" 
                                                        onclick="updateQuantity({{ $loop->index }}, -1)">
                                                    <i class="bi bi-dash-lg"></i>
                                                </button>
                                                <div class="quantity-display">
                                                    <input type="number" 
                                                           id="quantity-{{ $loop->index }}" 
                                                           class="quantity-input-modern" 
                                                           value="{{ $item['quantity'] ?? 1 }}" 
                                                           min="0.1" 
                                                           onchange="calculateItemTotal({{ $loop->index }})"
                                                           data-price="{{ $item['discounted_price'] ?? $item['price'] ?? 0 }}">
                                                    <label class="quantity-label">QTY</label>
                                                </div>
                                                <button type="button" 
                                                        class="quantity-btn-modern increment" 
                                                        onclick="updateQuantity({{ $loop->index }}, 1)">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                @if(isset($item['discounted_price']) && $item['discounted_price'] < ($item['price'] ?? 0))
                                                    <span class="text-muted text-decoration-line-through">LKR {{ number_format($item['price'] ?? 0, 2) }}</span>
                                                    <br>
                                                    <span class="text-success fw-bold">LKR {{ number_format($item['discounted_price'], 2) }}</span>
                                                @else
                                                    LKR {{ number_format($item['price'] ?? 0, 2) }}
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if(isset($item['discounted_price']) && $item['discounted_price'] < ($item['price'] ?? 0))
                                                <span class="badge bg-success">
                                                    LKR {{ number_format(($item['price'] ?? 0) - $item['discounted_price'], 2) }}
                                                    ({{ round((($item['price'] ?? 0) - $item['discounted_price']) / ($item['price'] ?? 1) * 100, 1) }}%)
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td><strong id="total-{{ $loop->index }}">LKR {{ number_format(($item['discounted_price'] ?? $item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Subtotal:</strong></td>
                                        <td><strong id="subtotal">LKR {{ number_format($order->total, 2) }}</strong></td>
                                    </tr>
                                    @php
                                        $totalOriginal = collect($order->items ?? [])->sum(function($item) {
                                            return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                                        });
                                        $totalDiscounted = collect($order->items ?? [])->sum(function($item) {
                                            return ($item['discounted_price'] ?? $item['price'] ?? 0) * ($item['quantity'] ?? 1);
                                        });
                                        $totalSavings = $totalOriginal - $totalDiscounted;
                                    @endphp
                                    @if($totalSavings > 0)
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Total Savings:</strong></td>
                                        <td><strong class="text-success">LKR {{ number_format($totalSavings, 2) }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Original Total:</strong></td>
                                        <td><span class="text-muted text-decoration-line-through">LKR {{ number_format($totalOriginal, 2) }}</span></td>
                                    </tr>
                                    @endif
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

            <!-- Status History Timeline -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Status History</h6>
                </div>
                <div class="card-body">
                    @if($order->statusHistory && $order->statusHistory->count() > 0)
                        <div class="timeline">
                            @foreach($order->statusHistory as $history)
                                <div class="timeline-item">
                                    <div class="timeline-marker status-{{ $history->new_status }}">
                                        <i class="bi bi-{{ $history->new_status == 'completed' ? 'check-circle' : ($history->new_status == 'cancelled' ? 'x-circle' : ($history->new_status == 'processing' ? 'gear' : 'clock')) }}"></i>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1">
                                                    {{ $history->old_status ? $history->old_status_label : 'New Order' }}
                                                    <i class="bi bi-arrow-right mx-2 text-muted"></i>
                                                    {{ $history->new_status_label }}
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="bi bi-clock me-1"></i>
                                                    {{ $history->created_at->format('M d, Y \a\t h:i A') }}
                                                </small>
                                            </div>
                                            <span class="badge bg-{{ $history->new_status == 'completed' ? 'success' : ($history->new_status == 'cancelled' ? 'danger' : ($history->new_status == 'processing' ? 'info' : 'warning')) }}">
                                                {{ $history->new_status_label }}
                                            </span>
                                        </div>
                                        @if($history->notes)
                                            <div class="alert alert-light border-start border-4 border-primary ps-3 mb-0">
                                                <small class="text-dark">{{ $history->notes }}</small>
                                            </div>
                                        @endif
                                        @if($history->changedBy)
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="bi bi-person me-1"></i>
                                                    Changed by: {{ $history->changedBy->name ?? 'Unknown User' }}
                                                </small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-clock-history text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-3 mb-0">No status changes recorded yet</p>
                            <small class="text-muted">Status history will appear here when the order status is updated</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Quantity management functions
function updateQuantity(itemIndex, change) {
    const quantityInput = document.getElementById(`quantity-${itemIndex}`);
    let currentValue = parseFloat(quantityInput.value);
    let newValue = currentValue + change;
    
    // Don't allow quantity less than 0.1
    if (newValue < 0.1) {
        newValue = 0.1;
    }
    
    // Round to 1 decimal place
    newValue = Math.round(newValue * 10) / 10;
    
    quantityInput.value = newValue;
    
    // Add animation class
    quantityInput.classList.add('updating');
    setTimeout(() => {
        quantityInput.classList.remove('updating');
    }, 300);
    
    calculateItemTotal(itemIndex);
    calculateSubtotal();
}

function calculateItemTotal(itemIndex) {
    const quantityInput = document.getElementById(`quantity-${itemIndex}`);
    const price = parseFloat(quantityInput.dataset.price);
    const quantity = parseFloat(quantityInput.value);
    const total = price * quantity;
    
    document.getElementById(`total-${itemIndex}`).textContent = `LKR ${total.toFixed(2)}`;
}

function calculateSubtotal() {
    const quantityInputs = document.querySelectorAll('[id^="quantity-"]');
    let subtotal = 0;
    
    quantityInputs.forEach(input => {
        const price = parseFloat(input.dataset.price);
        const quantity = parseFloat(input.value);
        subtotal += price * quantity;
    });
    
    document.getElementById('subtotal').textContent = `LKR ${subtotal.toFixed(2)}`;
}

function saveQuantities(orderId) {
    // Collect all quantities
    const quantityInputs = document.querySelectorAll('[id^="quantity-"]');
    const items = [];
    
    quantityInputs.forEach((input, index) => {
        items.push({
            index: index,
            quantity: parseFloat(input.value)
        });
    });
    
    // Show confirmation
    Swal.fire({
        title: 'Save Quantity Changes?',
        text: 'This will update the order items quantities. Are you sure?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, save changes!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Saving...',
                text: 'Please wait while we save the quantity changes.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send the update request (you'll need to create this endpoint)
            fetch(`/admin/orders/${orderId}/update-quantities`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    items: items
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Quantities Updated!',
                        text: 'Order quantities have been updated successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload the page to show updated order
                        location.reload();
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: data.message || 'Failed to update quantities. Please try again.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Failed to connect to the server. Please check your connection and try again.'
                });
            });
        }
    });
}

function updateStatus(orderId) {
    const newStatus = document.getElementById('statusSelect').value;
    
    // Confirm with user before updating
    Swal.fire({
        title: 'Update Order Status?',
        text: `Are you sure you want to change the status to "${newStatus}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Updating...',
                text: 'Please wait while we update the order status.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send the update request
            fetch(`/admin/orders/${orderId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: newStatus,
                    note: `Status updated to ${newStatus} via admin panel`
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated!',
                        text: 'Order status has been updated successfully.',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Reload the page to show updated status
                        location.reload();
                    });
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: data.message || 'Failed to update order status. Please try again.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Network Error',
                    text: 'Failed to connect to the server. Please check your connection and try again.'
                });
            });
        }
    });
}
</script>
@endpush
<div class="mt-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left me-2"></i>Back to Orders
    </a>
</div>
@endsection

<style>
/* Modern Quantity Control Design */
.quantity-control {
    display: flex;
    align-items: center;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    border-radius: 50px;
    padding: 4px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.quantity-control:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.quantity-btn-modern {
    width: 40px;
    height: 40px;
    border: none;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 18px;
    box-shadow: 0 2px 10px rgba(102, 126, 234, 0.3);
}

.quantity-btn-modern:hover {
    transform: scale(1.1) rotate(10deg);
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.5);
}

.quantity-btn-modern:active {
    transform: scale(0.95) rotate(-5deg);
}

.quantity-btn-modern.decrement {
    background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
    box-shadow: 0 2px 10px rgba(255, 107, 107, 0.3);
}

.quantity-btn-modern.decrement:hover {
    box-shadow: 0 4px 20px rgba(255, 107, 107, 0.5);
}

.quantity-display {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin: 0 15px;
    position: relative;
}

.quantity-input-modern {
    width: 60px;
    height: 35px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.9);
    text-align: center;
    font-weight: 700;
    font-size: 16px;
    color: #2c3e50;
    transition: all 0.3s ease;
    outline: none;
    box-shadow: inset 0 2px 5px rgba(0, 0, 0, 0.1);
}

.quantity-input-modern:focus {
    background: white;
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1), inset 0 2px 5px rgba(0, 0, 0, 0.1);
    transform: scale(1.05);
}

.quantity-input-modern::-webkit-inner-spin-button,
.quantity-input-modern::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quantity-label {
    font-size: 8px;
    font-weight: 800;
    color: #667eea;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 3px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

/* Enhanced Table Styling */
.table-borderless {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
}

.table-borderless td {
    vertical-align: middle;
    padding: 1.5rem 1rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.table-borderless tr:last-child td {
    border-bottom: none;
}

.table-borderless tr:hover td {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.02) 0%, rgba(118, 75, 162, 0.02) 100%);
}

/* Modern Card Header */
.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 15px 15px 0 0 !important;
    padding: 1.25rem 1.5rem;
    position: relative;
    overflow: hidden;
}

.card-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    transition: left 0.5s ease;
}

.card-header:hover::before {
    left: 100%;
}

.card-header h6 {
    margin: 0;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 14px;
}

/* Enhanced Save Button */
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    font-weight: 700;
    transition: all 0.3s ease;
    padding: 10px 20px;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.3s ease, height 0.3s ease;
}

.btn-primary:hover::before {
    width: 300px;
    height: 300px;
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-primary:active {
    transform: translateY(-1px);
}

/* Product Image Enhancements */
.table img {
    transition: all 0.3s ease;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.table img:hover {
    transform: scale(1.08) rotate(2deg);
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
}

/* Total Column Styling */
.table td:nth-child(5) {
    font-weight: 700;
    color: #667eea;
    font-size: 16px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

/* Discount Column Styling */
.table td:nth-child(4) .badge {
    font-size: 11px;
    font-weight: 600;
    padding: 6px 10px;
    border-radius: 20px;
    background: linear-gradient(135deg, #28a745, #20c997) !important;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

/* Price Column Styling */
.table td:nth-child(3) {
    min-width: 120px;
}

.table td:nth-child(3) .text-decoration-line-through {
    font-size: 13px;
}

.table td:nth-child(3) .text-success {
    font-size: 15px;
    font-weight: 600;
}

/* Responsive Design */
@media (max-width: 768px) {
    .quantity-control {
        transform: scale(0.9);
    }
    
    .quantity-btn-modern {
        width: 35px;
        height: 35px;
        font-size: 16px;
    }
    
    .quantity-input-modern {
        width: 50px;
        height: 30px;
        font-size: 14px;
    }
    
    .quantity-display {
        margin: 0 10px;
    }
}

/* Animation for quantity changes */
@keyframes quantityUpdate {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.quantity-input-modern.updating {
    animation: quantityUpdate 0.3s ease;
}

/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 40px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #e9ecef, #dee2e6);
}

.timeline-item {
    position: relative;
    margin-bottom: 25px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    color: white;
    border: 2px solid white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 1;
}

.timeline-marker.status-pending {
    background: linear-gradient(135deg, #ffc107, #ffb300);
}

.timeline-marker.status-processing {
    background: linear-gradient(135deg, #17a2b8, #138496);
}

.timeline-marker.status-completed {
    background: linear-gradient(135deg, #28a745, #218838);
}

.timeline-marker.status-cancelled {
    background: linear-gradient(135deg, #dc3545, #c82333);
}

.timeline-content {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    border-left: 3px solid #e9ecef;
    transition: all 0.3s ease;
}

.timeline-content:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.timeline-item:first-child .timeline-content {
    border-left-color: #667eea;
}

.timeline-content h6 {
    color: #2c3e50;
    font-weight: 600;
    font-size: 14px;
}

.timeline-content .badge {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>
