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
                <form id="clear-error-logs-form" action="{{ route('error-logs.clear') }}" method="POST" class="mb-0">
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
                                <td colspan="6" class="text-center py-4">
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

    <!-- SweetAlert2 Script -->
    <x-slot name="script">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('clear-error-logs-form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Clear Error Logs?',
                            text: 'Are you sure you want to clear all error logs? This action cannot be undone.',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, clear logs',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                }
            });
        </script>
    </x-slot>
</x-app-layout>
