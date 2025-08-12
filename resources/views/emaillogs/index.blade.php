<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-bug-fill me-2"></i> {{ __('Email Logs') }}
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
            Email Logs
        </li>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Email Logs</h5>
                <p class="text-muted mb-0 small">Browse recent application email records</p>
            </div>
            @can('clear email logs')
                <form id="clear-email-logs-form" action="{{ route('email-logs.clear') }}" method="POST"
                    class="mb-0 clearLogs">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" id="clearEmailLogsBtn">
                        <i class="bi bi-trash3-fill me-1"></i> Clear Logs
                    </button>
                </form>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>To</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($emailLogs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->to }}</td>
                                <td>{{ $log->subject }}</td>
                                <td title="{{ $log->body }}">{{ Str::limit($log->body, 80) }}</td>
                                <td>{{ $log->status }}</td>
                                <td class="text-nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    @can('view email logs')
                                        <a href="{{ route('email-logs.show', $log->id) }}"
                                            class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                            title="Edit">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-emoji-frown fs-3 text-muted align-middle"></i>
                                    <br>No email logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($emailLogs->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$emailLogs" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
