<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 fw-semibold">
                <i class="bi bi-shield-lock-fill me-2"></i> Permission Management
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
        <li class="breadcrumb-item active" aria-current="page">Permissions</li>
    </x-slot>

    <div class="row justify-content-center">
        <div class="col-xxl-10">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                    <div>
                        <h2 class="h5 mb-0 fw-semibold">System Permissions</h2>
                        <p class="text-muted small mb-0">Manage access control permissions</p>
                    </div>
                    @can('create permissions')
                        <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Add Permission
                        </a>
                    @endcan
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" width="80">ID</th>
                                    <th>Permission Name</th>
                                    <th>Assigned to Roles</th>
                                    <th>Created</th>
                                    <th class="text-end pe-4" width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($permissions as $permission)
                                    <tr>
                                        <td class="ps-4 fw-semibold">{{ $permission->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span
                                                    class="symbol symbol-30px bg-light-warning rounded-circle d-flex align-items-center justify-content-center">
                                                    <i class="bi bi-shield-check text-warning"></i>
                                                </span>
                                                <span class="fw-semibold">{{ $permission->name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                @foreach ($permission->roles->take(3) as $role)
                                                    <span class="badge bg-primary bg-opacity-10 py-1 px-2 small">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                                @if ($permission->roles->count() > 3)
                                                    <span class="badge bg-light text-muted py-1 px-2 small">
                                                        +{{ $permission->roles->count() - 3 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="text-muted">{{ $permission->created_at->format('d M Y') }}</span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                @can('edit permissions')
                                                    <a href="{{ route('permissions.edit', $permission->id) }}"
                                                        class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                        title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                @endcan
                                                @can('delete permissions')
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger delete-permission"
                                                        data-id="{{ $permission->id }}" data-bs-toggle="tooltip"
                                                        title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-shield-exclamation fs-1 text-muted"></i>
                                            <h5 class="mt-3">No permissions found</h5>
                                            <p class="text-muted">Create your first permission to get started</p>
                                            @can('create permissions')
                                                <a href="{{ route('permissions.create') }}" class="btn btn-primary mt-3">
                                                    <i class="bi bi-plus-lg me-2"></i> Create Permission
                                                </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($permissions->hasPages())
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $permissions->firstItem() }} to {{ $permissions->lastItem() }} of
                                {{ $permissions->total() }} entries
                            </div>
                            {{ $permissions->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Delete confirmation
            document.querySelectorAll('.delete-permission').forEach(button => {
                button.addEventListener('click', function() {
                    const permissionId = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const deleteUrl = '{{ route('permissions.destroy', ':id') }}'.replace(
                                ':id', permissionId);

                            fetch(deleteUrl, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(res => res.json())
                                .then(response => {
                                    if (response.status) {
                                        Swal.fire('Deleted!', 'Permission has been deleted.',
                                                'success')
                                            .then(() => window.location.reload());
                                    } else {
                                        Swal.fire('Error!', response.message ||
                                            'Something went wrong', 'error');
                                    }
                                })
                                .catch(() => Swal.fire('Error!', 'Something went wrong', 'error'));
                        }
                    });
                });
            });

            // Initialize tooltips
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => {
                new bootstrap.Tooltip(el);
            });

            // Success toast after create/update
            @if (session('status') === 'permission-saved')
                Swal.fire({
                    icon: 'success',
                    title: 'Permission Saved',
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        </script>
    @endpush
</x-app-layout>
