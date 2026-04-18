@extends('layouts.app')
@section('title', 'Register')

@section('content')
<style>
.register-bg {
    background: #ffffff;
    min-height: 100vh;
    display: flex;
    align-items: center;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    padding: 2rem 0;
}

.register-container {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.register-header {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 2rem;
    text-align: center;
}

.register-body {
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
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    background: white;
}

.form-label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 8px;
}

.btn-register {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.btn-register:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
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

.login-link {
    color: #28a745;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.login-link:hover {
    color: #218838;
    text-decoration: underline;
}

@media (max-width: 576px) {
    .register-body {
        padding: 2rem 1.5rem;
    }
}
</style>

<div class="register-bg">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="register-container">
                    <div class="register-header">
                        <div class="mb-3">
                            <img src="{{ asset('logo/logo.png') }}" alt="VegiShop Logo" height="50" class="me-2">
                        </div>
                        <h2 class="mb-2 fw-bold">Create Account!</h2>
                        <p class="mb-0 opacity-90">Join us and start shopping today</p>
                    </div>
                    
                    <div class="register-body">
                        <!-- Flash messages will be handled by SweetAlert2 automatically -->
                        
                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person me-2"></i>Full Name
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input id="name" type="text" class="form-control with-icon" name="name" 
                                           value="{{ old('name') }}" required autofocus autocomplete="name" 
                                           placeholder="Enter your full name">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope me-2"></i>Email Address
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control with-icon" name="email" 
                                           value="{{ old('email') }}" required autocomplete="username" 
                                           placeholder="Enter your email">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-2"></i>Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control with-icon" 
                                           name="password" required autocomplete="new-password" 
                                           placeholder="Create a password">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="toggleIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill me-2"></i>Confirm Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input id="password_confirmation" type="password" class="form-control with-icon" 
                                           name="password_confirmation" required autocomplete="new-password" 
                                           placeholder="Confirm your password">
                                    <button class="btn btn-outline-secondary" type="button" id="toggleConfirmPassword">
                                        <i class="bi bi-eye" id="toggleConfirmIcon"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-register btn-success w-100 mb-4">
                                <i class="bi bi-person-plus me-2"></i>Create Account
                            </button>
                            
                            <div class="text-center">
                                <small class="text-muted">
                                    Already have an account? 
                                    <a href="{{ route('login') }}" class="login-link">Sign In</a>
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
    // Toggle password visibility for password field
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
    
    // Toggle password visibility for confirm password field
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const toggleConfirmIcon = document.getElementById('toggleConfirmIcon');
    
    if (toggleConfirmPassword) {
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            
            if (type === 'text') {
                toggleConfirmIcon.classList.remove('bi-eye');
                toggleConfirmIcon.classList.add('bi-eye-slash');
            } else {
                toggleConfirmIcon.classList.remove('bi-eye-slash');
                toggleConfirmIcon.classList.add('bi-eye');
            }
        });
    }
    
    // Add loading state on form submit
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function() {
            const submitBtn = registerForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Creating Account...';
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
