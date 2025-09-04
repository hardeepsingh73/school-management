<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-building text-primary me-2"></i>
                {{ isset($department) ? 'Edit Department' : 'Create New Department' }}
            </h2>
        </div>
    </x-slot>

    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item">
            <a class="btn-link text-decoration-none" href="{{ route('dashboard') }}">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </li>
        <li class="breadcrumb-item">
            <a class="btn-link text-decoration-none" href="{{ route('departments.index') }}">
                <i class="bi bi-diagram-3 me-1"></i> Departments
            </a>
        </li>
        <li class="breadcrumb-item active">{{ isset($department) ? 'Edit' : 'Create' }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form
                action="{{ isset($department) ? route('departments.update', $department) : route('departments.store') }}"
                method="POST" novalidate>
                @csrf
                @if (isset($department))
                    @method('PUT')
                @endif

                {{-- ===== BASIC INFORMATION ===== --}}
                <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                    <i class="bi bi-info-circle me-2"></i> Basic Information
                </h5>
                <div class="row g-3">
                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label">Department Name <x-star /></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $department->name ?? '') }}" placeholder="Enter Department Name">
                        @error('name')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- Head Teacher --}}
                    <div class="col-md-6">
                        <label class="form-label">Head Teacher</label>
                        <select name="head_teacher_id"
                            class="form-select @error('head_teacher_id') is-invalid @enderror">
                            <option value="">-- Select Head Teacher --</option>
                            @foreach ($headTeachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ old('head_teacher_id', $department->head_teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('head_teacher_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary px-4">
                        {{ isset($department) ? 'Update Department' : 'Create Department' }}
                    </button>
                    <x-back-button :href="route('departments.index')" class="btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
                    </x-back-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
