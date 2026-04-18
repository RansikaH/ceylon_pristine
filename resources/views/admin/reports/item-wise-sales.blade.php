@extends('admin.layout')

@section('title', 'Item Wise Sales Report')

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
    
    .product-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .product-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }
    
    .stock-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .stock-high { background-color: #d1f2eb; color: #0e5f4e; }
    .stock-medium { background-color: #fff3cd; color: #856404; }
    .stock-low { background-color: #f8d7da; color: #721c24; }
</style>
@endpush

@section('content')
<div class="container px-4 py-4">
    <!-- Report Header -->
    <div class="report-header">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="mb-3">
                    <i class="bi bi-box-seam me-2"></i>Item Wise Sales Report
                </h1>
                <p class="mb-0 opacity-90">Detailed product performance analysis with sales metrics</p>
            </div>
            <div class="col-md-6 text-md-end">
                <form method="GET" action="{{ route('admin.reports.item-wise-sales') }}" class="d-inline-flex gap-2">
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
        <div class="col-md-4">
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
        
        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 text-success me-3">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Items Sold</h6>
                            <h3 class="mb-0">{{ number_format($totalQuantity) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                            <i class="bi bi-list-check"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Unique Products</h6>
                            <h3 class="mb-0">{{ count($itemSales) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products Chart -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>Top 10 Products by Revenue
                    </h6>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary" onclick="exportChart('topProducts')">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-pie-chart me-2"></i>Category Distribution
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>Product Sales Details
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
                <table class="table table-hover mb-0" id="productsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>SKU</th>
                            <th>Quantity Sold</th>
                            <th>Revenue</th>
                            <th>Orders</th>
                            <th>Current Stock</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($itemSales as $item)
                        <tr>
                            <td>
                                <div class="product-cell">
                                    <div class="product-icon">
                                        {{ substr($item['product_name'], 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $item['product_name'] }}</div>
                                        <small class="text-muted">ID: {{ $item['product_id'] }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $item['category'] }}</span>
                            </td>
                            <td>
                                <code>{{ $item['sku'] }}</code>
                            </td>
                            <td class="fw-semibold">{{ number_format($item['quantity']) }}</td>
                            <td class="fw-semibold text-success">LKR {{ number_format($item['revenue'], 2) }}</td>
                            <td>{{ number_format($item['orders']) }}</td>
                            <td>
                                <span class="stock-badge {{ $item['current_stock'] > 50 ? 'stock-high' : ($item['current_stock'] > 10 ? 'stock-medium' : 'stock-low') }}">
                                    {{ $item['current_stock'] }} units
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.products.edit', $item['product_id']) }}" class="btn btn-sm btn-outline-primary rounded-circle me-1" data-bs-toggle="tooltip" title="Edit Product">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="{{ route('admin.products.show', $item['product_id']) }}" class="btn btn-sm btn-outline-info rounded-circle" data-bs-toggle="tooltip" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="mt-2 mb-0 text-muted">No product sales found for the selected period</p>
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
    const itemSales = @json($itemSales);
    
    // Top Products Chart - Take first 10 items
    const topProducts = Object.values(itemSales).slice(0, 10);
    
    const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');
    new Chart(topProductsCtx, {
        type: 'bar',
        data: {
            labels: topProducts.map(item => item.product_name.length > 20 ? item.product_name.substring(0, 20) + '...' : item.product_name),
            datasets: [{
                label: 'Revenue (LKR)',
                data: topProducts.map(item => item.revenue),
                backgroundColor: 'rgba(102, 126, 234, 0.8)',
                borderColor: '#667eea',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Revenue (LKR)'
                    }
                }
            }
        }
    });
    
    // Category Distribution Chart
    const categoryData = {};
    Object.values(itemSales).forEach(item => {
        if (!categoryData[item.category]) {
            categoryData[item.category] = 0;
        }
        categoryData[item.category] += item.revenue;
    });
    
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(categoryData),
            datasets: [{
                data: Object.values(categoryData),
                backgroundColor: [
                    '#667eea',
                    '#764ba2',
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
                    position: 'bottom',
                    labels: {
                        padding: 10,
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function exportChart(chartId) {
    alert('Chart export functionality would be implemented here');
}

function exportTable() {
    alert('Excel export functionality would be implemented here');
}
</script>
@endpush
