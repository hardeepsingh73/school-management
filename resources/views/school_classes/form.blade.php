<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-Easel text-primary me-2"></i>
                {{ isset($schoolClass) ? 'Edit Class' : 'Create New Class' }}
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
            <a class="btn-link text-decoration-none" href="{{ route('school_classes.index') }}">
                <i class="bi bi-easel me-1"></i> Classes
            </a>
        </li>
        <li class="breadcrumb-item active">{{ isset($schoolClass) ? 'Edit' : 'Create' }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form
                action="{{ isset($schoolClass) ? route('school_classes.update', $schoolClass) : route('school_classes.store') }}"
                method="POST" novalidate>
                @csrf
                @if (isset($schoolClass))
                    @method('PUT')
                @endif

                {{-- ===== CLASS IDENTIFICATION ===== --}}
                <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                    <i class="bi bi-info-circle me-2"></i> Class Identification
                </h5>
                <div class="row g-3">
                    {{-- Name --}}
                    <div class="col-md-4">
                        <label class="form-label">Class Name <x-star /></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $schoolClass->name ?? '') }}">
                        @error('name')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- Section --}}
                    <div class="col-md-4">
                        <label class="form-label">Section</label>
                        <input type="text" name="section" class="form-control @error('section') is-invalid @enderror"
                            value="{{ old('section', $schoolClass->section ?? '') }}">
                        @error('section')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                    {{-- Class Teacher --}}
                    <div class="col-md-4">
                        <label class="form-label">Class Teacher</label>
                        <select name="class_teacher_id"
                            class="form-select @error('class_teacher_id') is-invalid @enderror">
                            <option value="">-- Select Teacher --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ old('class_teacher_id', $schoolClass->class_teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_teacher_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                </div>
                {{-- Actions --}}
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary px-4">
                        {{ isset($schoolClass) ? 'Update Class' : 'Create Class' }}
                    </button>
                    <x-back-button :href="route('school_classes.index')" class="btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
                    </x-back-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
