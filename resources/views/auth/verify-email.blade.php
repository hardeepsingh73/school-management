<x-guest-layout>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-body p-4 p-md-5">
                        <!-- Header -->
                        <div class="text-center mb-4">
                            <i class="bi bi-envelope-check fs-1 text-primary mb-3"></i>
                            <h2 class="h3 fw-bold">{{ __('Verify Your Email') }}</h2>
                        </div>

                        <!-- Instructions -->
                        <div class="alert alert-info mb-4">
                            <i class="bi bi-info-circle-fill me-2"></i>
                            {{ __('Thanks for signing up! Please verify your email address by clicking the link we sent to your inbox.') }}
                        </div>

                        <!-- Success Message -->
                        @if (session('status') == 'verification-link-sent')
                            <div class="alert alert-success mb-4">
                                <i class="bi bi-check-circle-fill me-2"></i>
                                {{ __('A new verification link has been sent to your email address.') }}
                            </div>
                        @endif

                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
                            <!-- Resend Form -->
                            <form method="POST" action="{{ route('verification.send') }}" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-envelope-arrow-up me-2"></i>
                                    {{ __('Resend Verification Email') }}
                                </button>
                            </form>

                            <!-- Logout Form -->
                            <form method="POST" action="{{ route('logout') }}" class="w-100">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    {{ __('Log Out') }}
                                </button>
                            </form>
                        </div>

                        <!-- Additional Help -->
                        <div class="mt-4 text-center">
                            <p class="text-muted small mb-2">{{ __("Didn't receive the email?") }}</p>
                            <ul class="list-unstyled small">
                                <li><i class="bi bi-check-circle text-primary me-1"></i>
                                    {{ __('Check your spam folder') }}</li>
                                <li><i class="bi bi-check-circle text-primary me-1"></i>
                                    {{ __('Ensure you entered the correct email') }}</li>
                                <li><i class="bi bi-check-circle text-primary me-1"></i>
                                    {{ __('Wait a few minutes and try again') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
