<section>
    <p class="text-muted mb-4">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

    <button type="button" class="btn btn-danger rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
        <i class="bi bi-trash me-1"></i> {{ __('Delete Account') }}
    </button>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="confirmDeleteModalLabel" style="color: #dc3545;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        {{ __('Are you sure you want to delete your account?') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                        @csrf
                        @method('delete')

                        <p class="text-muted mb-4">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-medium">
                                <i class="bi bi-key me-1"></i> {{ __('Password') }}
                            </label>
                            <div class="input-group">
                                <input id="password" name="password" type="password" 
                                    class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                                    placeholder="{{ __('Enter your password') }}">
                                <button class="btn btn-outline-secondary toggle-password" type="button" data-target="password">
                                    <i class="bi bi-eye"></i>
                                </button>
                                @error('password', 'userDeletion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> {{ __('Cancel') }}
                    </button>
                    <button type="submit" form="deleteAccountForm" class="btn btn-danger rounded-pill">
                        <i class="bi bi-trash me-1"></i> {{ __('Delete Account') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show modal if there are validation errors
            @if($errors->userDeletion->isNotEmpty())
                new bootstrap.Modal(document.getElementById('confirmDeleteModal')).show();
            @endif

            // Password toggle functionality
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
