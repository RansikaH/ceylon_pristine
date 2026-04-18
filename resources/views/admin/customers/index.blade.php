@extends('admin.layout')

@section('title', 'Customers - Customer Management')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Customers Management</h1>
            <p class="text-muted mb-0">Manage your customer base and view detailed information</p>
        </div>
        <div class="d-flex gap-2">
            <!-- Export functionality can be added later -->
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary bg-gradient">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="ms-3">
                            <div class="stats-value">{{ $customers->total() }}</div>
                            <div class="stats-label">Total Customers</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-success bg-gradient">
                            <i class="bi bi-person-check"></i>
                        </div>
                        <div class="ms-3">
                            <div class="stats-value">{{ \App\Models\User::where('role', 'user')->whereDate('created_at', '>=', now()->subDays(30))->count() }}</div>
                            <div class="stats-label">New This Month</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-info bg-gradient">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <div class="ms-3">
                            <div class="stats-value">{{ \App\Models\Order::distinct('user_id')->count() }}</div>
                            <div class="stats-label">Active Buyers</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm stats-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-warning bg-gradient">
                            <i class="bi bi-star"></i>
                        </div>
                        <div class="ms-3">
                            <div class="stats-value">{{ number_format(\App\Models\User::where('role', 'user')->avg('id') ?? 0) }}</div>
                            <div class="stats-label">Avg. Orders</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.customers') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <div class="search-input-modern">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" 
                               name="search" 
                               class="form-control-modern" 
                               placeholder="Search by name, email, or phone..." 
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-control-modern">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="date_range" class="form-control-modern">
                        <option value="">All Time</option>
                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="quarter" {{ request('date_range') == 'quarter' ? 'selected' : '' }}>This Quarter</option>
                        <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary btn-modern w-100">
                        <i class="bi bi-funnel me-2"></i>Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.customers') }}" class="btn btn-outline-secondary btn-modern w-100">
                        <i class="bi bi-arrow-clockwise me-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Customers Table -->
    <div class="card shadow modern-card">
        <div class="card-header modern-card-header">
            <h6 class="modern-card-title">
                <i class="bi bi-people me-2"></i>Customer List
            </h6>
            <div class="d-flex gap-2">
                <span class="badge bg-white text-dark">
                    <i class="bi bi-person-check me-1"></i>{{ $customers->total() }} Total
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive-modern">
                <table class="table table-hover table-modern">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Contact Info</th>
                            <th>Registration</th>
                            <th>Orders</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($customers as $customer)
                            <tr class="customer-row" data-customer-id="{{ $customer->id }}">
                                <td>
                                    <div class="customer-info">
                                        <div class="customer-avatar">
                                            {{ strtoupper(substr($customer->name, 0, 2)) }}
                                        </div>
                                        <div class="customer-details">
                                            <div class="customer-name">{{ $customer->name }}</div>
                                            <div class="customer-id">ID: #{{ str_pad($customer->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="contact-info">
                                        <div class="contact-item">
                                            <i class="bi bi-envelope me-2"></i>
                                            <span>{{ $customer->email }}</span>
                                        </div>
                                        <div class="contact-item">
                                            <i class="bi bi-telephone me-2"></i>
                                            <span>{{ $customer->phone ?? 'Not provided' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="registration-info">
                                        <div class="date-info">
                                            <i class="bi bi-calendar-event me-2"></i>
                                            {{ $customer->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="time-info">
                                            {{ $customer->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="order-stats">
                                        <div class="order-count">
                                            {{ $customer->orders->count() }}
                                        </div>
                                        <div class="order-label">
                                            @if($customer->orders->count() > 0)
                                                Total Orders
                                            @else
                                                No Orders
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="customer-status">
                                        @if($customer->orders->count() > 0)
                                            <span class="status-badge active">
                                                <i class="bi bi-check-circle me-1"></i>Active
                                            </span>
                                        @else
                                            <span class="status-badge inactive">
                                                <i class="bi bi-circle me-1"></i>New
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.customers.show', $customer) }}" 
                                           class="action-btn view-btn" 
                                           title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="action-btn message-btn" 
                                                onclick="sendMessage('{{ $customer->id }}', '{{ $customer->name }}')"
                                                title="Send Message">
                                            <i class="bi bi-chat-dots"></i>
                                        </button>
                                        <button type="button" 
                                                class="action-btn more-btn" 
                                                onclick="showMoreOptions('{{ $customer->id }}')"
                                                title="More Options">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="empty-state">
                                <td colspan="6">
                                    <div class="empty-content">
                                        <i class="bi bi-people empty-icon"></i>
                                        <h6 class="empty-title">No Customers Found</h6>
                                        <p class="empty-text">
                                            @if(request('search') || request('status') || request('date_range'))
                                                Try adjusting your search or filters
                                            @else
                                                No customers have registered yet
                                            @endif
                                        </p>
                                        @if(request('search') || request('status') || request('date_range'))
                                            <a href="{{ route('admin.customers') }}" class="btn btn-outline-secondary btn-modern">
                                                <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if($customers->hasPages())
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pagination-info">
                        Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of {{ $customers->total() }} customers
                    </div>
                    <div>
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Customer management functions
function sendMessage(customerId, customerName) {
    Swal.fire({
        title: 'Send Message to ' + customerName,
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
            const payload = {
                title: result.value.title,
                message: result.value.content,
                type: result.value.type,
                url: result.value.url
            };
            
            console.log('Sending message to customer:', customerId);
            console.log('Payload:', payload);
            
            fetch('/admin/customers/' + customerId + '/send-message', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            })
            .then(response => {
                console.log('Response status:', response.status);
                if (!response.ok) {
                    return response.json().then(err => {
                        console.error('Error response:', err);
                        throw err;
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Success response:', data);
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Message Sent!',
                        text: 'Your message has been sent to ' + customerName,
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

function showMoreOptions(customerId) {
    // Implementation for more options
    console.log('Show more options for customer:', customerId);
}

// Search functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="search"]');
    
    searchInput.addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            this.closest('form').submit();
        }
    });
    
    // Row hover effects
    const customerRows = document.querySelectorAll('.customer-row');
    customerRows.forEach(row => {
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
/* Modern Cards */
.stats-card {
    border-radius: 15px;
    border: none;
    background: white;
    transition: all 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.stats-icon {
    width: 60px;
    height: 60px;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
}

.stats-value {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1;
}

.stats-label {
    font-size: 13px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-top: 4px;
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

/* Search Input */
.search-input-modern {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 16px;
    z-index: 10;
}

.form-control-modern {
    border: 2px solid #e9ecef;
    border-radius: 25px;
    padding: 10px 20px;
    padding-left: 45px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control-modern:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    background: white;
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

.customer-row {
    transition: all 0.3s ease;
}

.customer-row:hover {
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
}

/* Customer Info */
.customer-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.customer-avatar {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 16px;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.customer-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 2px;
}

.customer-id {
    font-size: 12px;
    color: #6c757d;
    font-family: 'Courier New', monospace;
}

/* Contact Info */
.contact-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.contact-item {
    display: flex;
    align-items: center;
    font-size: 13px;
    color: #495057;
}

.contact-item i {
    color: #6c757d;
    width: 16px;
}

/* Registration Info */
.registration-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.date-info {
    font-size: 13px;
    font-weight: 600;
    color: #2c3e50;
}

.time-info {
    font-size: 11px;
    color: #6c757d;
}

/* Order Stats */
.order-stats {
    text-align: center;
}

.order-count {
    font-size: 20px;
    font-weight: 700;
    color: #667eea;
}

.order-label {
    font-size: 11px;
    color: #6c757d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
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

.status-badge.active {
    background: rgba(25, 135, 84, 0.1);
    color: #198754;
    border: 1px solid rgba(25, 135, 84, 0.2);
}

.status-badge.inactive {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
    border: 1px solid rgba(108, 117, 125, 0.2);
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
}

.action-btn.view-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.action-btn.view-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.action-btn.message-btn {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}

.action-btn.message-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(23, 162, 184, 0.4);
}

.action-btn.more-btn {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
}

.action-btn.more-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.4);
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
    margin-bottom: 1.5rem;
}

/* Pagination */
.pagination-info {
    font-size: 14px;
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

.btn-outline-success.btn-modern {
    background: white;
    border: 2px solid #28a745;
    color: #28a745;
}

.btn-outline-success.btn-modern:hover {
    border-color: #28a745;
    background: #28a745;
    color: white;
}
</style>
@endsection
