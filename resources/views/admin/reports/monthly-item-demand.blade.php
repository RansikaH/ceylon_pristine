@extends('admin.layout')

@section('title', 'Monthly Item Demand Report')

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
    
    .month-tabs {
        border-bottom: 2px solid #e9ecef;
        margin-bottom: 2rem;
    }
    
    .month-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6c757d;
        font-weight: 500;
        padding: 1rem 1.5rem;
        transition: all 0.3s ease;
    }
    
    .month-tabs .nav-link:hover {
        color: #667eea;
        border-bottom-color: rgba(102, 126, 234, 0.3);
    }
    
    .month-tabs .nav-link.active {
        color: #667eea;
        border-bottom-color: #667eea;
        background: none;
        font-weight: 600;
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
        background: linear-gradient(135deg, #f6c23e 0%, #e74a3b 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }
    
    .demand-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .demand-high { background-color: #f8d7da; color: #721c24; }
    .demand-medium { background-color: #fff3cd; color: #856404; }
    .demand-low { background-color: #d1f2eb; color: #0e5f4e; }
    
    .year-selector {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
    }
    
    .year-selector:focus {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.3);
        color: white;
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.1);
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
                    <i class="bi bi-calendar-month me-2"></i>Monthly Item Demand Report
                </h1>
                <p class="mb-0 opacity-90">Product demand trends and monthly sales analysis</p>
            </div>
            <div class="col-md-6 text-md-end">
                <form method="GET" action="{{ route('admin.reports.monthly-item-demand') }}" class="d-inline-flex gap-2 align-items-center">
                    <select name="year" class="form-select year-selector" onchange="this.form.submit()">
                        @for($y = 2020; $y <= date('Y'); $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="btn btn-light">
                        <i class="bi bi-arrow-clockwise me-1"></i>Update
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Year Overview Stats -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Year</h6>
                            <h3 class="mb-0">{{ $year }}</h3>
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
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Total Products Sold</h6>
                            <h3 class="mb-0">{{ array_sum(array_column($yearlyDemand, 'quantity')) }}</h3>
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
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Year Revenue</h6>
                            <h3 class="mb-0">LKR {{ number_format(array_sum(array_column($yearlyDemand, 'revenue')), 0) }}</h3>
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
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Top Product</h6>
                            <h3 class="mb-0" style="font-size: 1rem;">{{ !empty($yearlyDemand) ? array_keys($yearlyDemand)[0] : 'N/A' }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Trend Chart -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-graph-up me-2"></i>Monthly Demand Trends
                    </h6>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary" onclick="exportChart('monthlyTrends')">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="monthlyTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-trophy me-2"></i>Top 10 Products ({{ $year }})
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="topProductsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthlyDemand = @json($monthlyDemand);
    const yearlyDemand = @json($yearlyDemand);
    const months = @json($months);
    
    console.log('Monthly Demand Data:', monthlyDemand);
    console.log('Yearly Demand Data:', yearlyDemand);
    console.log('Months Data:', months);
    
    // Prepare data for monthly trends chart
    const monthlyData = months.map(month => {
        const monthData = monthlyDemand[month] || [];
        return {
            month: month,
            totalQuantity: monthData.reduce((sum, item) => sum + (item.quantity || 0), 0),
            totalRevenue: monthData.reduce((sum, item) => sum + (item.revenue || 0), 0),
            productCount: monthData.length
        };
    });
    
    console.log('Monthly Chart Data:', monthlyData);
    
    // Monthly Trends Chart
    try {
        const trendsCtx = document.getElementById('monthlyTrendsChart');
        if (!trendsCtx) {
            console.error('Monthly trends chart canvas not found');
            return;
        }
        
        // Check if there's any data to display
        const hasData = monthlyData.some(item => item.totalQuantity > 0 || item.totalRevenue > 0);
        
        if (!hasData) {
            console.warn('No data available for monthly trends chart');
            trendsCtx.parentElement.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 text-muted"><div><i class="bi bi-inbox" style="font-size: 2rem;"></i><p class="mt-2">No data available for the selected period</p></div></div>';
            return;
        }
        
        new Chart(trendsCtx.getContext('2d'), {
            type: 'line',
            data: {
                labels: monthlyData.map(item => new Date(item.month).toLocaleDateString('en-US', { month: 'short' })),
                datasets: [{
                    label: 'Total Quantity',
                    data: monthlyData.map(item => item.totalQuantity),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }, {
                    label: 'Total Revenue (LKR)',
                    data: monthlyData.map(item => item.totalRevenue),
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
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.datasetIndex === 0) {
                                    label += new Intl.NumberFormat().format(context.parsed.y) + ' units';
                                } else {
                                    label += 'LKR ' + new Intl.NumberFormat().format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Quantity'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Revenue (LKR)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                }
            }
        });
        
        console.log('Monthly trends chart rendered successfully');
    } catch (error) {
        console.error('Error rendering monthly trends chart:', error);
    }
    
    // Top Products Chart - Take first 10 from yearly demand
    try {
        const topProducts = Array.isArray(yearlyDemand) ? yearlyDemand.slice(0, 10) : Object.values(yearlyDemand).slice(0, 10);
        
        console.log('Top Products Data:', topProducts);
        
        const topProductsCtx = document.getElementById('topProductsChart');
        if (!topProductsCtx) {
            console.error('Top products chart canvas not found');
            return;
        }
        
        // Check if there's any data to display
        if (topProducts.length === 0) {
            console.warn('No data available for top products chart');
            topProductsCtx.parentElement.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 text-muted"><div><i class="bi bi-inbox" style="font-size: 2rem;"></i><p class="mt-2">No data available for the selected period</p></div></div>';
            return;
        }
        
        new Chart(topProductsCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: topProducts.map(item => (item.product_name || 'Unknown').length > 15 ? (item.product_name || 'Unknown').substring(0, 15) + '...' : (item.product_name || 'Unknown')),
                datasets: [{
                    label: 'Quantity',
                    data: topProducts.map(item => item.quantity || 0),
                    backgroundColor: 'rgba(246, 194, 62, 0.8)',
                    borderColor: '#f6c23e',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Quantity: ' + new Intl.NumberFormat().format(context.parsed.y) + ' units';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantity Sold'
                        }
                    }
                }
            }
        });
        
        console.log('Top products chart rendered successfully');
    } catch (error) {
        console.error('Error rendering top products chart:', error);
    }
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

function exportChart(chartId) {
    alert('Chart export functionality would be implemented here');
}

function viewProductDetails(productId) {
    window.open('/admin/products/' + productId, '_blank');
}

function viewProductTrend(productId) {
    alert('Product trend analysis for product ID ' + productId + ' would be shown here');
}
</script>
@endpush
