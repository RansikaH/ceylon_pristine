@extends('admin.layout')

@section('title', 'Admin Dashboard')

@php
    // Calculate revenue data at the top of the view so it's available everywhere
    $viewMonthlyRevenue = [];
    for ($i = 5; $i >= 0; $i--) {
        $monthStart = \Carbon\Carbon::now()->subMonths($i)->startOfMonth();
        $monthEnd = \Carbon\Carbon::now()->subMonths($i)->endOfMonth();
        $revenue = \App\Models\Order::where('status', '!=', 'cancelled')
            ->where('created_at', '>=', $monthStart)
            ->where('created_at', '<=', $monthEnd)
            ->sum('total');
        $viewMonthlyRevenue[] = $revenue;
    }
    
    $viewTotalRevenue = \App\Models\Order::where('status', '!=', 'cancelled')->sum('total');
    $viewRevenueGrowth = 0;
    if (count($viewMonthlyRevenue) >= 2) {
        $current = $viewMonthlyRevenue[count($viewMonthlyRevenue) - 1];
        $previous = $viewMonthlyRevenue[count($viewMonthlyRevenue) - 2];
        $viewRevenueGrowth = $previous > 0 ? (($current - $previous) / $previous * 100) : 0;
    }
    
    // Calculate order growth data
    $viewMonthlyOrders = [];
    for ($i = 5; $i >= 0; $i--) {
        $monthStart = \Carbon\Carbon::now()->subMonths($i)->startOfMonth();
        $monthEnd = \Carbon\Carbon::now()->subMonths($i)->endOfMonth();
        $orderCount = \App\Models\Order::where('created_at', '>=', $monthStart)
            ->where('created_at', '<=', $monthEnd)
            ->count();
        $viewMonthlyOrders[] = $orderCount;
    }
    
    $viewTotalOrders = \App\Models\Order::count();
    $viewOrdersGrowth = 0;
    if (count($viewMonthlyOrders) >= 2) {
        $currentOrders = $viewMonthlyOrders[count($viewMonthlyOrders) - 1];
        $previousOrders = $viewMonthlyOrders[count($viewMonthlyOrders) - 2];
        $viewOrdersGrowth = $previousOrders > 0 ? (($currentOrders - $previousOrders) / $previousOrders * 100) : 0;
    }
    
    // Debug: Log the calculations
    if (count($viewMonthlyRevenue) >= 2) {
        $debugCurrent = $viewMonthlyRevenue[count($viewMonthlyRevenue) - 1];
        $debugPrevious = $viewMonthlyRevenue[count($viewMonthlyRevenue) - 2];
        \Log::info('View Revenue Growth Calculation:');
        \Log::info('Previous month: LKR ' . $debugPrevious);
        \Log::info('Current month: LKR ' . $debugCurrent);
        \Log::info('Growth: ' . round($viewRevenueGrowth, 1) . '%');
    }
    
    if (count($viewMonthlyOrders) >= 2) {
        $debugCurrentOrders = $viewMonthlyOrders[count($viewMonthlyOrders) - 1];
        $debugPreviousOrders = $viewMonthlyOrders[count($viewMonthlyOrders) - 2];
        \Log::info('View Orders Growth Calculation:');
        \Log::info('Previous month orders: ' . $debugPreviousOrders);
        \Log::info('Current month orders: ' . $debugCurrentOrders);
        \Log::info('Orders growth: ' . round($viewOrdersGrowth, 1) . '%');
    }
    
    // Calculate Quick Stats metrics
    $viewOrderCompletionRate = 0;
    $totalOrdersForCompletion = \App\Models\Order::count();
    $completedOrdersForCompletion = \App\Models\Order::where('status', 'completed')->count();
    $viewOrderCompletionRate = $totalOrdersForCompletion > 0 
        ? ($completedOrdersForCompletion / $totalOrdersForCompletion) * 100 
        : 0;
    
    $viewInventoryStatus = 0;
    $totalProducts = \App\Models\Product::count();
    $inStockProducts = \App\Models\Product::where('stock', '>', 0)->count();
    $viewInventoryStatus = $totalProducts > 0 
        ? ($inStockProducts / $totalProducts) * 100 
        : 0;
    
    $viewNewCustomersThisMonth = \App\Models\User::where('role', 'user')
        ->where('created_at', '>=', \Carbon\Carbon::now()->startOfMonth())
        ->count();
    
    $viewAvgOrderValue = 0;
    $completedOrders = \App\Models\Order::where('status', 'completed')->count();
    $viewAvgOrderValue = $completedOrders > 0 
        ? \App\Models\Order::where('status', 'completed')->avg('total') 
        : 0;
    
    // Calculate top selling products from JSON order items
    $viewTopProducts = collect();
    $completedOrdersWithItems = \App\Models\Order::where('status', 'completed')
        ->whereNotNull('items')
        ->get();
    
    $productSales = [];
    foreach ($completedOrdersWithItems as $order) {
        $orderItems = $order->items;
        
        // Handle both array and string JSON
        if (is_string($orderItems)) {
            $orderItems = json_decode($orderItems, true);
        }
        
        if (is_array($orderItems)) {
            foreach ($orderItems as $item) {
                $productId = $item['product_id'] ?? null;
                if ($productId) {
                    if (!isset($productSales[$productId])) {
                        $productSales[$productId] = 0;
                    }
                    $productSales[$productId] += $item['quantity'] ?? 1;
                }
            }
        }
    }
    
    // Sort by sales and get top 5 products
    arsort($productSales);
    $topProductIds = array_keys(array_slice($productSales, 0, 5, true));
    
    if (!empty($topProductIds)) {
        $viewTopProducts = \App\Models\Product::whereIn('id', $topProductIds)
            ->get()
            ->map(function($product) use ($productSales) {
                $product->total_sold = $productSales[$product->id] ?? 0;
                return $product;
            })
            ->sortByDesc('total_sold')
            ->values();
    }
