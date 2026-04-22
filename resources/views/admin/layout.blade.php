<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - {{ config('app.name', 'Ceylon Moms') }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    @stack('styles')
    <style>
        /* Modern Navigation Styling */
        .navbar {
            padding: 0.75rem 1.5rem;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Message Badge Styling */
        #nav-message-count {
            position: absolute !important;
            top: 8px !important;
            left: 8px !important;
            transform: translate(-50%, -50%) !important;
            font-size: 0.6rem !important;
            padding: 0.2em 0.45em !important;
            min-width: 16px !important;
            height: 16px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            line-height: 1 !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.3) !important;
            border: 2px solid #667eea !important;
        }

        #navbarDropdownMessages {
            position: relative !important;
            display: inline-flex !important;
            align-items: center !important;
            padding: 0.5rem 0.75rem !important;
        }

        #navbarDropdownMessages i.bi-envelope {
            font-size: 1.25rem !important;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.3rem;
            padding: 0.5rem 0;
            color: #fff !important;
            display: flex;
            align-items: center;
            transition: opacity 0.2s ease;
        }

        .navbar-brand:hover {
            opacity: 0.9;
        }

        .navbar-brand i {
            font-size: 1.5rem;
            margin-right: 0.5rem;
        }

        .nav-link {
            font-weight: 500;
            padding: 0.625rem 1.125rem !important;
            border-radius: 8px;
            margin: 0 0.25rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color: rgba(255, 255, 255, 0.8) !important;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .nav-link:hover, .nav-link:focus {
            background-color: rgba(255, 255, 255, 0.15);
            color: #fff !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .nav-link:hover::before {
            left: 100%;
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: #fff !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .nav-link i {
            margin-right: 0.375rem;
            font-size: 1rem;
        }

        .navbar-toggler {
            border: 2px solid rgba(255, 255, 255, 0.3);
            padding: 0.25rem 0.5rem;
            transition: all 0.2s ease;
        }

        .navbar-toggler:hover {
            border-color: rgba(255, 255, 255, 0.6);
            background-color: rgba(255, 255, 255, 0.1);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 12px;
            padding: 0.75rem 0;
            margin-top: 0.75rem;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.98);
            min-width: 250px;
            width: auto;
        }

        .dropdown-item {
            padding: 0.625rem 1.25rem;
            font-weight: 400;
            border-radius: 8px;
            margin: 0.25rem 0.5rem;
            width: auto;
            transition: all 0.2s ease;
            color: #495057;
            white-space: nowrap;
        }

        .dropdown-item:hover, .dropdown-item:focus {
            background-color: #f8f9fa;
            color: #667eea;
            transform: translateX(4px);
        }

        .dropdown-item i {
            margin-right: 0.5rem;
            opacity: 0.7;
        }

        .dropdown-divider {
            margin: 0.75rem 0;
            border-color: rgba(0, 0, 0, 0.05);
        }

        .dropdown-header {
            font-weight: 600;
            color: #495057;
            padding: 0.5rem 1.25rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Mobile menu styles */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                padding: 1.25rem;
                background: linear-gradient(135deg, rgba(102, 126, 234, 0.95) 0%, rgba(118, 75, 162, 0.95) 100%);
                margin: 0.75rem -1rem 0;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.1);
            }

            .nav-item {
                margin: 0.375rem 0;
            }

            .nav-link {
                color: rgba(255, 255, 255, 0.9) !important;
                padding: 0.75rem 1rem !important;
            }

            .nav-link:hover, .nav-link.active {
                background-color: rgba(255, 255, 255, 0.15);
                color: #fff !important;
            }

            .dropdown-menu {
                margin-left: 1rem;
                margin-top: 0.5rem;
                box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
                background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(248, 249, 250, 0.95) 100%);
                backdrop-filter: blur(10px);
                min-width: 200px;
                width: auto;
            }

            .dropdown-item {
                color: #495057;
                padding: 0.625rem 1rem;
                white-space: nowrap;
            }

            .dropdown-item:hover {
                color: #667eea;
                background-color: rgba(102, 126, 234, 0.1);
            }
        }

        /* Modern Search bar */
        .input-group .form-control {
            min-width: 240px;
            border-radius: 25px 0 0 25px;
            border-right: none;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 0.625rem 1.25rem;
            transition: all 0.3s ease;
        }

        .input-group .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .input-group .form-control:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.3);
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .input-group .btn {
            border-radius: 0 25px 25px 0;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-left: none;
            background-color: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.8);
            padding: 0.625rem 1rem;
            transition: all 0.3s ease;
        }

        .input-group .btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.4);
            color: #fff;
        }
    </style>
