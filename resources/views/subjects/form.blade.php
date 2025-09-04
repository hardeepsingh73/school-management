<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-journal-bookmark text-primary me-2"></i>
                {{ isset($subject) ? 'Edit Subject' : 'Create New Subject' }}
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
            <a class="btn-link text-decoration-none" href="{{ route('subjects.index') }}">
                <i class="bi bi-journal-bookmark me-1"></i> Subjects
            </a>
        </li>
        <li class="breadcrumb-item active">{{ isset($subject) ? 'Edit' : 'Create' }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ isset($subject) ? route('subjects.update', $subject) : route('subjects.store') }}"
                method="POST" novalidate>
                @csrf
                @if (isset($subject))
                    @method('PUT')
                @endif

                {{-- ===== SUBJECT IDENTIFICATION ===== --}}
                <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                    <i class="bi bi-info-circle me-2"></i> Subject Identification
                </h5>
                <div class="row g-3">
                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label">Name <x-star /></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $subject->name ?? '') }}">
                        @error('name')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                    {{-- Department --}}
                    <div class="col-md-6">
                        <label class="form-label">Department</label>
                        <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                            <option value="">-- Select Department --</option>
                            @foreach ($departments as $dept)
                                <option value="{{ $dept->id }}"
                                    {{ old('department_id', $subject->department_id ?? '') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                </div>
                {{-- Actions --}}
                <div class="mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary px-4">
                        {{ isset($subject) ? 'Update Subject' : 'Create Subject' }}
                    </button>
                    <x-back-button :href="route('subjects.index')" class="btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
                    </x-back-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