@endphp

@push('styles')
<style>
    /* Base Styles */
    :root {
        --primary: #4e73df;
        --primary-dark: #224abe;
        --success: #1cc88a;
        --warning: #f6c23e;
        --danger: #e74a3b;
        --gray-100: #f8f9fc;
        --gray-200: #eaecf4;
        --gray-300: #e3e6f0;
        --gray-600: #6c757d;
        --gray-800: #5a5c69;
    }
    
    body {
        background-color: #f8f9fc;
        color: var(--gray-800);
    }
    
    /* Card Styles */
    .card {
        border: 1px solid var(--gray-300);
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.5rem rgba(58, 59, 69, 0.1);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
        background: #fff;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    }
    
    .card-header {
        background-color: var(--gray-100);
        border-bottom: 1px solid var(--gray-300);
        font-weight: 600;
        padding: 1rem 1.5rem;
        border-top-left-radius: 0.5rem !important;
        border-top-right-radius: 0.5rem !important;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--primary);
    }
    
    /* Stat Card */
    .stat-card {
        transition: all 0.3s ease;
        border: 1px solid var(--gray-300);
        border-left: 0.35rem solid;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.5rem rgba(58, 59, 69, 0.1);
        margin-bottom: 1.5rem;
        height: 100%;
        background: #fff;
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1);
    }
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.1;
        position: absolute;
        right: 1.25rem;
        top: 1.25rem;
        transition: all 0.3s ease;
        z-index: 1;
    }
    
    .stat-card:hover .stat-icon {
        transform: scale(1.05);
        opacity: 0.15;
    }
    
    .icon-circle {
        height: 2.75rem;
        width: 2.75rem;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    
    .stat-value {
        font-size: 1.75rem;
        font-weight: 700;
        line-height: 1.2;
        color: var(--gray-800);
        margin: 0.5rem 0;
        position: relative;
        z-index: 2;
    }
    
    .stat-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--gray-600);
        margin: 0;
        font-weight: 600;
        position: relative;
        z-index: 2;
    }

    .card {
        border: 1px solid #e3e6f0;
        border-radius: 0.5rem;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        margin-bottom: 1.5rem;
        transition: box-shadow 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        font-weight: 600;
        padding: 1rem 1.25rem;
        border-top-left-radius: 0.5rem !important;
        border-top-right-radius: 0.5rem !important;
    }
    /* Welcome Card */
    .welcome-card {
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        box-shadow: 0 0.25rem 1.5rem rgba(34, 74, 190, 0.2);
        overflow: hidden;
        position: relative;
    }
    
    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 60%);
        transform: rotate(30deg);
        z-index: 1;
    }
    
    .welcome-card .card-body {
        padding: 2rem;
        position: relative;
        z-index: 2;
    }
    
    .welcome-card h2 {
        font-weight: 700;
        margin-bottom: 0.75rem;
        font-size: 1.75rem;
        line-height: 1.2;
    }
    
    .welcome-card p {
        opacity: 0.9;
        margin-bottom: 1.5rem;
        font-size: 1.05rem;
        max-width: 600px;
    }
    /* Stats and Progress */
    .quick-stats {
        background-color: #fff;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid var(--gray-300);
    }
    
    .stat-item {
        margin-bottom: 1.5rem;
    }
    
    .stat-item:last-child {
        margin-bottom: 0;
    }
    
    .stat-item-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }
    
    .stat-item-title {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--gray-600);
        margin: 0;
    }
    
    .stat-item-value {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--gray-800);
    }
    
    .progress {
        height: 6px;
        border-radius: 3px;
        background-color: var(--gray-200);
        margin: 0.5rem 0 1.25rem;
        overflow: visible;
        position: relative;
    }
    
    .progress-bar {
        border-radius: 3px;
        position: relative;
        transition: width 0.6s ease;
    }
    
    .progress-bar::after {
        content: '';
        position: absolute;
        top: -3px;
        right: -4px;
        width: 12px;
        height: 12px;
        background: white;
        border-radius: 50%;
        border: 2px solid currentColor;
        box-shadow: 0 0 0 2px rgba(255,255,255,0.8);
    }
    .table {
        margin-bottom: 0;
        color: #5a5c69;
    }
    
    .table th {
        font-weight: 600;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6c757d;
        padding: 1rem;
        background-color: #f8f9fc;
        border-bottom: 2px solid #e3e6f0;
    }
    
    .table td {
        padding: 1rem;
        vertical-align: middle;
        border-top: 1px solid #e3e6f0;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fc;
    }
    .badge {
        font-weight: 500;
        padding: 0.4em 0.8em;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .badge.bg-success {
        background-color: #1cc88a !important;
    }
    
    .badge.bg-warning {
        background-color: #f6c23e !important;
        color: #1f2d3d;
    }
    
    .badge.bg-danger {
        background-color: #e74a3b !important;
    }
    
    /* Status Badge Colors - Enhanced Readability */
    .badge.text-success {
        color: #0e7c5a !important;
    }
    
    .badge.text-warning {
        color: #c87d0e !important;
    }
    
    .badge.text-info {
        color: #0c5c8d !important;
    }
    
    .badge.text-danger {
        color: #c42e21 !important;
    }
    
    /* Status Badge Backgrounds */
    .badge.bg-success.bg-opacity-10 {
        background-color: rgba(28, 200, 138, 0.15) !important;
    }
    
    .badge.bg-warning.bg-opacity-10 {
        background-color: rgba(246, 194, 62, 0.15) !important;
    }
    
    .badge.bg-info.bg-opacity-10 {
        background-color: rgba(54, 162, 235, 0.15) !important;
    }
    
    .badge.bg-danger.bg-opacity-10 {
        background-color: rgba(231, 74, 59, 0.15) !important;
    }
</style>
@endpush

@section('content')
<div class="container px-4 py-3">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50 me-1"></i> Generate Report
        </a>
    </div>

    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card welcome-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h2>Welcome back, Admin!</h2>
                            <p>Here's what's happening with your store today. You have {{ $stats['new_orders_this_month'] ?? 0 }} new orders and {{ $stats['new_customers_this_month'] ?? 0 }} new customers this month.</p>
                            <div class="d-flex gap-3">
                                <a href="#" class="btn btn-light rounded-pill px-4">
                                    <i class="fas fa-chart-line me-2"></i>View Reports
                                </a>
                                <a href="#" class="btn btn-outline-light rounded-pill px-4">
                                    <i class="fas fa-cog me-2"></i>Settings
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 d-none d-lg-block text-center">
                            <i class="bi bi-graph-up-arrow" style="font-size: 6rem; opacity: 0.15;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Total Revenue -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100" style="border-left-color: var(--primary);">
                <div class="card-body position-relative">
                    <i class="bi bi-currency-dollar stat-icon text-primary"></i>
                    <h6 class="stat-label">Total Revenue (Order Values)</h6>
                    <h3 class="stat-value mb-3">LKR {{ number_format($viewTotalRevenue ?? $stats['total_revenue'] ?? 0, 2) }}</h3>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-primary bg-opacity-10 text-primary me-2">
                            <i class="bi bi-arrow-{{ ($viewRevenueGrowth ?? 0) >= 0 ? 'up' : 'down' }} me-1"></i> {{ abs(number_format($viewRevenueGrowth ?? $stats['revenue_growth'] ?? 0, 1)) }}%
                        </span>
                        <span class="small text-muted">order value growth vs last month</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.orders.index') }}" class="text-primary text-decoration-none small fw-bold d-flex align-items-center">
                        View Reports <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Total Orders -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100" style="border-left-color: var(--success);">
                <div class="card-body position-relative">
                    <i class="bi bi-cart-check stat-icon text-success"></i>
                    <h6 class="stat-label">Total Orders</h6>
                    <h3 class="stat-value mb-3">{{ number_format($viewTotalOrders ?? $stats['total_orders'] ?? 0) }}</h3>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success bg-opacity-10 text-success me-2">
                            <i class="bi bi-arrow-{{ ($viewOrdersGrowth ?? 0) >= 0 ? 'up' : 'down' }} me-1"></i> {{ abs(number_format($viewOrdersGrowth ?? 0, 1)) }}%
                        </span>
                        <span class="small text-muted">vs last month</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.orders.index') }}" class="text-success text-decoration-none small fw-bold d-flex align-items-center">
                        View Orders <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Products -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100" style="border-left-color: var(--warning);">
                <div class="card-body position-relative">
                    <i class="bi bi-box-seam stat-icon text-warning"></i>
                    <h6 class="stat-label">Products</h6>
                    <h3 class="stat-value mb-3">{{ number_format($stats['total_products'] ?? 0) }}</h3>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-warning bg-opacity-10 text-warning me-2">
                            <i class="bi bi-arrow-up me-1"></i> 5.7%
                        </span>
                        <span class="small text-muted">vs last month</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.products.index') }}" class="text-warning text-decoration-none small fw-bold d-flex align-items-center">
                        View Products <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Customers -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100" style="border-left-color: var(--info);">
                <div class="card-body position-relative">
                    <i class="bi bi-people stat-icon text-info"></i>
                    <h6 class="stat-label">Customers</h6>
                    <h3 class="stat-value mb-3">{{ number_format($stats['total_customers'] ?? 0) }}</h3>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-info bg-opacity-10 text-info me-2">
                            <i class="bi bi-arrow-{{ ($stats['customers_growth'] ?? 0) >= 0 ? 'up' : 'down' }} me-1"></i> {{ abs($stats['customers_growth'] ?? 0) }}%
                        </span>
                        <span class="small text-muted">vs last month</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.customers') }}" class="text-info text-decoration-none small fw-bold d-flex align-items-center">
                        View Customers <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row g-4 mb-4">
        <!-- Customer Chat -->
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card h-100" style="border-left-color: var(--success);">
                <div class="card-body position-relative">
                    <i class="bi bi-chat-dots-fill stat-icon text-success"></i>
                    <h6 class="stat-label">Customer Messages</h6>
                    <h3 class="stat-value mb-3">
                        <span id="unread-messages-count">0</span>
                        <small class="text-muted">unread</small>
                    </h3>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-success bg-opacity-10 text-success me-2">
                            <i class="bi bi-chat-fill me-1"></i> Active
                        </span>
                        <span class="small text-muted">customer support</span>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0">
                    <a href="{{ route('admin.chat.index') }}" class="text-success text-decoration-none small fw-bold d-flex align-items-center">
                        View Messages <i class="bi bi-chevron-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Orders Chart -->
        <div class="col-xl-9 col-md-6">
            <div class="card h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Orders Overview</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="{{ route('admin.orders.index') }}">View All Orders</a>
                            <a class="dropdown-item" href="#">Export Data</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="recentOrdersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row g-4">
        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Overview</h6>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-link text-muted p-0" type="button" id="revenueDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="revenueDropdown">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-calendar-week me-2"></i>This Week</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-calendar-month me-2"></i>This Month</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-calendar3 me-2"></i>This Year</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-download me-2"></i>Export</a></li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 320px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Stats</h6>
                    <i class="bi bi-speedometer2 text-primary"></i>
                </div>
                <div class="card-body">
                    <div class="stat-item">
                        <div class="stat-item-header">
                            <span class="stat-item-title">Order Completion</span>
                            <span class="stat-item-value">{{ round($viewOrderCompletionRate ?? $stats['order_completion_rate'] ?? 0) }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $viewOrderCompletionRate ?? $stats['order_completion_rate'] ?? 0 }}%" aria-valuenow="{{ $viewOrderCompletionRate ?? $stats['order_completion_rate'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-item-header">
                            <span class="stat-item-title">Inventory Status</span>
                            <span class="stat-item-value">{{ round($viewInventoryStatus ?? $stats['inventory_status'] ?? 0) }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $viewInventoryStatus ?? $stats['inventory_status'] ?? 0 }}%" aria-valuenow="{{ $viewInventoryStatus ?? $stats['inventory_status'] ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-item-header">
                            <span class="stat-item-title">New Customers</span>
                            <span class="stat-item-value text-success">+{{ $viewNewCustomersThisMonth ?? $stats['new_customers_this_month'] ?? 0 }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(($viewNewCustomersThisMonth ?? $stats['new_customers_this_month'] ?? 0) * 5, 100) }}%" aria-valuenow="{{ min(($viewNewCustomersThisMonth ?? $stats['new_customers_this_month'] ?? 0) * 5, 100) }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <div class="stat-item">
                        <div class="stat-item-header">
                            <span class="stat-item-title">Avg. Order Value</span>
                            <span class="stat-item-value">LKR {{ number_format($viewAvgOrderValue ?? $stats['avg_order_value'] ?? 0, 0) }}</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" style="width: {{ min(($viewAvgOrderValue ?? $stats['avg_order_value'] ?? 0) / 150, 100) }}%" aria-valuenow="{{ min(($viewAvgOrderValue ?? $stats['avg_order_value'] ?? 0) / 150, 100) }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-center">
                        <a href="{{ route('admin.reports') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                            <i class="bi bi-graph-up me-1"></i> View Full Report
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders & Top Products -->
    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stats['recent_orders'] as $order)
                                <tr>
                                    <td class="ps-4 fw-semibold">#{{ $order->id }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-2">
                                                <div class="avatar-sm">
                                                    <div class="avatar-title bg-light text-primary rounded-circle">
                                                        {{ substr($order->user->name, 0, 1) }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0">{{ $order->user->name }}</h6>
                                                <small class="text-muted">{{ $order->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-muted">{{ $order->created_at->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </td>
                                    <td class="fw-semibold">LKR {{ number_format($order->total, 2) }}</td>
                                    <td>
                                        @if($order->status == 'completed')
                                            <span class="badge bg-success bg-opacity-10 text-success">
                                                <i class="bi bi-check-circle-fill me-1"></i> Completed
                                            </span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge bg-warning bg-opacity-10 text-warning">
                                                <i class="bi bi-arrow-repeat me-1"></i> Processing
                                            </span>
                                        @elseif($order->status == 'shipped')
                                            <span class="badge bg-info bg-opacity-10 text-info">
                                                <i class="bi bi-truck me-1"></i> Shipped
                                            </span>
                                        @else
                                            <span class="badge bg-danger bg-opacity-10 text-danger">
                                                <i class="bi bi-x-circle-fill me-1"></i> {{ ucfirst($order->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-circle me-1" data-bs-toggle="tooltip" title="View Order">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-secondary rounded-circle" data-bs-toggle="tooltip" title="Print Invoice" onclick="window.print()">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-cart-x text-muted" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0">No recent orders found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Top Products -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
                    <i class="bi bi-trophy-fill text-warning"></i>
                </div>
                <div class="card-body">
                    @php
                        $topProducts = $viewTopProducts ?? $stats['top_products'] ?? [];
                    @endphp
                    @if(count($topProducts) > 0)
                        @foreach($topProducts as $index => $product)
                        <div class="d-flex align-items-center mb-4">
                            <div class="flex-shrink-0 position-relative">
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    #{{ $index + 1 }}
                                </span>
                                <img src="{{ $product->image_url ?? asset('product-images/default_product.png') }}" 
                                     alt="{{ $product->name }}" 
                                     class="rounded" 
                                     style="width: 56px; height: 56px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);">
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">{{ $product->name }}</h6>
                                    <span class="badge bg-primary bg-opacity-10 text-primary">
                                        <i class="bi bi-cart-check me-1"></i> {{ $product->total_sold ?? 0 }} sold
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <span class="fw-semibold text-primary">LKR {{ number_format($product->price, 2) }}</span>
                                    </div>
                                    @if(isset($product->stock))
                                    <div class="text-end">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                                                @php
                                                    $total = ($product->stock ?? 0) + ($product->total_sold ?? 0);
                                                    $stockPercentage = $total > 0 ? (($product->stock ?? 0) / $total) * 100 : 0;
                                                    $soldPercentage = 100 - $stockPercentage;
                                                @endphp
                                                <div class="progress-bar bg-success" 
                                                     role="progressbar" 
                                                     style="width: {{ $soldPercentage }}%" 
                                                     aria-valuenow="{{ $soldPercentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                            <small class="text-muted ms-2">{{ round($soldPercentage) }}%</small>
                                        </div>
                                        <small class="text-muted">{{ $product->stock }} in stock</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary rounded-pill px-4">
                                View All Products <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-box-seam text-muted" style="font-size: 2.5rem;"></i>
                            <p class="mt-2 mb-0">No top selling products found</p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-primary mt-3">
                                <i class="bi bi-plus-lg me-1"></i> Add Product
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Enable tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Revenue Chart
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Get actual revenue data from backend (order values)
        var revenueData = @json($viewMonthlyRevenue);
        var months = [];
        var currentDate = new Date();
        
        // Generate labels for the last 6 months
        for (var i = 5; i >= 0; i--) {
            var date = new Date();
            date.setMonth(currentDate.getMonth() - i);
            months.push(date.toLocaleString('default', { month: 'short', year: '2-digit' }));
        }
        
        // Check if all months have zero revenue (no real order data)
        var hasAllZeroRevenue = revenueData.every(function(value) {
            return value === 0;
        });
        
        // If all months are zero, generate sample data for demonstration
        if (hasAllZeroRevenue) {
            revenueData = months.map(function(index) {
                var baseRevenue = 75000;
                var growthFactor = 1 + (index * 0.08); // 8% growth per month
                var variation = (Math.random() * 0.3 + 0.85); // ±15% variation
                return Math.round(baseRevenue * growthFactor * variation);
            });
        }
        
        // Calculate growth percentage based on order values
        var growth = {{ $viewRevenueGrowth }};
        
        // Update growth indicator in stats
        var growthElement = document.querySelector('.revenue-growth');
        if (growthElement) {
            growthElement.textContent = (growth >= 0 ? '+' : '') + growth.toFixed(1) + '%';
            growthElement.className = 'badge bg-' + (growth >= 0 ? 'success' : 'danger') + ' bg-opacity-10 text-' + (growth >= 0 ? 'success' : 'danger');
        }
        
        // Update total revenue display
        var totalRevenueElements = document.querySelectorAll('.stat-value');
        totalRevenueElements.forEach(function(element) {
            if (element.textContent.includes('LKR 0.00')) {
                element.textContent = 'LKR {{ number_format($viewTotalRevenue, 2) }}';
            }
        });
        
        // Create the revenue chart with order values
        var revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenue from Order Values (View Calculated)',
                    data: revenueData,
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointHoverBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        titleFont: {
                            size: 13,
                            weight: '600',
                            family: "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
                        },
                        bodyFont: {
                            size: 13,
                            family: "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
                        },
                        padding: 12,
                        displayColors: false,
                        cornerRadius: 4,
                        callbacks: {
                            label: function(context) {
                                return 'Revenue from Orders: LKR ' + context.parsed.y.toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            },
                            title: function(context) {
                                return context[0].label + ' Order Values';
                            },
                            afterBody: function(context) {
                                var index = context[0].dataIndex;
                                if (index > 0 && !hasAllZeroRevenue) {
                                    var currentValue = context[0].parsed.y;
                                    var previousValue = revenueData[index - 1];
                                    var change = ((currentValue - previousValue) / previousValue * 100).toFixed(1);
                                    var changeSymbol = change >= 0 ? '↑' : '↓';
                                    var changeColor = change >= 0 ? '#1cc88a' : '#e74a3b';
                                    return [
                                        '',
                                        'Order value change from last month: ' + changeSymbol + ' ' + Math.abs(change) + '%'
                                    ];
                                }
                                return '';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.03)',
                            drawBorder: false,
                            drawTicks: false
                        },
                        ticks: {
                            padding: 10,
                            color: '#6c757d',
                            font: {
                                size: 12,
                                family: "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
                            },
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'LKR ' + (value / 1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return 'LKR ' + (value / 1000).toFixed(0) + 'k';
                                }
                                return 'LKR ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            padding: 10,
                            color: '#6c757d',
                            font: {
                                size: 12,
                                family: "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
                            }
                        }
                    }
                }
            }
        });
        
        // Recent Orders Chart
        var recentOrdersCtx = document.getElementById('recentOrdersChart').getContext('2d');
        
        // Get actual order data from backend
        var ordersData = @json($stats['monthly_orders']);
        var orderMonths = [];
        var currentOrderDate = new Date();
        
        // Generate labels for the last 6 months
        for (var i = 5; i >= 0; i--) {
            var date = new Date();
            date.setMonth(currentOrderDate.getMonth() - i);
            orderMonths.push(date.toLocaleString('default', { month: 'short', year: '2-digit' }));
        }
        
        // Check if all months have zero orders (no real order data)
        var hasAllZeroOrders = ordersData.every(function(value) {
            return value === 0;
        });
        
        // If all months are zero, generate sample data for demonstration
        if (hasAllZeroOrders) {
            ordersData = orderMonths.map(function(index) {
                var baseOrders = 25;
                var growthFactor = 1 + (index * 0.12); // 12% growth per month
                var variation = (Math.random() * 0.4 + 0.8); // ±20% variation
                return Math.round(baseOrders * growthFactor * variation);
            });
        }
        
        // Create the recent orders chart
        var recentOrdersChart = new Chart(recentOrdersCtx, {
            type: 'bar',
            data: {
                labels: orderMonths,
                datasets: [{
                    label: 'Number of Orders',
                    data: ordersData,
                    backgroundColor: 'rgba(28, 200, 138, 0.8)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    borderWidth: 2,
                    borderRadius: 4,
                    borderSkipped: false,
                    hoverBackgroundColor: 'rgba(28, 200, 138, 1)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.85)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        titleFont: {
                            size: 13,
                            weight: '600',
                            family: "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
                        },
                        bodyFont: {
                            size: 13,
                            family: "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
                        },
                        padding: 12,
                        displayColors: false,
                        cornerRadius: 4,
                        callbacks: {
                            label: function(context) {
                                return 'Orders: ' + context.parsed.y.toLocaleString();
                            },
                            title: function(context) {
                                return context[0].label + ' Orders';
                            },
                            afterBody: function(context) {
                                var index = context[0].dataIndex;
                                if (index > 0 && !hasAllZeroOrders) {
                                    var currentValue = context[0].parsed.y;
                                    var previousValue = ordersData[index - 1];
                                    var change = previousValue > 0 ? ((currentValue - previousValue) / previousValue * 100).toFixed(1) : 0;
                                    var changeSymbol = change >= 0 ? '↑' : '↓';
                                    var changeColor = change >= 0 ? '#1cc88a' : '#e74a3b';
                                    return [
                                        '',
                                        'Change from last month: ' + changeSymbol + ' ' + Math.abs(change) + '%'
                                    ];
                                }
                                return '';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.03)',
                            drawBorder: false,
                            drawTicks: false
                        },
                        ticks: {
                            padding: 10,
                            color: '#6c757d',
                            font: {
                                size: 12,
                                family: "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
                            },
                            callback: function(value) {
                                if (value >= 1000) {
                                    return (value / 1000).toFixed(0) + 'k';
                                }
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            padding: 10,
                            color: '#6c757d',
                            font: {
                                size: 12,
                                family: "'Nunito', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif"
                            }
                        }
                    }
                }
            }
        });
        
        // Dropdown functionality for time periods
        var revenueDropdownItems = document.querySelectorAll('#revenueDropdown + .dropdown-menu .dropdown-item');
        revenueDropdownItems.forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                var period = this.textContent.trim();
                console.log('Changing revenue period to:', period);
                
                // Show loading state
                revenueChart.data.datasets[0].data = revenueChart.data.labels.map(function() {
                    return 0;
                });
                revenueChart.update();
                
                // Simulate loading new data (replace with actual API call)
                setTimeout(function() {
                    var newData;
                    switch(period) {
                        case 'This Week':
                            newData = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'].map(function() {
                                return 10000 + Math.floor(Math.random() * 5000);
                            });
                            break;
                        case 'This Month':
                            newData = Array.from({length: 30}, function(_, i) {
                                return i + 1;
                            }).map(function() {
                                return 20000 + Math.floor(Math.random() * 10000);
                            });
                            break;
                        case 'This Year':
                            newData = months.map(function() {
                                return 60000 + Math.floor(Math.random() * 25000);
                            });
                            break;
                        default:
                            newData = revenueData;
                    }
                    
                    revenueChart.data.labels = period === 'This Week' ? 
                        ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] : 
                        (period === 'This Month' ? 
                            Array.from({length: 30}, function(_, i) { return (i + 1).toString(); }) : 
                            months);
                    
                    revenueChart.data.datasets[0].data = newData;
                    revenueChart.update();
                }, 500);
            });
        });
        
        // Export functionality for order values
        document.querySelector('.dropdown-item[href="#"]').addEventListener('click', function(e) {
            if (this.textContent.includes('Export')) {
                e.preventDefault();
                
                // Create CSV data for order values
                var csvContent = "Month,Revenue from Order Values (LKR)\n";
                months.forEach(function(month, index) {
                    csvContent += month + "," + revenueData[index] + "\n";
                });
                
                // Create download link
                var blob = new Blob([csvContent], { type: 'text/csv' });
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = url;
                a.download = 'order-values-revenue-' + new Date().toISOString().slice(0, 7) + '.csv';
                a.click();
                window.URL.revokeObjectURL(url);
            }
        });

        // Tooltip initialization
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover',
                placement: 'top',
                container: 'body'
            });
        });
        
        // Initialize popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
        
        // Update unread message count
        function updateUnreadMessageCount() {
            fetch('{{ route("admin.chat.conversations") }}')
                .then(response => response.json())
                .then(data => {
                    const totalUnread = data.conversations.reduce((sum, conv) => sum + conv.unread_count, 0);
                    const countElement = document.getElementById('unread-messages-count');
                    if (countElement) {
                        countElement.textContent = totalUnread;
                    }
                })
                .catch(error => {
                    console.error('Error fetching unread count:', error);
                });
        }
        
        // Update unread count every 30 seconds
        updateUnreadMessageCount();
        setInterval(updateUnreadMessageCount, 30000);
    });
</script>
@endpush
