<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-person-badge-fill me-2"></i>
                {{ isset($role) ? 'Edit Role' : 'Create New Role' }}
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
        <li class="breadcrumb-item">
            <a class="btn-link text-decoration-none" href="{{ route('roles.index') }}">
                <i class="bi bi-person-badge me-1"></i> Roles
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ isset($role) ? 'Edit' : 'Create' }}
        </li>
    </x-slot>

    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-white py-3">
            <div class="header-title">
                <h4 class="card-title mb-0">
                    {{ isset($role) ? 'Edit Role' : 'Create New Role' }}
                </h4>
                <p class="text-muted mb-0 small">
                    {{ isset($role) ? 'Update role and modify assigned permissions' : 'Define a new role and assign permissions' }}
                </p>
            </div>
            <div class="header-action">
                <x-back-button :href="route('roles.index')" class="btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> {{ __('Back to Roles') }}
                </x-back-button>
            </div>
        </div>

        <div class="card-body">
            <form action="{{ isset($role) ? route('roles.update', $role) : route('roles.store') }}" method="POST"
                class="needs-validation" novalidate>
                @csrf
                @if (isset($role))
                    @method('PUT')
                @endif

                <!-- Role Name Field -->
                <div class="form-group row mb-4">
                    <label class="col-sm-3 col-form-label" for="name">
                        Role Name <x-star></x-star>
                    </label>
                    <div class="col-sm-9">
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            id="name" placeholder="e.g. Manager, Editor"
                            value="{{ old('name', $role->name ?? '') }}" pattern="[a-zA-Z0-9-_. ]+"
                            title="Only letters, numbers, spaces, hyphens, underscores and dots">
                        @error('name')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                        <small class="form-text text-muted">
                            Use descriptive names without special characters
                        </small>
                    </div>
                </div>

                <!-- Permissions Section -->
                <div class="form-group row mb-4">
                    <label class="col-sm-3 col-form-label pt-0">Permissions</label>
                    <div class="col-sm-9">
                        @if ($permissions->isNotEmpty())
                            <div class="permission-categories">
                                <div class="row">
                                    @foreach ($permissions as $permission)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    id="permission-{{ $permission->id }}" name="permissions[]"
                                                    value="{{ $permission->id }}"
                                                    {{ in_array($permission->name, old('permissions', isset($role) ? $role->permissions->pluck('name')->toArray() : [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="permission-{{ $permission->id }}">
                                                    {{ ucwords(str_replace(['-', '_'], ' ', $permission->name)) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning mb-2"><i class="bi bi-exclamation-triangle me-1"></i> No
                                permissions available</div>
                        @endif
                        @error('permissions')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-group row">
                    <div class="col-sm-9 offset-sm-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i>
                            {{ isset($role) ? 'Update Role' : 'Create Role' }}
                        </button>
                        <button type="reset" class="btn btn-outline-secondary ms-2">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Optional SweetAlert2 Toast on Save -->
    @if (session('status') === 'role-saved')
        <x-slot name="script">
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Role has been {{ isset($role) ? 'updated' : 'created' }} successfully.',
                        toast: true,
                        position: 'top-end',
                        timer: 3000,
                        showConfirmButton: false
                    });
                });
            </script>
        </x-slot>
    @endif
</x-app-layout>
