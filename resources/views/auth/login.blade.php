@extends('layouts.app')
@section('title', 'Login')

@section('content')
<style>
.login-bg {
    background: #ffffff;
    min-height: 100vh;
    display: flex;
    align-items: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 2rem 0;
}

.login-container {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.login-header {
    background: linear-gradient(135deg, #c52b36 0%, #e74c3c 100%);
    color: white;
    padding: 2rem;
    text-align: center;
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
    border-color: #c52b36;
    box-shadow: 0 0 0 0.2rem rgba(197, 43, 54, 0.25);
    background: white;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.btn-login {
    background: linear-gradient(135deg, #c52b36 0%, #e74c3c 100%);
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(197, 43, 54, 0.3);
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(197, 43, 54, 0.4);
    background: linear-gradient(135deg, #a02630 0%, #c0392b 100%);
}

.form-check-input:checked {
    background-color: #c52b36;
    border-color: #c52b36;
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

.social-login-btn {
    border: 2px solid #e9ecef;
    border-radius: 10px;
    padding: 10px;
    transition: all 0.3s ease;
    background: white;
}

.social-login-btn:hover {
    border-color: #c52b36;
    background: #f8f9fa;
}

.forgot-link {
    color: #c52b36;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.forgot-link:hover {
    color: #a02630;
    text-decoration: underline;
}

.register-link {
    color: #c52b36;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.register-link:hover {
    color: #a02630;
    text-decoration: underline;
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
                        <div class="mb-3">
                            <img src="{{ asset('logo/logo.png') }}" alt="VegiShop Logo" height="50" class="me-2">
                        </div>
                        <h2 class="mb-2 fw-bold">Welcome Back!</h2>
                        <p class="mb-0 opacity-90">Sign in to continue to your account</p>
                    </div>
                    
                    <div class="login-body">
                        <!-- Flash messages will be handled by SweetAlert2 automatically -->
                        
                        <form method="POST" action="{{ route('login') }}" id="loginForm">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-2"></i>Email Address
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control with-icon" name="email" 
                                           value="{{ old('email') }}" required autofocus autocomplete="username" 
                                           placeholder="Enter your email">
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
                                    <input id="password" type="password" class="form-control with-icon" 
                                           name="password" required autocomplete="current-password" 
                                           placeholder="Enter your password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember_me" name="remember">
                                    <label class="form-check-label" for="remember_me">
                                        <small>Remember me</small>
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="forgot-link small" href="{{ route('password.request') }}">
                                        Forgot password?
                                    </a>
                                @endif
                            </div>
                            
                            <button type="submit" class="btn btn-login btn-success w-100 mb-4">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                            </button>
                            
                            <div class="text-center">
                                <small class="text-muted">
                                    Don't have an account? 
                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}" class="register-link">Sign Up</a>
                                    @endif
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
