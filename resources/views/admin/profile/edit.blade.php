@extends('admin.layout')

@section('title', 'Profile Management')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Profile Management</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Profile</li>
    </ol>

    
    <div class="row">
        <!-- Profile Information -->
        <div class="col-xl-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-person-circle me-2"></i>Profile Picture
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($admin->avatar)
                            <img src="{{ asset('storage/admin_avatars/'.$admin->avatar) }}" 
                                 class="rounded-circle img-fluid" 
                                 style="width: 150px; height: 150px; object-fit: cover;"
                                 alt="Admin Avatar">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($admin->name) }}&background=4e73df&color=fff&size=150" 
                                 class="rounded-circle img-fluid" 
                                 style="width: 150px; height: 150px; object-fit: cover;"
                                 alt="Admin Avatar">
                        @endif
                    </div>
                    <h5>{{ $admin->name }}</h5>
                    <p class="text-muted">{{ $admin->role ?? 'Administrator' }}</p>
                    <p class="text-muted small">{{ $admin->email }}</p>
                    @if($admin->phone)
                        <p class="text-muted small">{{ $admin->phone }}</p>
                    @endif
                    
                    <div class="mt-3">
                        <form method="POST" action="{{ route('admin.profile.remove-avatar') }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            @if($admin->avatar)
                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                        onclick="return confirm('Are you sure you want to remove your avatar?')">
                                    <i class="bi bi-trash me-1"></i>Remove Avatar
                                </button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>

            <!-- Account Statistics -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-graph-up me-2"></i>Account Statistics
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-2">
                                <small class="text-muted">Member Since</small>
                                <div class="fw-bold">{{ $admin->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-2">
                                <small class="text-muted">Last Login</small>
                                <div class="fw-bold">
                                    {{ $admin->last_login_at ? $admin->last_login_at->format('M d, Y H:i') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-2">
                                <small class="text-muted">Account ID</small>
                                <div class="fw-bold">#{{ $admin->id }}</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-2">
                                <small class="text-muted">Role</small>
                                <div class="fw-bold">{{ ucfirst($admin->role ?? 'admin') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="col-xl-8">
            <!-- Update Profile Information -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-person me-2"></i>Update Profile Information
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $admin->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $admin->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $admin->phone) }}" 
                                       placeholder="Optional">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="avatar" class="form-label">Profile Picture</label>
                                <input type="file" class="form-control @error('avatar') is-invalid @enderror" 
                                       id="avatar" name="avatar" accept="image/*">
                                <div class="form-text">Allowed formats: JPEG, PNG, JPG, GIF (Max 2MB)</div>
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <i class="bi bi-shield-lock me-2"></i>Update Password
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.profile.update-password') }}">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                       id="current_password" name="current_password" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                       id="password_confirmation" name="password_confirmation" required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-shield-check me-2"></i>Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-shield-check me-2"></i>Security Settings
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Two-Factor Authentication</h6>
                            <p class="text-muted small">Add an extra layer of security to your account.</p>
                            <button class="btn btn-outline-success btn-sm" disabled>
                                <i class="bi bi-shield-lock me-1"></i>Enable 2FA (Coming Soon)
                            </button>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Login Activity</h6>
                            <p class="text-muted small">View your recent login history and active sessions.</p>
                            <button class="btn btn-outline-info btn-sm" disabled>
                                <i class="bi bi-clock-history me-1"></i>View Activity (Coming Soon)
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview avatar before upload
    const avatarInput = document.getElementById('avatar');
    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // You could add a preview modal here if needed
                    console.log('Avatar selected:', file.name);
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Password strength indicator (optional enhancement)
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    
    if (passwordInput && passwordConfirmInput) {
        passwordConfirmInput.addEventListener('input', function() {
            if (passwordInput.value !== passwordConfirmInput.value) {
                passwordConfirmInput.setCustomValidity('Passwords do not match');
            } else {
                passwordConfirmInput.setCustomValidity('');
            }
        });
    }
});
</script>
@endpush
