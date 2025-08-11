<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                <i class="bi bi-shield-lock-fill me-2"></i>
                {{ isset($permission) ? 'Edit Permission' : 'Create New Permission' }}
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
            <a class="btn-link text-decoration-none" href="{{ route('permissions.index') }}">
                <i class="bi bi-shield-lock me-1"></i> Permissions
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ isset($permission) ? 'Edit' : 'Create' }}
        </li>
    </x-slot>

    <!-- Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">{{ isset($permission) ? 'Edit Permission' : 'Create New Permission' }}</h5>
                <p class="text-muted mb-0 small">
                    {{ isset($permission) ? 'Modify the selected permission' : 'Add a new permission to the system' }}
                </p>
            </div>
            <div class="header-action">
                <x-back-button :href="route('permissions.index')" class="btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
                </x-back-button>
            </div>
        </div>

        <div class="card-body">
            <form
                action="{{ isset($permission) ? route('permissions.update', $permission) : route('permissions.store') }}"
                method="POST" class="needs-validation" novalidate>
                @csrf
                @if (isset($permission))
                    @method('PUT')
                @endif

                <div class="row mb-4">
                    <div class="col-md-8 offset-md-2">
                        <div class="form-group">
                            <label for="name" class="form-label">
                                Permission Name <x-star></x-star>
                            </label>
                            <input type="text" name="name"
                                class="form-control @error('name') is-invalid @enderror" id="name"
                                placeholder="e.g. edit articles" value="{{ old('name', $permission->name ?? '') }}">
                            @error('name')
                                <x-error-msg>{{ $message }}</x-error-msg>
                            @enderror
                            <div class="form-text">
                                Use lowercase with words separated by spaces (e.g. "create users")
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-1"></i>
                                {{ isset($permission) ? 'Update' : 'Create' }} Permission
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- SweetAlert2 Toast on Save -->
    @if (session('status') === 'permission-saved')
        <x-slot name="script">
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Permission has been {{ isset($permission) ? 'updated' : 'created' }} successfully.',
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
