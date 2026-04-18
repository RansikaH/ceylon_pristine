@extends('layouts.app')
@section('title', 'My Orders')
@section('content')
<div class="orders-modern">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Page Header -->
                <div class="orders-header mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="orders-header-icon">
                                <i class="bi bi-bag-check"></i>
                            </div>
                            <div>
                                <h1 class="orders-title mb-1">{{ __('My Orders') }}</h1>
                                <p class="orders-subtitle mb-0">Track and manage all your orders</p>
                            </div>
                        </div>
                        @if(!$orders->isEmpty())
                        <div class="orders-count-badge">
                            <span class="count-number">{{ $orders->count() }}</span>
                            <span class="count-label">{{ $orders->count() === 1 ? 'Order' : 'Orders' }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="orders-card">
                    @if($orders->isEmpty())
                        <div class="orders-empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-cart-x"></i>
                            </div>
                            <h4 class="empty-state-title">{{ __('No Orders Yet') }}</h4>
                            <p class="empty-state-text">{{ __('You haven\'t placed any orders yet. Start shopping to see your orders here!') }}</p>
                            <a href="{{ route('shop.home') }}" class="btn-shop-now">
                                <i class="bi bi-cart-plus me-2"></i>{{ __('Start Shopping') }}
                            </a>
                        </div>
                    @else
                        <div class="orders-table-wrapper">
                            <table class="orders-table">
                                <thead>
                                    <tr>
                                        <th class="ps-4">{{ __('Order ID') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Total') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th class="text-end pe-4">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
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
                                            <a href="{{ route('my.orders.show', $order) }}" class="btn-view-order">
                                                <i class="bi bi-eye me-1"></i>{{ __('View Details') }}
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
/* Orders Modern Styles */
.orders-modern {
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
    padding-top: 2rem;
    padding-bottom: 2rem;
}

/* Page Header */
.orders-header {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.orders-header-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 36px;
    margin-right: 1.5rem;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
}

.orders-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
}

.orders-subtitle {
    color: #6c757d;
    font-size: 1rem;
}

.orders-count-badge {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1rem 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
}

.count-number {
    font-size: 2rem;
    font-weight: 700;
    color: white;
    line-height: 1;
}

.count-label {
    font-size: 0.75rem;
    color: rgba(255, 255, 255, 0.9);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-top: 0.25rem;
}

/* Orders Card */
.orders-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    overflow: hidden;
}

/* Empty State */
.orders-empty-state {
    text-align: center;
    padding: 5rem 2rem;
}

.empty-state-icon {
    width: 120px;
    height: 120px;
    margin: 0 auto 2rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 60px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.empty-state-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.empty-state-text {
    color: #6c757d;
    font-size: 1.125rem;
    margin-bottom: 2.5rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

.btn-shop-now {
    display: inline-flex;
    align-items: center;
    padding: 1rem 2.5rem;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1.125rem;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-shop-now:hover {
    background: linear-gradient(135deg, #218838 0%, #1aa179 100%);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
    color: white;
}

/* Orders Table */
.orders-table-wrapper {
    overflow-x: auto;
}

.orders-table {
    width: 100%;
    margin: 0;
}

.orders-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.orders-table thead th {
    color: white;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.75rem;
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
    transform: scale(1.005);
}

.orders-table tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
}

.order-id {
    font-weight: 700;
    color: #667eea;
    font-size: 1rem;
}

.order-date {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9375rem;
}

.order-total {
    font-weight: 700;
    color: #28a745;
    font-size: 1.125rem;
}

/* Status Badges */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.75rem;
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

/* View Order Button */
.btn-view-order {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1.25rem;
    background: transparent;
    color: #667eea;
    border: 2px solid #667eea;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-view-order:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    transform: translateX(5px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-view-order i {
    transition: transform 0.3s ease;
}

.btn-view-order:hover i {
    transform: translateX(3px);
}

/* Responsive Design */
@media (max-width: 768px) {
    .orders-modern {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    .orders-header {
        padding: 1.5rem;
    }
    
    .orders-header .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .orders-header-icon {
        width: 60px;
        height: 60px;
        font-size: 30px;
        margin-right: 1rem;
    }
    
    .orders-title {
        font-size: 1.5rem;
    }
    
    .orders-count-badge {
        align-self: flex-start;
        padding: 0.75rem 1.25rem;
    }
    
    .count-number {
        font-size: 1.5rem;
    }
    
    .empty-state-icon {
        width: 100px;
        height: 100px;
        font-size: 50px;
    }
    
    .empty-state-title {
        font-size: 1.5rem;
    }
    
    .empty-state-text {
        font-size: 1rem;
    }
    
    .btn-shop-now {
        padding: 0.875rem 2rem;
        font-size: 1rem;
    }
    
    .orders-table thead th {
        font-size: 0.7rem;
        padding: 1rem 0.75rem;
    }
    
    .orders-table tbody td {
        padding: 1rem 0.75rem;
        font-size: 0.875rem;
    }
    
    .order-id {
        font-size: 0.875rem;
    }
    
    .order-date {
        font-size: 0.875rem;
    }
    
    .order-total {
        font-size: 1rem;
    }
    
    .btn-view-order {
        padding: 0.4rem 1rem;
        font-size: 0.8rem;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.orders-header,
.orders-card {
    animation: fadeInUp 0.5s ease-out;
}

.orders-card {
    animation-delay: 0.1s;
}

.order-row {
    animation: fadeInUp 0.3s ease-out;
}

.order-row:nth-child(1) { animation-delay: 0.05s; }
.order-row:nth-child(2) { animation-delay: 0.1s; }
.order-row:nth-child(3) { animation-delay: 0.15s; }
.order-row:nth-child(4) { animation-delay: 0.2s; }
.order-row:nth-child(5) { animation-delay: 0.25s; }
</style>
@endpush
