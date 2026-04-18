<section>
    <p class="text-muted mb-4">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </p>

    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="mb-3">
            <label for="update_password_current_password" class="form-label fw-medium">
                <i class="bi bi-key me-1"></i> {{ __('Current Password') }}
            </label>
            <div class="input-group">
                <input id="update_password_current_password" name="current_password" type="password" 
                    class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                    autocomplete="current-password">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="update_password_current_password">
                    <i class="bi bi-eye"></i>
                </button>
                @error('current_password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="update_password_password" class="form-label fw-medium">
                <i class="bi bi-shield-lock me-1"></i> {{ __('New Password') }}
            </label>
            <div class="input-group">
                <input id="update_password_password" name="password" type="password" 
                    class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                    autocomplete="new-password">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="update_password_password">
                    <i class="bi bi-eye"></i>
                </button>
                @error('password', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="update_password_password_confirmation" class="form-label fw-medium">
                <i class="bi bi-check-circle me-1"></i> {{ __('Confirm Password') }}
            </label>
            <div class="input-group">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" 
                    class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                    autocomplete="new-password">
                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="update_password_password_confirmation">
                    <i class="bi bi-eye"></i>
                </button>
                @error('password_confirmation', 'updatePassword')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mt-4">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-check-circle me-1"></i> {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success d-inline-flex align-items-center p-2 mb-0 ms-2" id="password-status-message">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ __('Saved.') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('password-status-message').style.display = 'none';
                    }, 2000);
                </script>
            @endif
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButtons = document.querySelectorAll('.toggle-password');
            
            toggleButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const passwordInput = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        passwordInput.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            });
        });
    </script>
</section>
