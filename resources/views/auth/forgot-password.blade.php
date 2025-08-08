<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <i class="bi bi-shield-lock fs-1 text-primary mb-3"></i>
                            <h2 class="h3 fw-bold">{{ __('Reset Password') }}</h2>
                            <p class="text-muted mb-0">
                                {{ __('Forgot your password? Enter your email and we\'ll send you a reset link.') }}
                            </p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="alert alert-success mb-4" :status="session('status')" />

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <!-- Email Input -->
                            <div class="mb-4">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        value="{{ old('email') }}"
                                        required
                                        autofocus
                                        placeholder="your@email.com"
                                    >
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-send-check me-2"></i>
                                    {{ __('Email Password Reset Link') }}
                                </button>
                            </div>

                            <!-- Back to Login -->
                            <div class="text-center">
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    <i class="bi bi-arrow-left me-1"></i>
                                    {{ __('Back to Login') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>