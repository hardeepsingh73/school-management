<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0">Profile</h1>
        </div>
    </x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a class ="btn-link" href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Profile</li>
    </x-slot>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <h2 class="h5 mb-0 fw-semibold">Profile Information</h2>
                    <div class="d-flex gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-pencil-square me-1"></i> Edit Profile
                        </a>
                        @if (Auth::user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !Auth::user()->hasVerifiedEmail())
                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-envelope-exclamation me-1"></i> Resend Verification
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="row g-0">
                        <!-- Profile Picture Column -->
                        <div class="col-md-4 border-end p-4 d-flex flex-column align-items-center">
                            <h3 class="h5 text-center mb-1">{{ Auth::user()->name }}</h3>
                            <p class="text-muted text-center small mb-3">{{ Auth::user()->role ?? 'User' }}</p>
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-share"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-qr-code"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Profile Details Column -->
                        <div class="col-md-8 p-4">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 text-muted text-truncate">Full Name</dt>
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
                                        <small
                                            class="text-muted">({{ Auth::user()->last_login_at->diffForHumans() }})</small>
                                    </dd>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Additional Profile Sections -->
                <div class="card-footer bg-white border-top py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="activity-tab" data-bs-toggle="tab"
                                data-bs-target="#activity" type="button" role="tab">
                                <i class="bi bi-activity me-1"></i> Activity
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="security-tab" data-bs-toggle="tab" data-bs-target="#security"
                                type="button" role="tab">
                                <i class="bi bi-shield-lock me-1"></i> Security
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-3" id="profileTabsContent">
                        <div class="tab-pane fade show active" id="activity" role="tabpanel">
                            <p class="text-muted mb-0">Recent user activity will appear here.</p>
                        </div>
                        <div class="tab-pane fade" id="security" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4 class="h6 mb-1">Password</h4>
                                    <p class="small text-muted mb-0">Last changed 3 months ago</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h4 class="h6 mb-1">Two-Factor Authentication</h4>
                                    <p class="small text-muted mb-0">Add extra security to your account</p>
                                </div>
                                <button class="btn btn-sm btn-outline-secondary">
                                    Enable 2FA
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
