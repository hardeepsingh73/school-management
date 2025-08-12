<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-bug-fill me-2"></i> {{ __('Error Logs') }}
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
            Error Logs
        </li>
    </x-slot>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Error Logs</h5>
                <p class="text-muted mb-0 small">Browse recent application error records</p>
            </div>
            @can('clear error logs')
                <form id="clear-error-logs-form" action="{{ route('error-logs.clear') }}" method="POST" class="mb-0 clearLogs">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" id="clearErrorLogsBtn">
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
                            <th>Error Type</th>
                            <th>URL</th>
                            <th>Message</th>
                            <th>File</th>
                            <th>Line</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($errorLogs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td>{{ $log->error_type }}</td>
                                <td>{{ $log->url }}</td>
                                <td title="{{ $log->message }}">{{ Str::limit($log->message, 80) }}</td>
                                <td>{{ $log->file ?? '-' }}</td>
                                <td>{{ $log->line ?? '-' }}</td>
                                <td class="text-nowrap">{{ $log->created_at->format('Y-m-d H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-emoji-frown fs-3 text-muted align-middle"></i>
                                    <br>No error logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($errorLogs->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$errorLogs" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
