@extends('layouts.app')
@section('title', 'Admin Login')

@section('content')
<style>
.login-bg {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    display: flex;
    align-items: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 2rem 0;
}

.login-container {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(255, 255, 255, 0.2);
    overflow: hidden;
}

.login-header {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    color: white;
    padding: 2.5rem 2rem;
    text-align: center;
}

.admin-badge {
    background: rgba(255, 255, 255, 0.2);
    padding: 8px 20px;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 1rem;
    font-size: 0.9rem;
    font-weight: 600;
    letter-spacing: 1px;
}

.login-body {
    padding: 3rem 2.5rem;
}

.form-control {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 12px 15px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control:focus {
    border-color: #2a5298;
    box-shadow: 0 0 0 0.2rem rgba(42, 82, 152, 0.25);
    background: white;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.btn-login {
    background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(30, 60, 114, 0.4);
    background: linear-gradient(135deg, #152a52 0%, #1e3c72 100%);
}

.form-check-input:checked {
    background-color: #2a5298;
    border-color: #2a5298;
}

.input-group-text {
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-right: none;
    border-radius: 10px 0 0 10px;
}

.form-control.with-icon {
    border-left: none;
    border-radius: 0 10px 10px 0;
}

.alert {
    border-radius: 10px;
    border: none;
    padding: 1rem 1.25rem;
}

.alert-danger {
    background: #fee;
    color: #c33;
}

@media (max-width: 576px) {
    .login-body {
        padding: 2rem 1.5rem;
    }
}
</style>

<div class="login-bg">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="login-container">
                    <div class="login-header">
                        <div class="admin-badge">
                            <i class="bi bi-shield-lock-fill me-2"></i>ADMIN PANEL
                        </div>
                        <h2 class="mb-2 fw-bold">Admin Login</h2>
                        <p class="mb-0 opacity-90">Sign in to access the admin dashboard</p>
                    </div>
                    
                    <div class="login-body">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                @foreach ($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mb-4">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-2"></i>Email Address
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control with-icon @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                                           placeholder="Enter your admin email">
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-2"></i>Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control with-icon @error('password') is-invalid @enderror" 
                                           name="password" required autocomplete="current-password" 
                                           placeholder="Enter your password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">
                                        <small>Remember me</small>
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-login btn-primary w-100 mb-3">
                                <i class="bi bi-shield-lock me-2"></i>Sign In as Admin
                            </button>
                            
                            <div class="text-center">
                                <small class="text-muted">
                                    <a href="{{ route('shop.home') }}" class="text-decoration-none">
                                        <i class="bi bi-arrow-left me-1"></i>Back to Shop
                                    </a>
                                </small>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (togglePassword) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        });
    }
    
    // Add loading state on form submit
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function() {
            const submitBtn = loginForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Signing in...';
            submitBtn.disabled = true;
            
            // Re-enable after 5 seconds in case of issues
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 5000);
        });
    }
});
</script>
@endsection
