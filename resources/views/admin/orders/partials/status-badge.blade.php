@switch($status)
    @case('pending')
        <span class="badge bg-warning text-dark">
            <i class="bi bi-clock me-1"></i>Pending
        </span>
        @break
    @case('processing')
        <span class="badge bg-info">
            <i class="bi bi-gear me-1"></i>Processing
        </span>
        @break
    @case('completed')
        <span class="badge bg-success">
            <i class="bi bi-check-circle me-1"></i>Completed
        </span>
        @break
    @case('cancelled')
        <span class="badge bg-danger">
            <i class="bi bi-x-circle me-1"></i>Cancelled
        </span>
        @break
    @default
        <span class="badge bg-secondary">
            <i class="bi bi-question-circle me-1"></i>{{ ucfirst($status) }}
        </span>
@endswitch
