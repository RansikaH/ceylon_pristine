<section>
    <p class="text-muted mb-4">
        {{ __("Update your account's profile information and email address.") }}
    </p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="name" class="form-label fw-medium">
                <i class="bi bi-person me-1"></i> {{ __('Name') }}
            </label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-medium">
                <i class="bi bi-envelope me-1"></i> {{ __('Email') }}
            </label>
            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-2 p-2">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <div>
                            <p class="mb-1">{{ __('Your email address is unverified.') }}</p>
                            <button form="send-verification" class="btn btn-sm btn-link p-0 text-decoration-underline">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mt-2 p-2">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <div class="d-flex align-items-center justify-content-between mt-4">
            <button type="submit" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-check-circle me-1"></i> {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success d-inline-flex align-items-center p-2 mb-0 ms-2" id="status-message">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ __('Saved.') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('status-message').style.display = 'none';
                    }, 2000);
                </script>
            @endif
        </div>
    </form>
</section>
