@extends('admin.layout')

@section('title', 'Sales Report')

@push('styles')
<style>
    .report-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
        border-radius: 0.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    .stat-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    
    .chart-container {
        position: relative;
        height: 400px;
        margin: 2rem 0;
    }
    
    .table-responsive {
        border-radius: 0.5rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .badge-status {
        padding: 0.5em 0.75em;
        font-weight: 500;
        border-radius: 0.375rem;
    }
</style>
@endpush

@section('content')
<div class="container px-4 py-4">
    <!-- Report Header -->
    <div class="report-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="mb-3">
                    <i class="bi bi-currency-dollar me-2"></i>Sales Report
                </h1>
                <p class="mb-0 opacity-90">Comprehensive sales analysis with date range filtering</p>
            </div>
            <div class="col-md-6 text-md-end">
                <form method="GET" action="{{ route('admin.reports.sales') }}" class="d-inline-flex gap-2">
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control" required>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control" required>
                    <button type="submit" class="btn btn-light">
                        <i class="bi bi-funnel me-1"></i>Filter
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Total Revenue</h6>
                            <h3 class="mb-0">LKR {{ number_format($totalRevenue, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                            <i class="bi bi-cart-check"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Total Orders</h6>
                            <h3 class="mb-0">{{ number_format($totalOrders) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Avg Order Value</h6>
                            <h3 class="mb-0">LKR {{ number_format($avgOrderValue, 2) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Days Covered</h6>
                            <h3 class="mb-0">{{ \Carbon\Carbon::parse($startDate)->diffInDays(\Carbon\Carbon::parse($endDate)) + 1 }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Daily Sales Trend
                    </h6>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary" onclick="exportChart('dailySales')">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="dailySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-pie-chart me-2"></i>Order Status Breakdown
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="mt-3">
                        @foreach($statusBreakdown as $status)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge badge-status {{ $status->status == 'completed' ? 'bg-success' : ($status->status == 'processing' ? 'bg-warning' : 'bg-info') }}">
                                {{ ucfirst($status->status) }}
                            </span>
                            <small class="text-muted">{{ $status->count }} orders</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>Recent Orders
            </h6>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportTable()">
                    <i class="bi bi-file-earmark-excel me-1"></i>Export Excel
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                    <i class="bi bi-printer me-1"></i>Print
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="ordersTable">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>
                                <strong>#{{ $order->id }}</strong>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                        {{ substr($order->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $order->user->name }}</div>
                                        <small class="text-muted">{{ $order->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $order->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                            </td>
                            <td class="fw-semibold">LKR {{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge badge-status {{ $order->status == 'completed' ? 'bg-success' : ($order->status == 'processing' ? 'bg-warning' : 'bg-info') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ ucfirst($order->payment_method ?? 'cash') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="mt-2 mb-0 text-muted">No orders found for the selected period</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily Sales Chart
    const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
    const dailySalesData = @json($dailySales);
    
    new Chart(dailySalesCtx, {
        type: 'line',
        data: {
            labels: dailySalesData.map(item => new Date(item.date).toLocaleDateString()),
            datasets: [{
                label: 'Revenue (LKR)',
                data: dailySalesData.map(item => item.revenue),
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }, {
                label: 'Orders',
                data: dailySalesData.map(item => item.orders),
                borderColor: '#764ba2',
                backgroundColor: 'rgba(118, 75, 162, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Revenue (LKR)'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Orders'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
    
    // Status Breakdown Chart
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = @json($statusBreakdown);
    
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusData.map(item => ucfirst(item.status)),
            datasets: [{
                data: statusData.map(item => item.count),
                backgroundColor: [
                    '#1cc88a',
                    '#f6c23e',
                    '#36b9cc',
                    '#e74a3b'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});

function exportChart(chartId) {
    // Implementation for chart export
    alert('Chart export functionality would be implemented here');
}

function exportTable() {
    // Implementation for table export
    alert('Excel export functionality would be implemented here');
}
</script>
@endpush
