@extends('layouts.app')

@section('content')
<div class="profile-modern">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Page Header -->
                <div class="profile-header mb-4">
                    <div class="d-flex align-items-center">
                        <div class="profile-header-icon">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <div>
                            <h1 class="profile-title mb-1">Profile Settings</h1>
                            <p class="profile-subtitle mb-0">Manage your account information and security settings</p>
                        </div>
                    </div>
                </div>

                <!-- Profile Information Card -->
                <div class="profile-card mb-4">
                    <div class="profile-card-header">
                        <div class="d-flex align-items-center">
                            <div class="profile-card-icon profile-icon">
                                <i class="bi bi-person-gear"></i>
                            </div>
                            <div>
                                <h3 class="profile-card-title mb-0">Profile Information</h3>
                                <p class="profile-card-subtitle mb-0">Update your account's profile information and email address</p>
                            </div>
                        </div>
                    </div>
                    <div class="profile-card-body">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
                
                <!-- Security Card -->
                <div class="profile-card mb-4">
                    <div class="profile-card-header">
                        <div class="d-flex align-items-center">
                            <div class="profile-card-icon security-icon">
                                <i class="bi bi-shield-lock"></i>
                            </div>
                            <div>
                                <h3 class="profile-card-title mb-0">Update Password</h3>
                                <p class="profile-card-subtitle mb-0">Ensure your account is using a strong password to stay secure</p>
                            </div>
                        </div>
                    </div>
                    <div class="profile-card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
                
                <!-- Danger Zone Card -->
                <div class="profile-card danger-card">
                    <div class="profile-card-header">
                        <div class="d-flex align-items-center">
                            <div class="profile-card-icon danger-icon">
                                <i class="bi bi-exclamation-triangle"></i>
                            </div>
                            <div>
                                <h3 class="profile-card-title mb-0">Delete Account</h3>
                                <p class="profile-card-subtitle mb-0">Permanently delete your account and all associated data</p>
                            </div>
                        </div>
                    </div>
                    <div class="profile-card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Profile Modern Styles */
.profile-modern {
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
    padding-top: 2rem;
    padding-bottom: 2rem;
}

/* Profile Header */
.profile-header {
    background: white;
    padding: 2rem;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.profile-header-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 36px;
    margin-right: 1.5rem;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
}

.profile-title {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
}

.profile-subtitle {
    color: #6c757d;
    font-size: 1rem;
}

/* Profile Cards */
.profile-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    overflow: hidden;
    transition: all 0.3s ease;
}

.profile-card:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
}

.profile-card-header {
    padding: 1.5rem 2rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.profile-card-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    margin-right: 1rem;
    flex-shrink: 0;
}

.profile-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
}

.security-icon {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.25);
}

.danger-icon {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
}

.profile-card-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2c3e50;
}

.profile-card-subtitle {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.profile-card-body {
    padding: 2rem;
}

/* Danger Card Special Styling */
.danger-card {
    border-color: #dc3545;
    border-width: 2px;
}

.danger-card .profile-card-header {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.05) 0%, rgba(200, 35, 51, 0.05) 100%);
    border-bottom-color: rgba(220, 53, 69, 0.2);
}

/* Form Styling Enhancements */
.profile-card-body .form-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.profile-card-body .form-control {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    padding: 0.75rem 1rem;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
}

.profile-card-body .form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.profile-card-body .btn {
    border-radius: 8px;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    font-size: 0.9375rem;
    transition: all 0.3s ease;
}

.profile-card-body .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
}

.profile-card-body .btn-primary:hover {
    background: linear-gradient(135deg, #5568d3 0%, #63408b 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.35);
}

.profile-card-body .btn-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border: none;
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
}

.profile-card-body .btn-danger:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(220, 53, 69, 0.35);
}

.profile-card-body .btn-secondary {
    background: #6c757d;
    border: none;
}

.profile-card-body .btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

/* Alert Styling */
.profile-card-body .alert {
    border-radius: 8px;
    border: none;
    padding: 1rem 1.25rem;
}

.profile-card-body .alert-success {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(32, 201, 151, 0.1) 100%);
    color: #155724;
    border-left: 4px solid #28a745;
}

.profile-card-body .alert-danger {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(200, 35, 51, 0.1) 100%);
    color: #721c24;
    border-left: 4px solid #dc3545;
}

/* Input Group Styling */
.profile-card-body .input-group {
    border-radius: 8px;
    overflow: hidden;
}

.profile-card-body .input-group-text {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-right: none;
    color: #6c757d;
}

/* Responsive Design */
@media (max-width: 768px) {
    .profile-modern {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    .profile-header {
        padding: 1.5rem;
    }
    
    .profile-header-icon {
        width: 60px;
        height: 60px;
        font-size: 30px;
        margin-right: 1rem;
    }
    
    .profile-title {
        font-size: 1.5rem;
    }
    
    .profile-subtitle {
        font-size: 0.875rem;
    }
    
    .profile-card-header {
        padding: 1.25rem 1.5rem;
    }
    
    .profile-card-body {
        padding: 1.5rem;
    }
    
    .profile-card-icon {
        width: 45px;
        height: 45px;
        font-size: 20px;
    }
    
    .profile-card-title {
        font-size: 1.1rem;
    }
}

/* Animation */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.profile-header,
.profile-card {
    animation: fadeInUp 0.5s ease-out;
}

.profile-card:nth-child(2) { animation-delay: 0.1s; }
.profile-card:nth-child(3) { animation-delay: 0.2s; }
.profile-card:nth-child(4) { animation-delay: 0.3s; }
</style>
@endpush
