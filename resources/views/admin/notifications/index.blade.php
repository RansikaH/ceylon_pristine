@extends('admin.layout')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Notifications</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Notifications</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-bell me-1"></i>
                    All Notifications
                </div>
                <div>
                    <a href="#" class="btn btn-sm btn-outline-primary mark-all-read" data-url="{{ route('notifications.mark-all-read') }}">
                        <i class="bi bi-check2-all me-1"></i> Mark all as read
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if($notifications->count() > 0)
                <div class="list-group list-group-flush">
                    @foreach($notifications as $notification)
                        <a href="{{ $notification->data['url'] ?? '#' }}" 
                           class="list-group-item list-group-item-action {{ $notification->unread() ? 'bg-light' : '' }}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                <small>{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                            <p class="mb-1">{{ $notification->data['message'] ?? '' }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    @if($notification->read_at)
                                        <i class="bi bi-check2-all text-success"></i> Read
                                    @else
                                        <i class="bi bi-dot"></i> Unread
                                    @endif
                                </small>
                                <div>
                                    @if($notification->unread())
                                        <button class="btn btn-sm btn-link text-primary mark-as-read" 
                                                data-id="{{ $notification->id }}">
                                            Mark as read
                                        </button>
                                    @endif
                                    <button class="btn btn-sm btn-link text-danger delete-notification" 
                                            data-id="{{ $notification->id }}">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash fs-1 text-muted"></i>
                    <p class="mt-3 text-muted">No notifications found</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mark as read
    document.querySelectorAll('.mark-as-read').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const notificationId = this.getAttribute('data-id');
            const url = `/notifications/${notificationId}/mark-as-read`;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({})
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = this.closest('.list-group-item');
                    item.classList.remove('bg-light');
                    this.remove();
                    
                    // Update notification count in navbar
                    updateNotificationCount(-1);
                }
            });
        });
    });
    
    // Mark all as read
    document.querySelector('.mark-all-read')?.addEventListener('click', function(e) {
        e.preventDefault();
        
        const url = this.getAttribute('data-url');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.list-group-item').forEach(item => {
                    item.classList.remove('bg-light');
                });
                
                document.querySelectorAll('.mark-as-read').forEach(btn => btn.remove());
                
                // Update notification count in navbar
                updateNotificationCount(-999); // Will be set to 0
            }
        });
    });
    
    // Delete notification
    document.querySelectorAll('.delete-notification').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (!confirm('Are you sure you want to delete this notification?')) {
                return;
            }
            
            const notificationId = this.getAttribute('data-id');
            const url = `/notifications/${notificationId}`;
            const isUnread = this.closest('.list-group-item').classList.contains('bg-light');
            
            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.closest('.list-group-item').remove();
                    
                    // Update notification count in navbar if the notification was unread
                    if (isUnread) {
                        updateNotificationCount(-1);
                    }
                    
                    // Check if there are no more notifications
                    if (document.querySelectorAll('.list-group-item').length === 0) {
                        const container = document.querySelector('.card-body');
                        container.innerHTML = `
                            <div class="text-center py-5">
                                <i class="bi bi-bell-slash fs-1 text-muted"></i>
                                <p class="mt-3 text-muted">No notifications found</p>
                            </div>
                        `;
                    }
                }
            });
        });
    });
    
    function updateNotificationCount(change) {
        const badge = document.querySelector('#navbarDropdownNotifications .badge');
        if (!badge) return;
        
        let count = parseInt(badge.textContent);
        if (isNaN(count)) count = 0;
        
        count += change;
        
        if (count <= 0) {
            badge.remove();
        } else {
            badge.textContent = count > 9 ? '9+' : count;
        }
    }
});
</script>
@endpush
@endsection
