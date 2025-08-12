<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 fw-semibold">
                <i class="bi bi-person-badge-fill me-2"></i> Role Management
            </h1>
        </div>
    </x-slot>

    <!-- Breadcrumbs -->
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item">
            <a class="btn-link text-decoration-none" href="{{ route('dashboard') }}">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Roles</li>
    </x-slot>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <div>
                <h2 class="h5 mb-0 fw-semibold">System Roles</h2>
                <p class="text-muted small mb-0">Manage permissions and access levels</p>
            </div>
            @can('create roles')
                <a href="{{ route('roles.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Add Role
                </a>
            @endcan
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4" width="80">ID</th>
                            <th>Role Name</th>
                            <th>Permissions</th>
                            <th>Created</th>
                            <th class="text-end pe-4" width="150">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $role->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span
                                            class="symbol symbol-30px bg-light-primary rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-shield-lock text-primary"></i>
                                        </span>
                                        <span class="fw-semibold">{{ $role->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach ($role->permissions->take(3) as $permission)
                                            <span class="badge bg-primary py-1 px-2 small">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                        @if ($role->permissions->count() > 3)
                                            <span class="badge bg-light text-muted py-1 px-2 small">
                                                +{{ $role->permissions->count() - 3 }} more
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted">{{ $role->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        @can('edit roles')
                                            <a href="{{ route('roles.edit', $role->id) }}"
                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete roles')
                                            <form action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-entry delete-role"
                                                    data-id="{{ $role->id }}" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-shield-lock fs-1 text-muted"></i>
                                    <h5 class="mt-3">No roles found</h5>
                                    <p class="text-muted">Create your first role to get started</p>
                                    @can('create roles')
                                        <a href="{{ route('roles.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Create Role
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($roles->hasPages())
            <div class="mt-4">
                <x-pagination :paginator="$roles" />
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $(document).ready(function() {
                    // Initialize tooltips
                    $('[data-bs-toggle="tooltip"]').each(function() {
                        new bootstrap.Tooltip(this);
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
