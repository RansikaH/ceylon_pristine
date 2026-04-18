@extends('admin.layout')

@section('title', 'Activity Logs - Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Activity Logs</h1>
            <p class="text-muted mb-0">Monitor and track user activities across the system</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-danger btn-modern" data-bs-toggle="modal" data-bs-target="#clearLogsModal">
                <i class="bi bi-trash me-2"></i>Clear Old Logs
            </button>
            <a href="{{ route('admin.activity-logs.export') }}" class="btn btn-success btn-modern">
                <i class="bi bi-download me-2"></i>Export CSV
            </a>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white border-0">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-funnel me-2"></i>Filters
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="action" class="form-label">Action</label>
                    <select name="action" id="action" class="form-select">
                        <option value="">All Actions</option>
                        @foreach($availableActions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $action)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="user_type" class="form-label">User Type</label>
                    <select name="user_type" id="user_type" class="form-select">
                        <option value="">All Users</option>
                        @foreach($availableUserTypes as $userType)
                            <option value="{{ $userType }}" {{ request('user_type') == $userType ? 'selected' : '' }}>
                                {{ $userType }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_from" class="form-label">From Date</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label for="date_to" class="form-label">To Date</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-md-2">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Search description..." value="{{ request('search') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                @if(request()->hasAny(['action', 'user_type', 'date_from', 'date_to', 'search']))
                    <div class="col-12">
                        <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x-circle me-2"></i>Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-0">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-clock-history me-2"></i>Recent Activities
            </h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Description</th>
                            <th>Subject</th>
                            <th>IP Address</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td><span class="badge bg-secondary">#{{ $activity->id }}</span></td>
                                <td>
                                    @if($activity->user)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-xs me-2">
                                                <img class="rounded-circle" src="https://ui-avatars.com/api/?name={{ urlencode($activity->user->name ?? 'Unknown') }}&background=4e73df&color=fff" alt="User">
                                            </div>
                                            <div>
                                                <div class="fw-medium">{{ $activity->user->name ?? 'Unknown' }}</div>
                                                <small class="text-muted">{{ class_basename($activity->user_type) }}</small>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $activity->action_badge_color }}">
                                        {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $activity->description }}">
                                        {{ $activity->description }}
                                    </div>
                                </td>
                                <td>
                                    @if($activity->subject)
                                        <small>
                                            <strong>{{ class_basename($activity->subject_type) }}</strong>
                                            @if($activity->subject_id)
                                                #{{ $activity->subject_id }}
                                            @endif
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $activity->ip_address ?? '-' }}</small>
                                </td>
                                <td>
                                    <small>{{ $activity->created_at->format('M d, Y H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.activity-logs.show', $activity) }}" 
                                           class="btn btn-outline-primary" title="View Details">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="bi bi-clock-history fs-4 d-block mb-2 text-muted"></i>
                                    No activity logs found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($activities->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $activities->links() }}
            </div>
        @endif
    </div>
</div>

<!-- Clear Logs Modal -->
<div class="modal fade" id="clearLogsModal" tabindex="-1" aria-labelledby="clearLogsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clearLogsModalLabel">Clear Old Activity Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="clearLogsForm" method="POST" action="{{ route('admin.activity-logs.clear') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="days" class="form-label">Delete logs older than</label>
                        <select name="days" id="days" class="form-select" required>
                            <option value="7">7 days</option>
                            <option value="30">30 days</option>
                            <option value="90">90 days</option>
                            <option value="180">6 months</option>
                            <option value="365">1 year</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone. All activity logs older than the selected period will be permanently deleted.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Clear Logs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle clear logs form submission
    const clearLogsForm = document.getElementById('clearLogsForm');
    if (clearLogsForm) {
        clearLogsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('Are you sure you want to delete old activity logs? This action cannot be undone.')) {
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal and show success message
                        const modal = bootstrap.Modal.getInstance(document.getElementById('clearLogsModal'));
                        modal.hide();
                        
                        // Show success alert
                        const alertHtml = `
                            <div class="alert alert-success alert-dismissible fade show mt-4" role="alert">
                                <i class="bi bi-check-circle me-2"></i>${data.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `;
                        document.querySelector('.container-fluid').insertAdjacentHTML('afterbegin', alertHtml);
                        
                        // Reload page after 2 seconds
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while clearing logs.');
                });
            }
        });
    }
    
    // Auto-refresh logs every 30 seconds
    setInterval(() => {
        if (!document.hidden) {
            // Only refresh if user is not on a filtered view
            const hasFilters = window.location.search.includes('?') && 
                              (window.location.search.includes('action=') || 
                               window.location.search.includes('user_type=') || 
                               window.location.search.includes('date_from=') || 
                               window.location.search.includes('search='));
            
            if (!hasFilters) {
                window.location.reload();
            }
        }
    }, 30000);
});
</script>
@endpush
@endsection
