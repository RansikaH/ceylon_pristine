@extends('admin.layout')

@section('title', 'Customer Details - Customer Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Customer Details</h1>
            <p class="text-muted mb-0">View detailed information and order history for {{ $user->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-info btn-modern" onclick="sendMessage()">
                <i class="bi bi-envelope me-2"></i>Send Message
            </button>
            <a href="{{ route('admin.customers') }}" class="btn btn-outline-secondary btn-modern">
                <i class="bi bi-arrow-left me-2"></i>Back to Customers
            </a>
        </div>
    </div>

    <!-- Customer Profile Header -->
    <div class="card shadow-lg profile-header mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <div class="customer-avatar-large">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="customer-info">
                        <h2 class="customer-name-large">{{ $user->name }}</h2>
                        <div class="customer-meta">
                            <span class="meta-badge">
                                <i class="bi bi-person-badge me-2"></i>ID: #{{ str_pad($user->id, 6, '0', STR_PAD_LEFT) }}
                            </span>
                            <span class="meta-badge">
                                <i class="bi bi-calendar-event me-2"></i>Member since {{ $user->created_at->format('M Y') }}
                            </span>
                            <span class="meta-badge status-active">
                                <i class="bi bi-check-circle me-2"></i>{{ $user->orders->count() > 0 ? 'Active' : 'New' }} Customer
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="customer-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $user->orders->count() }}</div>
                            <div class="stat-label">Total Orders</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">LKR {{ number_format($user->orders->sum('total'), 0) }}</div>
                            <div class="stat-label">Total Spent</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Information -->
        <div class="col-lg-4">
            <!-- Contact Information Card -->
            <div class="card shadow modern-card mb-4">
                <div class="card-header modern-card-header">
                    <h6 class="modern-card-title">
                        <i class="bi bi-person-lines-fill me-2"></i>Contact Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="info-group">
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Email Address</div>
                                <div class="info-value">{{ $user->email }}</div>
                            </div>
                        </div>
                        
                        @if($user->phone)
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value">{{ $user->phone }}</div>
                            </div>
                        </div>
                        @else
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-telephone-x"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Phone Number</div>
                                <div class="info-value text-muted">Not provided</div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-calendar-check"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Member Since</div>
                                <div class="info-value">{{ $user->created_at->format('F j, Y') }}</div>
                                <div class="info-sub">{{ $user->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Last Active</div>
                                <div class="info-value">{{ $user->updated_at->format('M d, Y') }}</div>
                                <div class="info-sub">{{ $user->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="action-buttons-vert">
                        <button type="button" class="btn btn-primary btn-modern w-100 mb-2" onclick="sendMessage()">
                            <i class="bi bi-envelope me-2"></i>Send Message
                        </button>
                        <button type="button" class="btn btn-outline-info btn-modern w-100 mb-2" onclick="viewActivity()">
                            <i class="bi bi-graph-up me-2"></i>View Activity
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-modern w-100" onclick="editCustomer()">
                            <i class="bi bi-pencil me-2"></i>Edit Customer
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Card -->
            <div class="card shadow modern-card mb-4">
                <div class="card-header modern-card-header">
                    <h6 class="modern-card-title">
                        <i class="bi bi-speedometer2 me-2"></i>Quick Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="stats-grid-mini">
                        <div class="stat-box">
                            <div class="stat-icon-mini bg-primary">
                                <i class="bi bi-cart-check"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value-mini">{{ $user->orders->count() }}</div>
                                <div class="stat-label-mini">Orders</div>
                            </div>
                        </div>
                        
                        <div class="stat-box">
                            <div class="stat-icon-mini bg-success">
                                <i class="bi bi-currency-dollar"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value-mini">LKR {{ number_format($user->orders->sum('total'), 0) }}</div>
                                <div class="stat-label-mini">Spent</div>
                            </div>
                        </div>
                        
                        <div class="stat-box">
                            <div class="stat-icon-mini bg-info">
                                <i class="bi bi-bag-check"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value-mini">{{ $user->orders->where('status', 'completed')->count() }}</div>
                                <div class="stat-label-mini">Completed</div>
                            </div>
                        </div>
                        
                        <div class="stat-box">
                            <div class="stat-icon-mini bg-warning">
                                <i class="bi bi-star"></i>
                            </div>
                            <div class="stat-info">
                                <div class="stat-value-mini">{{ number_format($user->orders->avg('total') ?? 0, 0) }}</div>
                                <div class="stat-label-mini">Avg Order</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card shadow modern-card mb-4">
                <div class="card-header modern-card-header">
                    <h6 class="modern-card-title">
                        <i class="bi bi-bag-fill me-2"></i>Recent Orders
                    </h6>
                    <div class="d-flex gap-2">
                        <span class="badge bg-white text-dark">
                            <i class="bi bi-cart me-1"></i>{{ $user->orders->count() }} Orders
                        </span>
                        <span class="badge bg-white text-dark">
                            <i class="bi bi-currency-dollar me-1"></i>LKR {{ number_format($user->orders->sum('total'), 0) }}
                        </span>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($user->orders->count() > 0)
                        <div class="table-responsive-modern">
                            <table class="table table-hover table-modern">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders as $order)
                                        <tr class="order-row" data-order-id="{{ $order->id }}">
                                            <td>
                                                <div class="order-id-info">
                                                    <div class="order-number">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="date-info">
                                                    <div class="date-main">{{ $order->created_at->format('M d, Y') }}</div>
                                                    <div class="date-sub">{{ $order->created_at->diffForHumans() }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="order-status-badge">
                                                    @switch($order->status)
                                                        @case('pending')
                                                            <span class="status-badge pending">
                                                                <i class="bi bi-clock me-1"></i>Pending
                                                            </span>
                                                            @break
                                                        @case('processing')
                                                            <span class="status-badge processing">
                                                                <i class="bi bi-gear me-1"></i>Processing
                                                            </span>
                                                            @break
                                                        @case('completed')
                                                            <span class="status-badge completed">
                                                                <i class="bi bi-check-circle me-1"></i>Completed
                                                            </span>
                                                            @break
                                                        @case('cancelled')
                                                            <span class="status-badge cancelled">
                                                                <i class="bi bi-x-circle me-1"></i>Cancelled
                                                            </span>
                                                            @break
                                                        @default
                                                            <span class="status-badge default">
                                                                <i class="bi bi-question-circle me-1"></i>{{ ucfirst($order->status) }}
                                                            </span>
                                                    @endswitch
                                                </div>
                                            </td>
                                            <td>
                                                <div class="items-count">
                                                    <i class="bi bi-box me-1"></i>{{ count($order->items ?? []) }} items
                                                </div>
                                            </td>
                                            <td>
                                                <div class="order-amount">
                                                    <div class="amount-main">LKR {{ number_format($order->total, 2) }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                                       class="action-btn view-btn" 
                                                       title="View Order Details">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="action-btn track-btn" 
                                                            onclick="trackOrder('{{ $order->id }}')"
                                                            title="Track Order">
                                                        <i class="bi bi-truck"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-content">
                                <i class="bi bi-bag-x empty-icon"></i>
                                <h6 class="empty-title">No Orders Found</h6>
                                <p class="empty-text">This customer hasn't placed any orders yet.</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Old modal removed - now using SweetAlert2 -->

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function sendMessage() {
    Swal.fire({
        title: 'Send Message to {{ $user->name }}',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label fw-bold">Message Title</label>
                    <input type="text" id="messageTitle" class="form-control" placeholder="e.g., Order Update">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Message Content</label>
                    <textarea id="messageContent" class="form-control" rows="4" placeholder="Enter your message here..."></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Message Type</label>
                    <select id="messageType" class="form-select">
                        <option value="info">Info (Blue)</option>
                        <option value="success">Success (Green)</option>
                        <option value="warning">Warning (Yellow)</option>
                        <option value="error">Error (Red)</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Link URL (Optional)</label>
                    <input type="url" id="messageUrl" class="form-control" placeholder="https://example.com/page">
                </div>
            </div>
        `,
        width: '600px',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-send me-2"></i>Send Message',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#667eea',
        cancelButtonColor: '#6c757d',
        preConfirm: () => {
            const title = document.getElementById('messageTitle').value.trim();
            const content = document.getElementById('messageContent').value.trim();
            const type = document.getElementById('messageType').value;
            const url = document.getElementById('messageUrl').value.trim();
            
            if (!title) {
                Swal.showValidationMessage('Please enter a message title');
                return false;
            }
            
            if (!content) {
                Swal.showValidationMessage('Please enter message content');
                return false;
            }
            
            if (!type) {
                Swal.showValidationMessage('Please select a message type');
                return false;
            }
            
            return { title, content, type, url };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Sending...',
                text: 'Please wait while we send your message',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Send the message via AJAX
            fetch('/admin/customers/{{ $user->id }}/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    title: result.value.title,
                    message: result.value.content,
                    type: result.value.type,
                    url: result.value.url
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw err;
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Message Sent!',
                        text: 'Your message has been sent to {{ $user->name }}',
                        confirmButtonColor: '#28a745'
                    });
                } else {
                    throw new Error(data.message || 'Failed to send message');
                }
            })
            .catch(error => {
                let errorMessage = 'Failed to send message. Please try again.';
                
                // Handle validation errors
                if (error.errors) {
                    const errors = Object.values(error.errors).flat();
                    errorMessage = errors.join('<br>');
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: errorMessage,
                    confirmButtonColor: '#dc3545'
                });
            });
        }
    });
}

function viewActivity() {
    // Implementation for viewing customer activity
    console.log('View activity for customer: {{ $user->id }}');
}

function editCustomer() {
    // Implementation for editing customer
    console.log('Edit customer: {{ $user->id }}');
}

function trackOrder(orderId) {
    // Implementation for tracking order
    console.log('Track order:', orderId);
}

// Row hover effects for orders
document.addEventListener('DOMContentLoaded', function() {
    const orderRows = document.querySelectorAll('.order-row');
    orderRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
            this.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
            this.style.boxShadow = 'none';
        });
    });
});
</script>
@endpush

<style>
/* Additional styles for message modal */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 15px 15px 0 0;
    border: none;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
}

.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 13px;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b4699 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary:disabled {
    background: #6c757d;
    transform: none;
    box-shadow: none;
}

.btn-secondary {
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 13px;
}

.form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

/* Profile Header */
.profile-header {
    border-radius: 15px;
    border: none;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.customer-avatar-large {
    width: 120px;
    height: 120px;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 48px;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    margin: 0 auto;
}

.customer-name-large {
    font-size: 32px;
    font-weight: 700;
    margin-bottom: 1rem;
    color: white;
}

.customer-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.meta-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
}

.meta-badge.status-active {
    background: rgba(25, 135, 84, 0.2);
}

.customer-stats {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    text-align: center;
}

.stat-item {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 1rem;
    border-radius: 15px;
}

.stat-number {
    font-size: 24px;
    font-weight: 700;
    color: white;
    margin-bottom: 4px;
}

.stat-label {
    font-size: 12px;
    color: rgba(255, 255, 255, 0.8);
    text-transform: uppercase;
    letter-spacing: 0.5px;
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
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modern-card-title {
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 14px;
    margin: 0;
}

/* Info Group */
.info-group {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.info-item {
    display: flex;
    align-items: start;
    gap: 15px;
}

.info-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    color: #667eea;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}

.info-content {
    flex-grow: 1;
}

.info-label {
    font-size: 12px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.info-value {
    font-size: 15px;
    font-weight: 600;
    color: #2c3e50;
}

.info-sub {
    font-size: 12px;
    color: #6c757d;
    margin-top: 2px;
}

/* Action Buttons */
.action-buttons-vert {
    display: flex;
    flex-direction: column;
    gap: 10px;
    margin-top: 2rem;
}

/* Stats Grid Mini */
.stats-grid-mini {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.stat-box {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.stat-box:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    transform: translateY(-2px);
}

.stat-icon-mini {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}

.stat-info {
    flex-grow: 1;
}

.stat-value-mini {
    font-size: 16px;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1;
}

.stat-label-mini {
    font-size: 11px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 2px;
}

/* Modern Table */
.table-responsive-modern {
    overflow-x: auto;
}

.table-modern {
    margin-bottom: 0;
    border-collapse: separate;
    border-spacing: 0;
}

.table-modern th {
    background: #f8f9fa;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 12px;
    color: #495057;
    padding: 1rem;
    border-bottom: 2px solid #e9ecef;
}

.table-modern td {
    padding: 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #f1f3f5;
}

.order-row {
    transition: all 0.3s ease;
}

.order-row:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
}

/* Order Info */
.order-id-info {
    font-weight: 600;
    color: #2c3e50;
}

.date-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.date-main {
    font-size: 13px;
    font-weight: 600;
    color: #2c3e50;
}

.date-sub {
    font-size: 11px;
    color: #6c757d;
}

.items-count {
    font-size: 13px;
    color: #495057;
}

.order-amount {
    font-weight: 700;
    color: #667eea;
}

/* Status Badge */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.pending {
    background: rgba(255, 193, 7, 0.1);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
}

.status-badge.processing {
    background: rgba(13, 110, 253, 0.1);
    color: #0d6efd;
    border: 1px solid rgba(13, 110, 253, 0.2);
}

.status-badge.completed {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.2);
}

.status-badge.cancelled {
    background: rgba(220, 53, 69, 0.1);
    color: #dc3545;
    border: 1px solid rgba(220, 53, 69, 0.2);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 35px;
    height: 35px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
    color: white;
    text-decoration: none;
}

.action-btn.view-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.action-btn.view-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.action-btn.track-btn {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.action-btn.track-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.4);
}

/* Empty State */
.empty-state {
    background: #f8f9fa;
}

.empty-content {
    padding: 4rem 2rem;
    text-align: center;
}

.empty-icon {
    font-size: 4rem;
    color: #dee2e6;
    margin-bottom: 1rem;
}

.empty-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.empty-text {
    color: #6c757d;
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

.btn-outline-info.btn-modern {
    background: white;
    border: 2px solid #17a2b8;
    color: #17a2b8;
}

.btn-outline-info.btn-modern:hover {
    border-color: #17a2b8;
    background: #17a2b8;
    color: white;
}

.btn-outline-warning.btn-modern {
    background: white;
    border: 2px solid #ffc107;
    color: #ffc107;
}

.btn-outline-warning.btn-modern:hover {
    border-color: #ffc107;
    background: #ffc107;
    color: white;
}
</style>
@endsection
