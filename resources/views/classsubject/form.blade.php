<x-app-layout>
    {{-- PAGE HEADER --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-link-45deg text-primary me-2"></i>
                {{ isset($classSubject) ? 'Edit Class Subject Assignment' : 'Assign Subject to Class' }}
            </h2>
        </div>
    </x-slot>

    {{-- BREADCRUMBS --}}
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}" class="btn-link text-decoration-none">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('class-subject.index') }}" class="btn-link text-decoration-none">
                <i class="bi bi-link-45deg me-1"></i> Class Subjects
            </a>
        </li>
        <li class="breadcrumb-item active">{{ isset($classSubject) ? 'Edit' : 'Create' }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form
                action="{{ isset($classSubject) ? route('class-subject.update', [$classSubject->class_id, $classSubject->subject_id]) : route('class-subject.store') }}"
                method="POST" novalidate>
                @csrf
                @if (isset($classSubject))
                    @method('PUT')
                @endif

                {{-- ===== BASIC RELATIONSHIP INFO ===== --}}
                <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                    <i class="bi bi-diagram-3 me-2"></i> Relationship Information
                </h5>
                <div class="row g-3">
                    {{-- Class --}}
                    <div class="col-md-4">
                        <label class="form-label">Class <x-star /></label>
                        <select name="class_id" class="form-select @error('class_id') is-invalid @enderror"
                            {{ isset($classSubject) ? 'disabled' : '' }}>
                            <option value="">-- Select Class --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $classSubject->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                    {{ $class->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                        @if (isset($classSubject))
                            <input type="hidden" name="class_id" value="{{ $classSubject->class_id }}">
                        @endif
                    </div>

                    {{-- Subject --}}
                    <div class="col-md-4">
                        <label class="form-label">Subject <x-star /></label>
                        <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror"
                            {{ isset($classSubject) ? 'disabled' : '' }}>
                            <option value="">-- Select Subject --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id', $classSubject->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                        @if (isset($classSubject))
                            <input type="hidden" name="subject_id" value="{{ $classSubject->subject_id }}">
                        @endif
                    </div>

                    {{-- Teacher --}}
                    <div class="col-md-4">
                        <label class="form-label">Teacher</label>
                        <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror">
                            <option value="">-- Select Teacher --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ old('teacher_id', $classSubject->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary px-4">
                        {{ isset($classSubject) ? 'Update Assignment' : 'Assign Subject' }}
                    </button>
                    <x-back-button :href="route('class-subject.index')" class="btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Back
                    </x-back-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
