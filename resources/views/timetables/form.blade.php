<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-calendar-week text-primary me-2"></i>
                {{ isset($timetable) ? 'Edit Timetable' : 'Create New Timetable' }}
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
            <a class="btn-link text-decoration-none" href="{{ route('timetables.index') }}">
                <i class="bi bi-calendar-week me-1"></i> Timetables
            </a>
        </li>
        <li class="breadcrumb-item active">{{ isset($timetable) ? 'Edit' : 'Create' }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ isset($timetable) ? route('timetables.update', $timetable) : route('timetables.store') }}"
                method="POST" novalidate>
                @csrf
                @if (isset($timetable))
                    @method('PUT')
                @endif

                {{-- ===== BASIC INFORMATION ===== --}}
                <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                    <i class="bi bi-info-circle me-2"></i> Basic Information
                </h5>
                <div class="row g-3">
                    {{-- Class --}}
                    <div class="col-md-3">
                        <label class="form-label">Class <x-star /></label>
                        <select name="class_id" class="form-select @error('class_id') is-invalid @enderror">
                            <option value="">-- Select Class --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $timetable->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                    {{ $class->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- Teacher --}}
                    <div class="col-md-3">
                        <label class="form-label">Teacher <x-star /></label>
                        <select name="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror">
                            <option value="">-- Select Teacher --</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ old('teacher_id', $timetable->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->user->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- Subject --}}
                    <div class="col-md-3">
                        <label class="form-label">Subject <x-star /></label>
                        <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror">
                            <option value="">-- Select Subject --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id', $timetable->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                </div>

                {{-- ===== SCHEDULE DETAILS ===== --}}
                <h5 class="mt-5 mb-4 border-bottom pb-2 text-success fw-semibold">
                    <i class="bi bi-clock me-2"></i> Schedule Details
                </h5>
                <div class="row g-3">
                    {{-- Day of Week --}}
                    <div class="col-md-3">
                        <label class="form-label">Day of Week <x-star /></label>
                        <select name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror">
                            <option value="">-- Select Day --</option>
                            @foreach (consthelper('Timetable::$days') as $index => $day)
                                <option value="{{ $index }}"
                                    {{ old('day_of_week', $timetable->day_of_week ?? '') == $index ? 'selected' : '' }}>
                                    {{ $day }}
                                </option>
                            @endforeach
                        </select>
                        @error('day_of_week')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- Start Time --}}
                    <div class="col-md-3">
                        <label class="form-label">Start Time <x-star /></label>
                        <input type="time" name="start_time"
                            class="form-control @error('start_time') is-invalid @enderror"
                            value="{{ old('start_time', isset($timetable->start_time) ? \Carbon\Carbon::parse($timetable->start_time)->format('H:i') : '') }}">
                        @error('start_time')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- End Time --}}
                    <div class="col-md-3">
                        <label class="form-label">End Time <x-star /></label>
                        <input type="time" name="end_time"
                            class="form-control @error('end_time') is-invalid @enderror"
                            value="{{ old('end_time', isset($timetable->end_time) ? \Carbon\Carbon::parse($timetable->end_time)->format('H:i') : '') }}">
                        @error('end_time')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- Room --}}
                    <div class="col-md-3">
                        <label class="form-label">Room</label>
                        <input type="text" name="room" class="form-control @error('room') is-invalid @enderror"
                            value="{{ old('room', $timetable->room ?? '') }}">
                        @error('room')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                </div>

                {{-- Schedule Type & Validity --}}
                <div class="row g-3 mt-2">
                    {{-- Schedule Type --}}
                    <div class="col-md-3">
                        <label class="form-label">Schedule Type</label>
                        <select name="schedule_type" class="form-select @error('schedule_type') is-invalid @enderror">
                            @foreach (consthelper('Timetable::$scheduleTypes') as $id => $label)
                                <option value="{{ $id }}"
                                    {{ old('status', $subject->schedule_type ?? '') == $id ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('schedule_type')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- Effective From --}}
                    <div class="col-md-3">
                        <label class="form-label">Effective From</label>
                        <input type="date" name="effective_from"
                            class="form-control @error('effective_from') is-invalid @enderror"
                            value="{{ old('effective_from', isset($timetable->effective_from) ? \Carbon\Carbon::parse($timetable->effective_from)->format('Y-m-d') : '') }}">
                        @error('effective_from')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- Effective Until --}}
                    <div class="col-md-3">
                        <label class="form-label">Effective Until</label>
                        <input type="date" name="effective_until"
                            class="form-control @error('effective_until') is-invalid @enderror"
                            value="{{ old('effective_until', isset($timetable->effective_until) ? \Carbon\Carbon::parse($timetable->effective_until)->format('Y-m-d') : '') }}">
                        @error('effective_until')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                </div>

                {{-- Actions --}}
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary px-4">
                        {{ isset($timetable) ? 'Update Timetable' : 'Create Timetable' }}
                    </button>
                    <x-back-button :href="route('timetables.index')" class="btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
                    </x-back-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
