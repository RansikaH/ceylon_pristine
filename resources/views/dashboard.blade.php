@extends('layouts.app')
@section('content')

<!-- Notification Popup Script -->
@if($unreadNotifications->isNotEmpty())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notifications = @json($unreadNotifications);
        
        // Show popup for each unread notification
        notifications.forEach((notification, index) => {
            setTimeout(() => {
                let title = 'New Message';
                let message = 'You have a new notification';
                let icon = 'info';
                
                // Parse notification data
                if (notification.data) {
                    title = notification.data.title || notification.data.subject || 'New Message';
                    message = notification.data.message || notification.data.body || 'You have a new notification';
                    icon = notification.data.type || notification.data.icon || 'info';
                }
                
                Swal.fire({
                    icon: icon,
                    title: title,
                    html: message,
                    showConfirmButton: true,
                    confirmButtonText: 'Mark as Read',
                    confirmButtonColor: '#50946c',
                    showCancelButton: true,
                    cancelButtonText: 'Close',
                    cancelButtonColor: '#6c757d',
                    allowOutsideClick: false,
                    customClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Mark notification as read
                        fetch(`/notifications/${notification.id}/mark-as-read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        }).then(response => {
                            if (response.ok) {
                                showToast('Notification marked as read', 'success');
                                // Reload after a short delay
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        });
                    }
                });
            }, index * 500); // Stagger notifications by 500ms
        });
    });
</script>
@endif
<div class="dashboard-modern">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
            <!-- Welcome Header -->
            <div class="welcome-header mb-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-5 fw-bold mb-2 gradient-text">Welcome back, {{ $user->name }}! 👋</h1>
                        <p class="lead text-muted mb-0">Here's what's happening with your account today.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="date-badge">
                            <i class="bi bi-calendar3 me-2"></i>{{ now()->format('l, F j, Y') }}
                        </div>
                    </div>
                </div>
            </div>

            @if($unreadNotifications->isNotEmpty())
            <div class="alert alert-modern alert-info alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <div class="alert-icon">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong>You have {{ $unreadNotifications->count() }} unread message(s)!</strong>
                        <p class="mb-0 small">Check your notifications to stay updated.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif
            
            <!-- Quick Action Cards -->
            <div class="row g-4 mb-5">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="action-card card-profile h-100">
                        <div class="card-icon-wrapper">
                            <div class="card-icon">
                                <i class="bi bi-person-circle"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <h5 class="card-title">Profile</h5>
                            <p class="card-text">Keep your contact and password up to date for a secure experience.</p>
                            <a href="{{ route('profile.edit') }}" class="btn-modern btn-profile">
                                <span>Edit Profile</span>
                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="action-card card-orders h-100">
                        <div class="card-icon-wrapper">
                            <div class="card-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <h5 class="card-title">Orders</h5>
                            <p class="card-text">Track and manage all your recent and past orders.</p>
                            <a href="{{ route('my.orders.index') }}" class="btn-modern btn-orders">
                                <span>My Orders</span>
                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="action-card card-shop h-100">
                        <div class="card-icon-wrapper">
                            <div class="card-icon">
                                <i class="bi bi-cart4"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <h5 class="card-title">Shop</h5>
                            <p class="card-text">Browse our latest products and special offers.</p>
                            <a href="{{ route('shop.full') }}" class="btn-modern btn-shop">
                                <span>Shop Now</span>
                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="action-card card-chat h-100 position-relative">
                        <div class="card-icon-wrapper">
                            <div class="card-icon">
                                <i class="bi bi-chat-dots-fill"></i>
                            </div>
                        </div>
                        <div class="card-content">
                            <h5 class="card-title">Chat with Admin</h5>
                            <p class="card-text">Get help and support from our admin team.</p>
                            <a href="{{ route('chat.index') }}" class="btn-modern btn-chat position-relative">
                                <span id="chat-unread-badge" class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-danger d-none">
                                    <span id="chat-unread-count">0</span>
                                    <span class="visually-hidden">unread messages</span>
                                </span>
                                <span>Start Chat</span>
                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Orders Section -->
            <div class="orders-section">
                <div class="section-header mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="section-title mb-1">Recent Orders</h3>
                            <p class="section-subtitle mb-0">Track your latest purchases</p>
                        </div>
                        <a href="{{ route('orders.index') }}" class="btn-view-all">
                            View All <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
                
                @if($orders->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="bi bi-bag-x"></i>
                        </div>
                        <h4 class="empty-state-title">No Orders Yet</h4>
                        <p class="empty-state-text">You haven't placed any orders yet. Start shopping to see your orders here!</p>
                        <a href="{{ route('shop.full') }}" class="btn-empty-action">
                            <i class="bi bi-cart-plus me-2"></i>Start Shopping
                        </a>
                    </div>
                @else
                    <div class="orders-table-card">
                        <div class="table-responsive">
                            <table class="table orders-table mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-4">Order ID</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders->take(3) as $order)
                                    <tr class="order-row">
                                        <td class="ps-4">
                                            <span class="order-id">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td>
                                            <div class="order-date">
                                                <i class="bi bi-calendar3 me-2"></i>{{ $order->created_at->format('d M Y') }}
                                            </div>
                                            <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <span class="order-total">LKR {{ number_format($order->total, 2) }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $statusConfig = match(strtolower($order->status)) {
                                                    'pending' => ['class' => 'status-pending', 'icon' => 'clock'],
                                                    'processing' => ['class' => 'status-processing', 'icon' => 'gear'],
                                                    'completed' => ['class' => 'status-completed', 'icon' => 'check-circle'],
                                                    'cancelled' => ['class' => 'status-cancelled', 'icon' => 'x-circle'],
                                                    default => ['class' => 'status-default', 'icon' => 'question-circle']
                                                };
                                            @endphp
                                            <span class="status-badge {{ $statusConfig['class'] }}">
                                                <i class="bi bi-{{ $statusConfig['icon'] }} me-1"></i>{{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('orders.show', $order) }}" class="btn-order-details">
                                                View Details <i class="bi bi-arrow-right ms-1"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Dashboard Modern Styles */
.dashboard-modern {
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
    padding-top: 2rem;
    padding-bottom: 2rem;
}

/* Welcome Header */
.welcome-header {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    margin-bottom: 2rem;
}

.gradient-text {
    color: #2c3e50;
    font-size: 2rem;
}

.date-badge {
    display: inline-block;
    padding: 10px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.25);
}

/* Modern Alert */
.alert-modern {
    border: none;
    border-radius: 15px;
    padding: 1.5rem;
    background: white;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    border-left: 4px solid #0dcaf0;
}

.alert-modern .alert-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    margin-right: 1rem;
    flex-shrink: 0;
}

/* Action Cards */
.action-card {
    background: white;
    border-radius: 16px;
    padding: 2rem 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    position: relative;
    overflow: hidden;
    height: 100%;
}

.action-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    transition: all 0.3s ease;
}

.action-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    border-color: transparent;
}

.card-icon-wrapper {
    margin-bottom: 1.5rem;
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    position: relative;
    transition: all 0.3s ease;
}

.action-card:hover .card-icon {
    transform: scale(1.05);
}

/* Card Specific Gradients */
.card-profile::before {
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
}

.card-profile .card-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
}

.card-orders::before {
    background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
}

.card-orders .card-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
}

.card-shop::before {
    background: linear-gradient(90deg, #dc3545 0%, #c82333 100%);
}

.card-shop .card-icon {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
}

.card-chat::before {
    background: linear-gradient(90deg, #50946c 0%, #3d7556 100%);
}

.card-chat .card-icon {
    background: linear-gradient(135deg, #50946c 0%, #3d7556 100%);
    box-shadow: 0 4px 12px rgba(80, 148, 108, 0.25);
}

.card-content .card-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.card-content .card-text {
    color: #6c757d;
    font-size: 0.875rem;
    line-height: 1.5;
    margin-bottom: 1.25rem;
}

/* Modern Buttons */
.btn-modern {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    position: relative;
}

.btn-modern i {
    transition: transform 0.3s ease;
}

.btn-modern:hover i {
    transform: translateX(3px);
}

.btn-profile {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-profile:hover {
    background: linear-gradient(135deg, #5568d3 0%, #63408b 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-orders {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
}

.btn-orders:hover {
    background: linear-gradient(135deg, #218838 0%, #1aa179 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}

.btn-shop {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
}

.btn-shop:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.btn-chat {
    background: linear-gradient(135deg, #50946c 0%, #3d7556 100%);
    color: white;
}

.btn-chat:hover {
    background: linear-gradient(135deg, #3d7556 0%, #2d5940 100%);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(80, 148, 108, 0.3);
}

/* Orders Section */
.orders-section {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.section-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2c3e50;
}

.section-subtitle {
    color: #6c757d;
    font-size: 0.95rem;
}

.btn-view-all {
    display: inline-flex;
    align-items: center;
    padding: 10px 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50px;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-view-all:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-view-all i {
    transition: transform 0.3s ease;
}

.btn-view-all:hover i {
    transform: translateX(5px);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 15px;
}

.empty-state-icon {
    width: 100px;
    height: 100px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 48px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.empty-state-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.75rem;
}

.empty-state-text {
    color: #6c757d;
    font-size: 1rem;
    margin-bottom: 2rem;
}

.btn-empty-action {
    display: inline-flex;
    align-items: center;
    padding: 14px 32px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-radius: 50px;
    font-weight: 600;
    font-size: 16px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-empty-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    color: white;
}

/* Orders Table */
.orders-table-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
}

.orders-table {
    margin-bottom: 0;
}

.orders-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.orders-table thead th {
    color: white;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1px;
    padding: 1.25rem 1rem;
    border: none;
}

.orders-table tbody .order-row {
    transition: all 0.3s ease;
    border-bottom: 1px solid #f0f0f0;
}

.orders-table tbody .order-row:hover {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.03) 0%, rgba(118, 75, 162, 0.03) 100%);
    transform: scale(1.01);
}

.orders-table tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
}

.order-id {
    font-weight: 700;
    color: #667eea;
    font-size: 15px;
}

.order-date {
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
}

.order-total {
    font-weight: 700;
    color: #28a745;
    font-size: 16px;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 50px;
    font-weight: 600;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
}

.status-processing {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
}

.status-completed {
    background: linear-gradient(135deg, #28a745 0%, #218838 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.status-cancelled {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.status-default {
    background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
}

.btn-order-details {
    display: inline-flex;
    align-items: center;
    padding: 8px 20px;
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
    border-radius: 50px;
    font-weight: 600;
    font-size: 13px;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-order-details:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-order-details i {
    transition: transform 0.3s ease;
}

.btn-order-details:hover i {
    transform: translateX(3px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .welcome-header {
        padding: 1.5rem;
    }
    
    .gradient-text {
        font-size: 1.75rem;
    }
    
    .date-badge {
        margin-top: 1rem;
        font-size: 12px;
        padding: 10px 20px;
    }
    
    .action-card {
        padding: 1.5rem;
    }
    
    .card-icon {
        width: 60px;
        height: 60px;
        font-size: 28px;
    }
    
    .orders-section {
        padding: 1.5rem;
    }
    
    .section-title {
        font-size: 1.5rem;
    }
    
    .btn-view-all {
        font-size: 12px;
        padding: 8px 16px;
    }
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.action-card,
.welcome-header,
.orders-section {
    animation: fadeInUp 0.6s ease-out;
}

.action-card:nth-child(1) { animation-delay: 0.1s; }
.action-card:nth-child(2) { animation-delay: 0.2s; }
.action-card:nth-child(3) { animation-delay: 0.3s; }
.action-card:nth-child(4) { animation-delay: 0.4s; }
</style>
@endpush

<script>
// Check for unread chat messages
document.addEventListener('DOMContentLoaded', function() {
    function updateChatUnreadCount() {
        fetch('{{ route("chat.unread-count") }}')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('chat-unread-badge');
                const count = document.getElementById('chat-unread-count');
                
                if (data.count > 0) {
                    count.textContent = data.count;
                    badge.classList.remove('d-none');
                } else {
                    badge.classList.add('d-none');
                }
            })
            .catch(error => {
                console.error('Error checking unread messages:', error);
            });
    }
    
    // Check immediately and then every 30 seconds
    updateChatUnreadCount();
    setInterval(updateChatUnreadCount, 30000);
});
</script>



