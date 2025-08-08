<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <i class="bi bi-shield-lock fs-1 text-primary mb-3"></i>
                            <h2 class="h3 fw-bold">{{ __('Security Check') }}</h2>
                            <p class="text-muted mb-4">
                                {{ __('This is a secure area of the application. Please confirm your password to continue.') }}
                            </p>
                        </div>

                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <!-- Password Field -->
                            <div class="mb-4">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input id="password" type="password" name="password"
                                        class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" required
                                        autocomplete="current-password" placeholder="Enter your password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-shield-check me-2"></i>
                                    {{ __('Confirm Password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
