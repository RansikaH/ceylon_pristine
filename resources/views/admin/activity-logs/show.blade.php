@extends('admin.layout')

@section('title', 'Activity Log Details - Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="h3 mb-1">Activity Log Details</h1>
            <p class="text-muted mb-0">Detailed information about this activity</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary btn-modern">
                <i class="bi bi-arrow-left me-2"></i>Back to Logs
            </a>
        </div>
    </div>

    <!-- Activity Details Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white border-0">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-info-circle me-2"></i>Activity Information
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted fw-medium" style="width: 150px;">Log ID:</td>
                            <td><span class="badge bg-secondary">#{{ $activityLog->id }}</span></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-medium">Action:</td>
                            <td>
                                <span class="badge bg-{{ $activityLog->action_badge_color }} text-uppercase">
                                    {{ ucfirst(str_replace('_', ' ', $activityLog->action)) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-medium">Description:</td>
                            <td>{{ $activityLog->description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-medium">Date:</td>
                            <td>{{ $activityLog->created_at->format('F d, Y H:i:s A') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-medium">IP Address:</td>
                            <td>
                                <code>{{ $activityLog->ip_address ?? 'Not recorded' }}</code>
                                @if($activityLog->ip_address)
                                    <a href="https://www.ipinfo.io/{{ $activityLog->ip_address }}" target="_blank" class="ms-2 text-muted">
                                        <i class="bi bi-box-arrow-up-right" title="Lookup IP"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted fw-medium" style="width: 150px;">Session ID:</td>
                            <td><code class="text-break">{{ $activityLog->session_id ?? 'Not recorded' }}</code></td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-medium">User Agent:</td>
                            <td class="text-break" style="font-size: 12px;">
                                {{ $activityLog->user_agent ?? 'Not recorded' }}
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-medium">Time Ago:</td>
                            <td>{{ $activityLog->created_at->diffForHumans() }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-medium">Date (ISO):</td>
                            <td>{{ $activityLog->created_at->toISOString() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white border-0">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-person me-2"></i>User Information
            </h6>
        </div>
        <div class="card-body">
            @if($activityLog->user)
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="avatar avatar-lg mb-3">
                                <img class="rounded-circle" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode($activityLog->user->name ?? 'Unknown') }}&background=4e73df&color=fff&size=128" 
                                     alt="User Avatar">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted fw-medium" style="width: 150px;">User ID:</td>
                                <td>#{{ $activityLog->user->id }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Name:</td>
                                <td>{{ $activityLog->user->name ?? 'Unknown' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">Email:</td>
                                <td>{{ $activityLog->user->email ?? 'Not available' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">User Type:</td>
                                <td><span class="badge bg-info">{{ class_basename($activityLog->user_type) }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted fw-medium">User Model:</td>
                                <td><code>{{ $activityLog->user_type }}</code></td>
                            </tr>
                        </table>
                    </div>
                </div>
            @else
                <div class="text-center text-muted py-3">
                    <i class="bi bi-person-x fs-4 d-block mb-2"></i>
                    This activity was performed by the system
                </div>
            @endif
        </div>
    </div>

    <!-- Subject Information -->
    @if($activityLog->subject)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-box me-2"></i>Subject Information
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted fw-medium" style="width: 150px;">Subject Type:</td>
                        <td><span class="badge bg-info">{{ class_basename($activityLog->subject_type) }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium">Subject ID:</td>
                        <td>#{{ $activityLog->subject_id }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted fw-medium">Subject Model:</td>
                        <td><code>{{ $activityLog->subject_type }}</code></td>
                    </tr>
                    @if(isset($activityLog->subject->name))
                        <tr>
                            <td class="text-muted fw-medium">Name:</td>
                            <td>{{ $activityLog->subject->name }}</td>
                        </tr>
                    @endif
                    @if(isset($activityLog->subject->email))
                        <tr>
                            <td class="text-muted fw-medium">Email:</td>
                            <td>{{ $activityLog->subject->email }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    @endif

    <!-- Properties -->
    @if($activityLog->properties && !empty($activityLog->properties))
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white border-0">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="bi bi-list-ul me-2"></i>Additional Properties
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 200px;">Property</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activityLog->properties as $key => $value)
                                <tr>
                                    <td class="fw-medium">{{ $key }}</td>
                                    <td>
                                        @if(is_array($value))
                                            <pre class="mb-0"><code>{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                                        @else
                                            {{ is_bool($value) ? ($value ? 'true' : 'false') : $value }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Technical Details -->
    <div class="card shadow-sm">
        <div class="card-header bg-white border-0">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="bi bi-gear me-2"></i>Technical Details
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted fw-medium" style="width: 150px;">Created At:</td>
                            <td>{{ $activityLog->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-medium">Updated At:</td>
                            <td>{{ $activityLog->updated_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr>
                            <td class="text-muted fw-medium" style="width: 150px;">Timestamp:</td>
                            <td>{{ $activityLog->created_at->timestamp }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-medium">Timezone:</td>
                            <td>{{ config('app.timezone') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
