@extends('admin.layout')

@section('title', 'District Wise Sales Report')

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
    
    .district-cell {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .district-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, #36b9cc 0%, #1cc88a 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
    }
    
    .performance-badge {
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .performance-high { background-color: #d1f2eb; color: #0e5f4e; }
    .performance-medium { background-color: #fff3cd; color: #856404; }
    .performance-low { background-color: #f8d7da; color: #721c24; }
    
    .map-container {
        background: #f8f9fc;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        border: 2px dashed #dee2e6;
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
                    <i class="bi bi-geo-alt me-2"></i>District Wise Sales Report
                </h1>
                <p class="mb-0 opacity-90">Geographic sales distribution analysis by district</p>
            </div>
            <div class="col-md-6 text-md-end">
                <form method="GET" action="{{ route('admin.reports.district-wise-sales') }}" class="d-inline-flex gap-2">
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
        
        <div class="col-md-4">
            <div class="card stat-card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-info bg-opacity-10 text-info me-3">
                            <i class="bi bi-geo"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-2">Districts Covered</h6>
                            <h3 class="mb-0">{{ count($districtSales) }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Map -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <i class="bi bi-bar-chart me-2"></i>District Revenue Distribution
                    </h6>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary" onclick="exportChart('districtChart')">
                            <i class="bi bi-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="districtChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="bi bi-pie-chart me-2"></i>Top Districts by Revenue
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="topDistrictsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- District Map Placeholder -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="map-container">
                <i class="bi bi-map text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3 text-muted">Sri Lanka District Map</h5>
                <p class="text-muted">Interactive map visualization would be implemented here</p>
                <button class="btn btn-outline-primary btn-sm mt-2" onclick="alert('Map integration would be implemented here')">
                    <i class="bi bi-fullscreen me-1"></i>View Full Map
                </button>
            </div>
        </div>
    </div>

    <!-- Districts Table -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0">
                <i class="bi bi-list-ul me-2"></i>District Sales Details
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
                <table class="table table-hover mb-0" id="districtsTable">
                    <thead class="table-light">
                        <tr>
                            <th>District</th>
                            <th>Revenue</th>
                            <th>Orders</th>
                            <th>Unique Customers</th>
                            <th>Avg Order Value</th>
                            <th>Performance</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($districtSales as $district)
                        <tr>
                            <td>
                                <div class="district-cell">
                                    <div class="district-icon">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium">{{ $district['district'] }}</div>
                                        <small class="text-muted">Geographic Region</small>
                                    </div>
                                </div>
                            </td>
                            <td class="fw-semibold text-success">LKR {{ number_format($district['revenue'], 2) }}</td>
                            <td class="fw-semibold">{{ number_format($district['orders']) }}</td>
                            <td>{{ number_format($district['unique_customers']) }}</td>
                            <td>LKR {{ number_format($district['orders'] > 0 ? $district['revenue'] / $district['orders'] : 0, 2) }}</td>
                            <td>
                                @php
                                    $avgRevenue = $totalRevenue / count($districtSales);
                                    $performance = $district['revenue'] > $avgRevenue * 1.2 ? 'high' : 
                                                   ($district['revenue'] > $avgRevenue * 0.8 ? 'medium' : 'low');
                                @endphp
                                <span class="performance-badge performance-{{ $performance }}">
                                    {{ ucfirst($performance) }} Performance
                                </span>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-sm btn-outline-primary rounded-circle me-1" data-bs-toggle="tooltip" title="View District Details" onclick="viewDistrictDetails('{{ $district['district'] }}')">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info rounded-circle" data-bs-toggle="tooltip" title="Export District Data" onclick="exportDistrictData('{{ $district['district'] }}')">
                                    <i class="bi bi-download"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox text-muted" style="font-size: 2rem;"></i>
                                <p class="mt-2 mb-0 text-muted">No district sales data found for the selected period</p>
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
    const districtSales = @json($districtSales);
    
    console.log('District Sales Data:', districtSales); // Debug log
    
    if (districtSales && districtSales.length > 0) {
        renderDistrictChart(districtSales);
        renderTopDistrictsChart(districtSales);
    } else {
        showNoDataMessage();
    }
    
    initializeTooltips();
});

function renderDistrictChart(districtSales) {
    try {
        const ctx = document.getElementById('districtChart');
        if (!ctx) {
            console.error('District chart canvas not found');
            return;
        }
        
        new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: districtSales.map(item => item.district),
                datasets: [{
                    label: 'Revenue (LKR)',
                    data: districtSales.map(item => item.revenue),
                    backgroundColor: 'rgba(54, 185, 204, 0.8)',
                    borderColor: '#36b9cc',
                    borderWidth: 1
                }, {
                    label: 'Orders',
                    data: districtSales.map(item => item.orders),
                    backgroundColor: 'rgba(28, 200, 138, 0.8)',
                    borderColor: '#1cc88a',
                    borderWidth: 1,
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
                                    label += 'LKR ' + new Intl.NumberFormat().format(context.parsed.y);
                                } else {
                                    label += new Intl.NumberFormat().format(context.parsed.y);
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
                            text: 'Revenue (LKR)'
                        },
                        ticks: {
                            callback: function(value) {
                                return 'LKR ' + new Intl.NumberFormat().format(value);
                            }
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
        
        console.log('District chart rendered successfully');
    } catch (error) {
        console.error('Error rendering district chart:', error);
    }
}

function renderTopDistrictsChart(districtSales) {
    try {
        const ctx = document.getElementById('topDistrictsChart');
        if (!ctx) {
            console.error('Top districts chart canvas not found');
            return;
        }
        
        // Take top 5 districts
        const topDistricts = districtSales.slice(0, 5);
        
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: topDistricts.map(item => item.district),
                datasets: [{
                    data: topDistricts.map(item => item.revenue),
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#36b9cc',
                        '#1cc88a',
                        '#f6c23e'
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
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': LKR ' + new Intl.NumberFormat().format(context.parsed) + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
        
        console.log('Top districts chart rendered successfully');
    } catch (error) {
        console.error('Error rendering top districts chart:', error);
    }
}

function showNoDataMessage() {
    const chartContainers = document.querySelectorAll('.chart-container');
    chartContainers.forEach(container => {
        container.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100 text-muted"><div><i class="bi bi-inbox" style="font-size: 2rem;"></i><p class="mt-2">No data available</p></div></div>';
    });
}

function initializeTooltips() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

function exportChart(chartId) {
    showNotification('Chart export functionality would be implemented here', 'info');
}

function exportTable() {
    showNotification('Excel export functionality would be implemented here', 'info');
}

function viewDistrictDetails(district) {
    showNotification('District details for ' + district + ' would be shown here', 'info');
}

function exportDistrictData(district) {
    showNotification('Export data for ' + district + ' would be downloaded here', 'info');
}

function showNotification(message, type = 'info') {
    const alertClass = type === 'success' ? 'alert-success' : 
                      type === 'error' ? 'alert-danger' : 'alert-info';
    
    const notification = document.createElement('div');
    notification.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        <i class="bi bi-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-triangle' : 'info-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}
</script>
@endpush
