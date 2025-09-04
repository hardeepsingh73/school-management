<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <x-application-logo />
                            <h2 class="h3 fw-bold">{{ __('Create Account') }}</h2>
                            <p class="text-muted mb-4">{{ __('Join us today! Fill out the form to get started.') }}</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Full Name') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-person"></i>
                                    </span>
                                    <input id="name" type="text" name="name"
                                        class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                        value="{{ old('name') }}" required autofocus autocomplete="name"
                                        placeholder="John Doe">
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope"></i>
                                    </span>
                                    <input id="email" type="email" name="email"
                                        class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                        value="{{ old('email') }}" required autocomplete="email"
                                        placeholder="your@email.com">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input id="password" type="password" name="password"
                                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                        required autocomplete="new-password" placeholder="••••••••">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                                <small class="text-muted">Minimum 8 characters</small>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <label for="password_confirmation"
                                    class="form-label">{{ __('Confirm Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input id="password_confirmation" type="password" name="password_confirmation"
                                        class="form-control" required autocomplete="new-password"
                                        placeholder="••••••••">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-person-plus me-2"></i>
                                    {{ __('Register') }}
                                </button>
                            </div>

                            <!-- Login Link -->
                            <div class="text-center">
                                <p class="mb-0">{{ __('Already have an account?') }}
                                    <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>
                                        {{ __('Login') }}
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
