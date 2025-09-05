<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-calendar-week text-primary me-2"></i>
                Timetable Details
            </h2>
            <a href="{{ route('timetables.edit', $timetable) }}" class="btn btn-sm btn-warning ms-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </x-slot>

    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('timetables.index') }}">
                <i class="bi bi-calendar-week me-1"></i> Timetables
            </a>
        </li>
        <li class="breadcrumb-item active">Timetable #{{ $timetable->id }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- BASIC INFORMATION --}}
            <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                <i class="bi bi-info-circle me-2"></i> Basic Information
            </h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Class:</strong> {{ $timetable->schoolClass->full_name ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Teacher:</strong> {{ $timetable->teacher->user->name ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Subject:</strong> {{ $timetable->subject->name ?? 'N/A' }}</div>
            </div>
        </div>

        {{-- SCHEDULE DETAILS --}}
        <h5 class="mt-4 mb-4 border-bottom pb-2 text-success fw-semibold">
            <i class="bi bi-clock me-2"></i> Schedule Details
        </h5>
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Day of Week:</strong>
                @php
                    $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                @endphp
                {{ $days[$timetable->day_of_week] ?? 'N/A' }}
            </div>
            <div class="col-md-6"><strong>Time:</strong> {{ $timetable->start_time }} - {{ $timetable->end_time }}
            </div>
            <div class="col-md-6"><strong>Room:</strong> {{ $timetable->room ?? 'N/A' }}</div>
            <div class="col-md-6">
                <strong>Schedule Type:</strong>
                @php
                    $types = [1 => 'Regular', 2 => 'Makeup', 3 => 'Special'];
                @endphp
                {{ $types[$timetable->schedule_type] ?? 'N/A' }}
            </div>
            <div class="col-md-6"><strong>Effective From:</strong> {{ $timetable->effective_from ?? 'N/A' }}</div>
            <div class="col-md-6"><strong>Effective Until:</strong> {{ $timetable->effective_until ?? 'N/A' }}
            </div>
        </div>

        {{-- TRACKING INFO --}}
        <h5 class="mt-4 mb-4 border-bottom pb-2 text-warning fw-semibold">
            <i class="bi bi-person-lines-fill me-2"></i> Tracking Info
        </h5>
        <div class="row mb-3">
            <div class="col-md-6"><strong>Created At:</strong>
                {{ $timetable->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</div>
            <div class="col-md-6"><strong>Updated At:</strong>
                {{ $timetable->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</div>
        </div>

        {{-- ACTIONS --}}
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <x-back-button :href="route('timetables.index')" class="btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </x-back-button>
            <a href="{{ route('timetables.edit', $timetable) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-1"></i> Edit Timetable
            </a>
        </div>
    </div>
    </div>
</x-app-layout>
