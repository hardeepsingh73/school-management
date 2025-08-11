<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-journal-text me-2"></i> {{ __('Activity Logs') }}
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
            Activity Logs
        </li>
    </x-slot>

    <!-- Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">Activity Logs</h5>
                <p class="text-muted mb-0 small">Browse and filter system activity records</p>
            </div>
            @can('clear activity logs')
                <form id="clear-logs-form" action="{{ route('activity-logs.clear') }}" method="POST" class="mb-0">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm" id="clearLogsBtn">
                        <i class="bi bi-trash3-fill me-1"></i> Clear Logs
                    </button>
                </form>
            @endcan
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Description</th>
                            <th>Subject</th>
                            <th>Causer</th>
                            <th>IP</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->id }}</td>
                                <td title="{{ $log->description }}">
                                    {{ Str::limit($log->description, 70) }}
                                </td>
                                <td>
                                    @if ($log->subject_type && $log->subject_id)
                                        {{ class_basename($log->subject_type) }} #{{ $log->subject_id }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if ($log->causer)
                                        {{ $log->causer->name ?? ($log->causer->email ?? 'User #' . $log->causer_id) }}
                                    @else
                                        System / Guest
                                    @endif
                                </td>
                                <td>
                                    {{ $log->ip_address ?? 'N/A' }}
                                </td>
                                <td class="text-nowrap">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="text-center text-nowrap">
                                    <a href="{{ route('activity-logs.show', $log->id) }}" class="btn btn-info btn-sm"
                                        title="View Details">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-emoji-frown fs-3 text-muted align-middle"></i>
                                    <br>No activity logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($logs->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$logs" />
                </div>
            @endif
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <x-slot name="script">
        <script>
            // SweetAlert2 confirmation for Clear Logs
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('clear-logs-form');
                const btn = document.getElementById('clearLogsBtn');
                if (form && btn) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Clear Activity Logs?',
                            text: 'Are you sure you want to clear all activity logs? This action cannot be undone.',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, clear logs',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#dc3545',
                            cancelButtonColor: '#6c757d',
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