</head>
<body class="sb-nav-fixed">
    <!-- Top Navigation -->
    <nav class="sb-topnav navbar navbar-expand-lg navbar-dark bg-dark">
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Navbar Brand -->
        <a class="navbar-brand ps-3" href="{{ route('admin.dashboard') }}">
            <i class="bi bi-shop me-2"></i>
            {{ config('app.name', 'Ceylon Moms') }} Admin
        </a>

        <!-- Navigation Links -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" href="{{ route('admin.products.index') }}">
                        <i class="bi bi-box-seam me-1"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}">
                        <i class="bi bi-tags me-1"></i> Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}" href="{{ route('admin.sliders.index') }}">
                        <i class="bi bi-images me-1"></i> Sliders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                        <i class="bi bi-cart-check me-1"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}" href="{{ route('admin.customers') }}">
                        <i class="bi bi-people me-1"></i> Customers
                    </a>
                </li>
                <li class="nav-item dropdown" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Coming soon">
                    <a class="nav-link dropdown-toggle" href="#" id="activityDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-clock-history me-1"></i> Activity Logs
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="activityDropdown">
                        <li><a class="dropdown-item" href="{{ route('admin.activity-logs.index') }}">
                            <i class="bi bi-list-ul me-2"></i>View All Logs
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.activity-logs.export') }}">
                            <i class="bi bi-download me-2"></i>Export Logs
                        </a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.reports*') ? 'active' : '' }}" href="#" id="reportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-graph-up me-1"></i> Reports
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="reportsDropdown">
                        <li><a class="dropdown-item" href="{{ route('admin.reports.sales') }}">
                            <i class="bi bi-currency-dollar me-2"></i>Sales Report
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.reports.item-wise-sales') }}">
                            <i class="bi bi-box-seam me-2"></i>Item Wise Sales Report
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.reports.district-wise-sales') }}">
                            <i class="bi bi-geo-alt me-2"></i>District Wise Sales Report
                        </a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.reports.monthly-item-demand') }}">
                            <i class="bi bi-calendar-month me-2"></i>Monthly Item Demand Report
                        </a></li>
                    </ul>
                </li>
            </ul>

        </div>



        <!-- User Dropdown -->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4 d-flex align-items-center">
            <!-- Messages Dropdown -->
            <li class="nav-item dropdown me-3">
                <a class="nav-link dropdown-toggle" href="{{ route('admin.chat.index') }}" id="navbarDropdownMessages" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-envelope"></i>
                    <span class="badge rounded-pill bg-danger d-none" id="nav-message-count">
                        <span id="nav-unread-count">0</span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-lg-end shadow" aria-labelledby="navbarDropdownMessages" style="min-width: 320px;">
                    <h6 class="dropdown-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <span><i class="bi bi-chat-dots me-2"></i>Customer Messages</span>
                        <a href="{{ route('admin.chat.index') }}" class="text-white text-decoration-none small">View All</a>
                    </h6>
                    <div id="nav-message-list">
                        <div class="dropdown-item text-center text-muted">
                            <i class="bi bi-chat-square-text" style="font-size: 2rem;"></i>
                            <p class="mt-2 mb-0">No new messages</p>
                        </div>
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('admin.chat.index') }}" class="dropdown-item text-center small text-primary">
                        <i class="bi bi-chat-dots me-1"></i>Go to Chat Interface
                    </a>
                </div>
            </li>

            @push('scripts')
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Load notifications when dropdown is shown
                const notificationDropdown = document.getElementById('navbarDropdownNotifications');
                const notificationContainer = document.getElementById('notification-container');
                let loaded = false;

                notificationDropdown.addEventListener('shown.bs.dropdown', function() {
                    if (!loaded) {
                        loadNotifications();
                        loaded = true;
                    }
                });

                // Mark all as read
                document.querySelector('.mark-all-read')?.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetch('{{ route("admin.notifications.mark-all-read") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update notification count
                            const badge = notificationDropdown.querySelector('.badge');
                            if (badge) badge.remove();
                            loadNotifications();
                        }
                    });
                });

                function loadNotifications() {
                    fetch('{{ route("admin.notifications.latest") }}')
                        .then(response => response.json())
                        .then(data => {
                            const container = document.getElementById('notification-container');
                            const loading = document.getElementById('notification-loading');

                            if (loading) loading.remove();

                            if (data.notifications && data.notifications.length > 0) {
                                container.innerHTML = data.notifications.map(notification => `
                                    <a class="dropdown-item d-flex align-items-center py-2 ${!notification.read_at ? 'bg-light' : ''}"
                                       href="${notification.data.url || '#'}">
                                        <div class="me-3">
                                            <div class="${getNotificationIcon(notification.type).bgClass} rounded-circle p-2">
                                                <i class="${getNotificationIcon(notification.type).icon}"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-medium">${notification.data.title || 'Notification'}</span>
                                                <small class="text-muted ms-2">${formatTime(notification.created_at)}</small>
                                            </div>
                                            <div class="small text-muted">${notification.data.message || ''}</div>
                                        </div>
                                    </a>
                                `).join('');
                            } else {
                                container.innerHTML = `
                                    <div class="text-center py-4 text-muted">
                                        <i class="bi bi-bell-slash fs-4 d-block mb-2"></i>
                                        No new notifications
                                    </div>
                                `;
                            }
                        });
                }

                function getNotificationIcon(type) {
                    const icons = {
                        order: { icon: 'bi-cart', bgClass: 'bg-primary bg-opacity-10 text-primary' },
                        user: { icon: 'bi-person-plus', bgClass: 'bg-success bg-opacity-10 text-success' },
                        system: { icon: 'bi-gear', bgClass: 'bg-warning bg-opacity-10 text-warning' },
                        default: { icon: 'bi-bell', bgClass: 'bg-secondary bg-opacity-10 text-secondary' }
                    };
                    return icons[type] || icons.default;
                }

                function formatTime(dateString) {
                    const date = new Date(dateString);
                    const now = new Date();
                    const diffInSeconds = Math.floor((now - date) / 1000);

                    if (diffInSeconds < 60) return 'Just now';
                    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
                    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
                    return date.toLocaleDateString();
                }
            });
            </script>
            <style>
                .dropdown-menu {
                    max-height: 400px;
                    overflow-y: auto;
                }
                .dropdown-item {
                    white-space: normal;
                    transition: all 0.2s;
                }
                .dropdown-item:hover {
                    background-color: #f8f9fa;
                }
                .notification-unread {
                    background-color: rgba(13, 110, 253, 0.05);
                }
            </style>
            @endpush

            <!-- User Dropdown -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdownUser" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    @php
                        $user = Auth::guard('admin')->user();
                        $adminName = $user ? $user->name : 'Admin';
                    @endphp
                    <div class="me-2 d-none d-lg-inline">
                        <span class="text-light">{{ $adminName }}</span>
                    </div>
                    <div class="avatar avatar-sm">
                        <img class="rounded-circle" src="https://ui-avatars.com/api/?name={{ urlencode($adminName) }}&background=4e73df&color=fff" alt="User">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="navbarDropdownUser">
                    <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}">
                        <i class="bi bi-person me-2"></i> Profile
                    </a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#settingsModal">
                        <i class="bi bi-gear me-2"></i> Settings
                    </a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('admin.logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_content" class="w-100">
            <main class="container px-4 py-4">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mt-4" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="py-4 bg-light mt-auto">
                <div class="container px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; {{ config('app.name') }} {{ date('Y') }}</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('js/admin.js') }}"></script>

    @stack('scripts')

    <script>
        // Initialize tooltips
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Prevent disabled dropdowns from opening
            document.querySelectorAll('.dropdown-toggle[style*="cursor: not-allowed"]').forEach(function(element) {
                element.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                });
            });

            // Update message count in navigation
            function updateNavMessageCount() {
                fetch('{{ route("admin.chat.conversations") }}')
                    .then(response => response.json())
                    .then(data => {
                        const totalUnread = data.conversations.reduce((sum, conv) => sum + conv.unread_count, 0);
                        const badge = document.getElementById('nav-message-count');
                        const count = document.getElementById('nav-unread-count');
                        const messageList = document.getElementById('nav-message-list');

                        if (totalUnread > 0) {
                            count.textContent = totalUnread;
                            badge.classList.remove('d-none');

                            // Update dropdown with recent conversations
                            let dropdownHtml = '';
                            const recentConversations = data.conversations.slice(0, 3);

                            if (recentConversations.length > 0) {
                                recentConversations.forEach(conv => {
                                    dropdownHtml += `
                                        <a href="${conv.user.id}" class="dropdown-item d-flex align-items-center" onclick="window.location.href='{{ route('admin.chat.index') }}'">
                                            <div class="me-3">
                                                ${conv.user.avatar ?
                                                    `<img src="${conv.user.avatar}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" onerror="this.src='{{ asset('images/default-avatar.png') }}';">` :
                                                    `<div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white" style="width: 40px; height: 40px;">
                                                        ${conv.user.name.charAt(0).toUpperCase()}
                                                    </div>`
                                                }
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <strong class="text-dark">${conv.user.name}</strong>
                                                    ${conv.unread_count > 0 ? `<span class="badge bg-danger">${conv.unread_count}</span>` : ''}
                                                </div>
                                                <div class="text-truncate small text-muted">${conv.last_message.message}</div>
                                                <div class="small text-muted">${new Date(conv.last_message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}</div>
                                            </div>
                                        </a>
                                    `;
                                });
                            } else {
                                dropdownHtml = `
                                    <div class="dropdown-item text-center text-muted">
                                        <i class="bi bi-chat-square-text" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0">No new messages</p>
                                    </div>
                                `;
                            }

                            messageList.innerHTML = dropdownHtml;
                        } else {
                            badge.classList.add('d-none');
                            messageList.innerHTML = `
                                <div class="dropdown-item text-center text-muted">
                                    <i class="bi bi-chat-square-text" style="font-size: 2rem;"></i>
                                    <p class="mt-2 mb-0">No new messages</p>
                                </div>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching message count:', error);
                    });
            }

            // Update message count immediately and then every 30 seconds
            updateNavMessageCount();
            setInterval(updateNavMessageCount, 30000);
        });
    </script>
</body>
</html>
