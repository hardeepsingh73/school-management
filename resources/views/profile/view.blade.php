<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0"><i class="bi bi-person-circle me-2"></i> Profile</h1>
        </div>
    </x-slot>

    <!-- Breadcrumbs -->
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item">
            <a class="btn-link text-decoration-none" href="{{ route('dashboard') }}">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Profile</li>
    </x-slot>

    <!-- Profile Card -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">

        <!-- Card Header -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h2 class="h5 mb-0 fw-semibold">Profile Information</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil-square me-1"></i> Edit Profile
                </a>
                @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !Auth::user()->hasVerifiedEmail())
                    <form method="POST" action="{{ route('verification.send') }}" id="resendVerificationForm">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-warning">
                            <i class="bi bi-envelope-exclamation me-1"></i> Resend Verification
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Profile Body -->
        <div class="card-body p-0">
            <div class="row g-0">
                <!-- Profile Picture & Quick Actions -->
                <div class="col-md-4 border-end p-4 d-flex flex-column align-items-center text-center">
                    <div class="avatar avatar-lg mb-2">
                        <i class="bi bi-person-circle text-secondary" style="font-size:4rem;"></i>
                    </div>
                    <h3 class="h5 mb-1">{{ Auth::user()->name }}</h3>
                    <p class="text-muted small mb-3">{{ Auth::user()->getRoleNames()->first() ?? 'User' }}</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" title="Share Profile">
                            <i class="bi bi-share"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" title="Show QR Code">
                            <i class="bi bi-qr-code"></i>
                        </button>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="col-md-8 p-4">
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">Full Name</dt>
                        <dd class="col-sm-8">{{ Auth::user()->name }}</dd>

                        <dt class="col-sm-4 text-muted">Email Address</dt>
                        <dd class="col-sm-8 d-flex align-items-center">
                            {{ Auth::user()->email }}
                            @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail)
                                <span
                                    class="badge ms-2 bg-{{ Auth::user()->hasVerifiedEmail() ? 'success' : 'danger' }}">
                                    {{ Auth::user()->hasVerifiedEmail() ? 'Verified' : 'Unverified' }}
                                </span>
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-muted">Account Created</dt>
                        <dd class="col-sm-8">
                            {{ Auth::user()->created_at->format('F j, Y') }}
                            <small class="text-muted">({{ Auth::user()->created_at->diffForHumans() }})</small>
                        </dd>

                        <dt class="col-sm-4 text-muted">Last Updated</dt>
                        <dd class="col-sm-8">
                            {{ Auth::user()->updated_at->format('F j, Y') }}
                            <small class="text-muted">({{ Auth::user()->updated_at->diffForHumans() }})</small>
                        </dd>

                        @if (Auth::user()->last_login_at)
                            <dt class="col-sm-4 text-muted">Last Login</dt>
                            <dd class="col-sm-8">
                                {{ Auth::user()->last_login_at->format('F j, Y \a\t g:i A') }}
                                <small class="text-muted">({{ Auth::user()->last_login_at->diffForHumans() }})</small>
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>

        <!-- Footer with Tabs -->
        <div class="card-footer bg-white border-top py-0">
            <ul class="nav nav-tabs card-header-tabs px-3 pt-3" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="activity-tab" data-bs-toggle="tab" data-bs-target="#activity"
                        type="button" role="tab">
                        <i class="bi bi-activity me-1"></i> Activity
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security"
                        type="button" role="tab">
                        <i class="bi bi-shield-lock me-1"></i> Security
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="login-history-tab" data-bs-toggle="tab" data-bs-target="#login-history"
                        type="button" role="tab">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login History
                    </button>
                </li>
            </ul>

            <div class="tab-content p-3" id="profileTabsContent">
                <!-- Activity Tab -->
                <div class="tab-pane fade show active" id="activity" role="tabpanel">
                    <p class="text-muted mb-0">Recent user activity will appear here.</p>
                </div>
                <!-- Security Tab -->
                <div class="tab-pane fade" id="security" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="h6 mb-1">Password</h4>
                            <p class="small text-muted mb-0">Last changed 3 months ago</p>
                        </div>
                        <button class="btn btn-sm btn-outline-primary">Change Password</button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="h6 mb-1">Two-Factor Authentication</h4>
                            <p class="small text-muted mb-0">Add extra security to your account</p>
                        </div>
                        <button class="btn btn-sm btn-outline-secondary">Enable 2FA</button>
                    </div>
                </div>
                <!-- Login History Tab -->
                <!-- Login History Tab -->
                <div class="tab-pane fade" id="login-history" role="tabpanel">
                    @if ($user->loginHistory && $user->loginHistory->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Date/Time</th>
                                        <th>IP Address</th>
                                        <th>User Agent</th>
                                        <th>Login At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->loginHistory->sortByDesc('created_at') as $history)
                                        <tr>
                                            <td>
                                                {{ $history->created_at->format('M j, Y g:i a') }}<br>
                                                <small
                                                    class="text-muted">{{ $history->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>{{ $history->ip_address }}</td>
                                            <td>
                                                {{ $history->user_agent }}
                                            </td>
                                            <td>
                                                {{ $history->login_at }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <small class="text-muted">
                                Showing {{ $user->loginHistory->count() }} recent logins
                            </small>
                            @if ($user->loginHistory->count() >= 10)
                                <button class="btn btn-sm btn-outline-primary">
                                    View Full History
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> No login history available
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Script for Resend Verification -->
    <x-slot name="script">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $(function() {
                    const $resendForm = $('#resendVerificationForm');
                    if ($resendForm.length) {
                        $resendForm.on('submit', function(e) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'warning',
                                title: 'Resend Verification Email?',
                                text: 'We will send a new verification email to your registered address.',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, send it',
                                cancelButtonText: 'Cancel',
                                confirmButtonColor: '#0d6efd',
                                cancelButtonColor: '#6c757d'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $resendForm.off('submit').trigger('submit');
                                }
                            });
                        });
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>
