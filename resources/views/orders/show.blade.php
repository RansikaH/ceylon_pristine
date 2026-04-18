@extends('layouts.app')
@section('title', 'Order #'.$order->id)
@section('content')
<div class="order-show-modern">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Page Header -->
                <div class="order-show-header mb-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="order-show-header-icon">
                                <i class="bi bi-receipt"></i>
                            </div>
                            <div>
                                <h1 class="order-show-title mb-1">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>
                                <p class="order-show-subtitle mb-0">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>
                        <a href="{{ route('my.orders.index') }}" class="btn-back-orders">
                            <i class="bi bi-arrow-left me-2"></i>Back to Orders
                        </a>
                    </div>
                </div>

                <div class="order-show-card">
                    <div class="order-show-body">
                        <!-- Order Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-6 mb-4 mb-md-0">
                                <div class="info-card">
                                    <div class="info-card-header">
                                        <div class="info-card-icon order-info-icon">
                                            <i class="bi bi-info-circle"></i>
                                        </div>
                                        <h5 class="info-card-title">{{ __('Order Information') }}</h5>
                                    </div>
                                    <div class="info-card-body">
                                        <div class="info-item">
                                            <span class="info-label">{{ __('Date') }}</span>
                                            <span class="info-value">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">{{ __('Status') }}</span>
                                            @php
                                                $statusConfig = match(strtolower($order->status)) {
                                                    'pending' => ['class' => 'status-pending', 'icon' => 'clock'],
                                                    'processing' => ['class' => 'status-processing', 'icon' => 'gear'],
                                                    'completed' => ['class' => 'status-completed', 'icon' => 'check-circle'],
                                                    'cancelled' => ['class' => 'status-cancelled', 'icon' => 'x-circle'],
                                                    default => ['class' => 'status-default', 'icon' => 'question-circle']
                                                };
                                            @endphp
                                            <span class="status-badge-large {{ $statusConfig['class'] }}">
                                                <i class="bi bi-{{ $statusConfig['icon'] }} me-1"></i>{{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">{{ __('Total') }}</span>
                                            <span class="info-value-highlight">LKR {{ number_format($order->total,2) }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">{{ __('Payment Method') }}</span>
                                            <span class="info-value">{{ $order->payment_method_display }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-card-header">
                                        <div class="info-card-icon customer-info-icon">
                                            <i class="bi bi-person-lines-fill"></i>
                                        </div>
                                        <h5 class="info-card-title">{{ __('Customer Details') }}</h5>
                                    </div>
                                    <div class="info-card-body">
                                        <div class="info-item">
                                            <span class="info-label">{{ __('Name') }}</span>
                                            <span class="info-value">{{ $order->customer_name }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="info-label">{{ __('Email') }}</span>
                                            <span class="info-value">{{ $order->customer_email }}</span>
                                        </div>
                                        @if($order->customer_phone)
                                            <div class="info-item">
                                                <span class="info-label">{{ __('Phone') }}</span>
                                                <span class="info-value">{{ $order->customer_phone }}</span>
                                            </div>
                                        @endif
                                        <div class="info-item">
                                            <span class="info-label">{{ __('Address') }}</span>
                                            <span class="info-value">{{ $order->full_address }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    
                        <!-- Order Items Section -->
                        <div class="order-items-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="bi bi-box-seam"></i>
                                </div>
                                <h5 class="section-title">{{ __('Order Items') }}</h5>
                            </div>
                            <div class="table-responsive">
                                <table class="order-items-table">
                                    <thead>
                                <tr>
                                    <th>{{ __('Product') }}</th>
                                    <th>{{ __('Unit Price') }}</th>
                                    <th>{{ __('Qty') }}</th>
                                    <th>{{ __('Discount') }}</th>
                                    <th class="text-end">{{ __('Subtotal') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalSavings = 0; @endphp
                                @foreach($order->items as $item)
                                @php
                                    $hasDiscount = isset($item['discount_percentage']) && $item['discount_percentage'] > 0;
                                    $unitPrice = $hasDiscount ? ($item['discounted_price'] ?? $item['price']) : $item['price'];
                                    $itemTotal = $unitPrice * $item['quantity'];
                                    if ($hasDiscount) {
                                        $savings = ($item['price'] - $unitPrice) * $item['quantity'];
                                        $totalSavings += $savings;
                                    }
                                @endphp
                                <tr>
                                    <td>
                                        {{ $item['name'] }}
                                        @if($hasDiscount)
                                            <span class="badge bg-danger small ms-1">{{ number_format($item['discount_percentage'], 0) }}% OFF</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($hasDiscount)
                                            <div class="text-muted text-decoration-line-through small">
                                                LKR {{ number_format($item['price'], 2) }}
                                            </div>
                                            <div class="text-danger fw-semibold">
                                                LKR {{ number_format($unitPrice, 2) }}
                                            </div>
                                        @else
                                            LKR {{ number_format($item['price'], 2) }}
                                        @endif
                                    </td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>
                                        @if($hasDiscount)
                                            <span class="text-success small">
                                                -LKR {{ number_format($savings, 2) }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-medium">LKR {{ number_format($itemTotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                @if($totalSavings > 0)
                                <tr class="table-success">
                                    <td colspan="4" class="text-end"><strong>{{ __('Total Savings') }}:</strong></td>
                                    <td class="text-end"><strong class="text-success">LKR {{ number_format($totalSavings, 2) }}</strong></td>
                                </tr>
                                @endif
                                <tr>
                                    <td colspan="4" class="text-end"><strong>{{ __('Grand Total') }}:</strong></td>
                                    <td class="text-end"><strong class="text-primary">LKR {{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                                </table>
                            </div>
                        </div>
                    
                        <!-- Status History Timeline -->
                        <div class="status-history-section">
                            <div class="section-header">
                                <div class="section-icon">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <h5 class="section-title">{{ __('Order Status History') }}</h5>
                            </div>
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
                                                        {{ $history->old_status ? ucfirst($history->old_status) : 'New Order' }}
                                                        <i class="bi bi-arrow-right mx-2 text-muted"></i>
                                                        {{ ucfirst($history->new_status) }}
                                                    </h6>
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock me-1"></i>
                                                        {{ $history->created_at->format('M d, Y \a\t h:i A') }}
                                                    </small>
                                                </div>
                                                <span class="badge bg-{{ $history->new_status == 'completed' ? 'success' : ($history->new_status == 'cancelled' ? 'danger' : ($history->new_status == 'processing' ? 'info' : 'warning')) }}">
                                                    {{ ucfirst($history->new_status) }}
                                                </span>
                                            </div>
                                            @if($history->notes)
                                                <div class="border-start border-4 border-primary ps-3 mb-0" style="background-color: #f8f9fa; padding: 12px; border-radius: 8px;">
                                                    <small class="text-muted">{{ $history->notes }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="empty-history">
                                <div class="empty-history-icon">
                                    <i class="bi bi-clock-history"></i>
                                </div>
                                <p class="empty-history-title">{{ __('No status changes recorded yet') }}</p>
                                <small class="empty-history-text">{{ __('Status history will appear here when the order status is updated') }}</small>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
/* Order Show Modern Styles */
.order-show-modern {
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
    padding-top: 2rem;
    padding-bottom: 2rem;
}

/* Page Header */
.order-show-header {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.order-show-header-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 36px;
    margin-right: 1.5rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
}

.order-show-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
}

.order-show-subtitle {
    color: #6c757d;
    font-size: 1rem;
}

.btn-back-orders {
    display: inline-flex;
    align-items: center;
    padding: 0.75rem 1.5rem;
    background: white;
    color: #6c757d;
    border: 2px solid #dee2e6;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9375rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-back-orders:hover {
    background: #f8f9fa;
    color: #495057;
    border-color: #adb5bd;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Main Card */
.order-show-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.order-show-body {
    padding: 2.5rem;
}

/* Info Cards */
.info-card {
    background: #f8f9fa;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid #e9ecef;
    height: 100%;
}

.info-card-header {
    display: flex;
    align-items: center;
    padding: 1.25rem 1.5rem;
    background: white;
    border-bottom: 1px solid #e9ecef;
}

.info-card-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
    margin-right: 1rem;
}

.order-info-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
}

.customer-info-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
}

.info-card-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.info-card-body {
    padding: 1.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
}

.info-value {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9375rem;
    text-align: right;
}

.info-value-highlight {
    font-weight: 700;
    color: #28a745;
    font-size: 1.25rem;
}

/* Status Badges */
.status-badge-large {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    font-size: 0.875rem;
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

/* Section Headers */
.order-items-section,
.status-history-section {
    margin-top: 2.5rem;
    padding-top: 2.5rem;
    border-top: 2px solid #e9ecef;
}

.section-header {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-icon {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 22px;
    margin-right: 1rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

/* Order Items Table */
.order-items-table {
    width: 100%;
    margin: 0;
}

.order-items-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.order-items-table thead th {
    color: white;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 1px;
    padding: 1rem;
    border: none;
}

.order-items-table tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.3s ease;
}

.order-items-table tbody tr:hover {
    background: linear-gradient(90deg, rgba(102, 126, 234, 0.03) 0%, rgba(118, 75, 162, 0.03) 100%);
}

.order-items-table tbody td {
    padding: 1rem;
    vertical-align: middle;
}

.order-items-table tfoot {
    background: #f8f9fa;
    font-weight: 600;
}

.order-items-table tfoot td {
    padding: 1rem;
    border-top: 2px solid #dee2e6;
}

/* Timeline Styles */
.timeline {
    position: relative;
    padding-left: 40px;
    margin-top: 1.5rem;
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
    margin: 0;
}

.timeline-content .badge {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 0.375rem 0.75rem;
}

/* Empty History */
.empty-history {
    text-align: center;
    padding: 4rem 2rem;
    background: #f8f9fa;
    border-radius: 12px;
    margin-top: 1.5rem;
}

.empty-history-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 40px;
    box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
}

.empty-history-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.empty-history-text {
    color: #6c757d;
    font-size: 0.9375rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .order-show-modern {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    .order-show-header {
        padding: 1.5rem;
    }
    
    .order-show-header .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .order-show-header-icon {
        width: 60px;
        height: 60px;
        font-size: 30px;
        margin-right: 1rem;
    }
    
    .order-show-title {
        font-size: 1.5rem;
    }
    
    .btn-back-orders {
        align-self: flex-start;
    }
    
    .order-show-body {
        padding: 1.5rem;
    }
    
    .info-card-header {
        padding: 1rem;
    }
    
    .info-card-body {
        padding: 1rem;
    }
    
    .info-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
    
    .info-value,
    .info-value-highlight {
        text-align: left;
    }
    
    .order-items-table thead th,
    .order-items-table tbody td,
    .order-items-table tfoot td {
        padding: 0.75rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .timeline {
        padding-left: 40px;
    }
}

/* Animations */
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

.order-show-header,
.order-show-card {
    animation: fadeInUp 0.5s ease-out;
}

.order-show-card {
    animation-delay: 0.1s;
}

.info-card {
    animation: fadeInUp 0.5s ease-out;
}

.info-card:nth-child(1) { animation-delay: 0.2s; }
.info-card:nth-child(2) { animation-delay: 0.3s; }
</style>
@endpush

@endsection
