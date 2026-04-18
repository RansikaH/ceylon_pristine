@extends('admin.layout')
@section('title', 'Orders Management')
@section('content')
<div class="container-fluid py-4">
    <!-- Page Header with Stats -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Orders Management</h1>
            <p class="text-muted mb-0">Track and manage all customer orders</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary btn-modern">
                <i class="bi bi-download me-2"></i>Export
            </button>
            <button type="button" class="btn btn-primary btn-modern">
                <i class="bi bi-plus-circle me-2"></i>New Order
            </button>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card shadow-sm mb-4 search-card">
        <div class="card-body">
            <form method="get" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Search Orders</label>
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" 
                               name="search" 
                               class="form-control search-input" 
                               placeholder="Search by customer name or email..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Status Filter</label>
                    <select name="status" class="form-select form-select-modern">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-semibold">Date Range</label>
                    <select name="date_range" class="form-select form-select-modern">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-modern w-100">
                        <i class="bi bi-funnel me-2"></i>Apply Filters
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-modern w-100">
                        <i class="bi bi-arrow-clockwise me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow modern-card">
        <div class="card-header modern-card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="modern-card-title">
                    <i class="bi bi-cart-check me-2"></i>
                    All Orders ({{ $orders->total() }})
                </h6>
                <div class="d-flex gap-2">
                    <span class="badge bg-success modern-badge">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ $stats['completed_orders'] ?? 0 }} Completed
                    </span>
                    <span class="badge bg-info modern-badge">
                        <i class="bi bi-clock me-1"></i>
                        {{ $stats['processing_orders'] ?? 0 }} Processing
                    </span>
                    <span class="badge bg-warning modern-badge">
                        <i class="bi bi-exclamation-triangle me-1"></i>
                        {{ $stats['pending_orders'] ?? 0 }} Pending
                    </span>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table modern-table mb-0" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th class="modern-th">Order ID</th>
                            <th class="modern-th">Customer</th>
                            <th class="modern-th">Date</th>
                            <th class="modern-th">Items</th>
                            <th class="modern-th">Total</th>
                            <th class="modern-th">Status</th>
                            <th class="modern-th text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orders->count() > 0)
                            @foreach($orders as $order)
                            <tr class="modern-row">
                                <td class="modern-td">
                                    <div class="order-id-wrapper">
                                        <span class="order-id">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                        <span class="order-date-small">{{ $order->created_at->format('M d') }}</span>
                                    </div>
                                </td>
                                <td class="modern-td">
                                    <div class="customer-info">
                                        <div class="avatar-wrapper">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($order->customer_name) }}&background=667eea&color=fff&size=32" 
                                                 class="customer-avatar" 
                                                 alt="{{ $order->customer_name }}">
                                        </div>
                                        <div class="customer-details">
                                            <div class="customer-name">{{ $order->customer_name }}</div>
                                            <div class="customer-email">{{ $order->customer_email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="modern-td">
                                    <div class="date-info">
                                        <div class="date-main">{{ $order->created_at->format('M d, Y') }}</div>
                                        <div class="time-small">{{ $order->created_at->format('h:i A') }}</div>
                                    </div>
                                </td>
                                <td class="modern-td">
                                    <span class="items-count">
                                        <i class="bi bi-box me-1"></i>
                                        {{ count($order->items ?? []) }} items
                                    </span>
                                </td>
                                <td class="modern-td">
                                    <div class="price-wrapper">
                                        <span class="currency">LKR</span>
                                        <span class="price-amount">{{ number_format($order->total, 2) }}</span>
                                    </div>
                                </td>
                                <td class="modern-td">
                                    <span class="status-badge status-{{ $order->status }}">
                                        <i class="bi bi-circle-fill me-1 status-dot"></i>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="modern-td text-center">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.orders.show', $order) }}" 
                                           class="btn btn-sm btn-primary btn-action"
                                           title="View Order">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-success btn-action"
                                                title="Update Status"
                                                onclick="quickUpdateStatus({{ $order->id }})">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-info btn-action"
                                                title="Print Order">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="bi bi-cart-x empty-icon"></i>
                                        <h5 class="empty-title">No Orders Found</h5>
                                        <p class="empty-text">Try adjusting your search or filter criteria</p>
                                        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary btn-modern">
                                            <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="d-flex justify-content-between align-items-center mt-4 pagination-wrapper">
        <div class="pagination-info text-muted">
            Showing <strong>{{ $orders->firstItem() }}</strong> to <strong>{{ $orders->lastItem() }}</strong> of <strong>{{ $orders->total() }}</strong> entries
        </div>
        {{ $orders->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

@push('scripts')
<script>
function quickUpdateStatus(orderId) {
    // Quick status update modal
    Swal.fire({
        title: 'Update Order Status',
        html: `
            <select id="quickStatus" class="form-select form-select-lg mb-3">
                <option value="pending">Pending</option>
                <option value="processing">Processing</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#667eea',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Update Status',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const status = document.getElementById('quickStatus').value;
            if (!status) {
                Swal.showValidationMessage('Please select a status');
                return false;
            }
            return status;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Send update request
            fetch(`/admin/orders/${orderId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    status: result.value,
                    note: 'Quick status update via admin panel'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Status Updated!',
                        text: 'Order status has been updated successfully.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message || 'Failed to update status', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Network Error', 'Failed to connect to server', 'error');
            });
        }
    });
}
</script>

<!-- SweetAlert2 -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

<style>
/* Modern Button Styles */
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

/* Search Card */
.search-card {
    border-radius: 15px;
    border: none;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.search-input-wrapper {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    z-index: 10;
}

.search-input {
    padding-left: 45px;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.search-input:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-select-modern {
    border-radius: 10px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-select-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
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

.modern-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Modern Table */
.modern-table {
    background: white;
}

.modern-table thead th {
    background: #f8f9fa;
    border: none;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1px;
    color: #495057;
    padding: 1rem;
}

.modern-row {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f1f3f5;
}

.modern-row:hover {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    transform: scale(1.01);
}

.modern-row:last-child {
    border-bottom: none;
}

.modern-td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
}

/* Order ID Styles */
.order-id-wrapper {
    display: flex;
    flex-direction: column;
}

.order-id {
    font-weight: 700;
    font-size: 14px;
    color: #667eea;
}

.order-date-small {
    font-size: 11px;
    color: #6c757d;
    margin-top: 2px;
}

/* Customer Info */
.customer-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.customer-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #f1f3f5;
}

.customer-name {
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
}

.customer-email {
    font-size: 12px;
    color: #6c757d;
    margin-top: 2px;
}

/* Date Info */
.date-main {
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
}

.time-small {
    font-size: 11px;
    color: #6c757d;
    margin-top: 2px;
}

/* Items Count */
.items-count {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    background: #f8f9fa;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
    color: #495057;
}

/* Price Wrapper */
.price-wrapper {
    display: flex;
    align-items: baseline;
    gap: 2px;
}

.currency {
    font-size: 12px;
    color: #6c757d;
    font-weight: 500;
}

.price-amount {
    font-weight: 700;
    font-size: 16px;
    color: #2c3e50;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-processing {
    background: #cfe2ff;
    color: #084298;
}

.status-completed {
    background: #d1e7dd;
    color: #0f5132;
}

.status-cancelled {
    background: #f8d7da;
    color: #842029;
}

.status-dot {
    font-size: 8px;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
}

.btn-action {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.btn-action:hover {
    transform: scale(1.1);
}

/* Empty State */
.empty-state {
    padding: 3rem 2rem;
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-title {
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #6c757d;
    margin-bottom: 1.5rem;
}

/* Pagination Wrapper */
.pagination-wrapper {
    padding: 1rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.pagination-info {
    font-size: 14px;
}

/* Responsive */
@media (max-width: 768px) {
    .customer-email, .time-small {
        display: none;
    }
    
    .action-buttons {
        flex-direction: column;
        gap: 4px;
    }
}
</style>
@endsection
