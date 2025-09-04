<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-bar-chart-line text-primary me-2"></i>
                {{ isset($result) ? 'Edit Result' : 'Create New Result' }}
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
            <a class="btn-link text-decoration-none" href="{{ route('results.index') }}">
                <i class="bi bi-bar-chart-line me-1"></i> Results
            </a>
        </li>
        <li class="breadcrumb-item active">{{ isset($result) ? 'Edit' : 'Create' }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ isset($result) ? route('results.update', $result) : route('results.store') }}"
                method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                @if (isset($result))
                    @method('PUT')
                @endif

                {{-- ===== BASIC INFORMATION ===== --}}
                <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                    <i class="bi bi-info-circle me-2"></i> Basic Information
                </h5>
                <div class="row g-3">
                    {{-- Student --}}
                    <div class="col-md-4">
                        <label class="form-label">Student <x-star /></label>
                        <select name="student_id" class="form-select @error('student_id') is-invalid @enderror">
                            <option value="">-- Select Student --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}"
                                    {{ old('student_id', $result->student_id ?? '') == $student->id ? 'selected' : '' }}>
                                    {{ $student->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('student_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- Exam --}}
                    <div class="col-md-4">
                        <label class="form-label">Exam <x-star /></label>
                        <select name="exam_id" class="form-select @error('exam_id') is-invalid @enderror">
                            <option value="">-- Select Exam --</option>
                            @foreach ($exams as $exam)
                                <option value="{{ $exam->id }}"
                                    {{ old('exam_id', $result->exam_id ?? '') == $exam->id ? 'selected' : '' }}>
                                    {{ $exam->subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('exam_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- Class --}}
                    <div class="col-md-4">
                        <label class="form-label">Class <x-star /></label>
                        <select name="class_id" class="form-select @error('class_id') is-invalid @enderror">
                            <option value="">-- Select Class --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $result->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                    {{ $class->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                </div>

                {{-- ===== MARKS DETAILS ===== --}}
                <h5 class="mt-5 mb-4 border-bottom pb-2 text-success fw-semibold">
                    <i class="bi bi-percent me-2"></i> Marks Details
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Total Marks</label>
                        <input type="number" step="0.01" name="total_marks"
                            class="form-control @error('total_marks') is-invalid @enderror"
                            value="{{ old('total_marks', $result->total_marks ?? '') }}">
                        @error('total_marks')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Obtained Marks</label>
                        <input type="number" step="0.01" name="obtained_marks"
                            class="form-control @error('obtained_marks') is-invalid @enderror"
                            value="{{ old('obtained_marks', $result->obtained_marks ?? '') }}">
                        @error('obtained_marks')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Grade</label>
                        <input type="text" name="grade" class="form-control @error('grade') is-invalid @enderror"
                            value="{{ old('grade', $result->grade ?? '') }}">
                        @error('grade')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary px-4">
                        {{ isset($result) ? 'Update Result' : 'Create Result' }}
                    </button>
                    <x-back-button :href="route('results.index')" class="btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
                    </x-back-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
