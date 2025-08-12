<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-clock-history me-2"></i> {{ __('Login History') }}
            </h2>
        </div>
    </x-slot>

    <!-- Breadcrumbs -->
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item">
            <a class="btn-link text-decoration-none" href="{{ route('dashboard') }}">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            Login History
        </li>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Login History</h5>
                <p class="text-muted mb-0 small">Track user login activities</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                @can('clear login history')
                    <form id="clear-login-history-form" action="{{ route('login-history.clear') }}" method="POST"
                        class="mb-0 clearLogs">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash3-fill me-1"></i> Clear Logs
                        </button>
                    </form>
                @endcan
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                    data-bs-target="#listSearchForm" aria-expanded="false" aria-controls="listSearchForm">
                    <i class="bi bi-search me-1"></i> Search
                </button>
            </div>
        </div>

        <div class="card-body">
            <!-- Search Form -->
            <div class="collapse mb-4 border-bottom pb-3" id="listSearchForm">
                <form action="{{ route('login-history.index') }}" method="POST" class="form-horizontal">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">User</label>
                            <select name="user_id" class="form-select">
                                <option value="">All Users</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}"
                                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">IP Address</label>
                            <input type="text" name="ip_address" class="form-control" placeholder="Enter IP address"
                                value="{{ request('ip_address') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Login Date</label>
                            <input type="date" name="login_at" class="form-control"
                                value="{{ request('login_at') }}">
                        </div>

                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle table-striped mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>User</th>
                            <th>Login Time</th>
                            <th>IP Address</th>
                            <th>Device/Browser</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($histories as $history)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div>
                                        <h6 class="mb-0">{{ $history->user->name }}</h6>
                                        <small class="text-muted">{{ $history->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    {{ $history->login_at->format('d M Y h:i A') }}
                                    <small class="d-block text-muted">{{ $history->login_at->diffForHumans() }}</small>
                                </td>
                                <td>{{ $history->ip_address }}</td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($history->user_agent, 50) }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-inbox fs-3 text-muted mb-2"></i>
                                    <p class="mb-0">No login history found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($histories->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$histories" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
