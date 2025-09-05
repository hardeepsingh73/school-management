<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-journal-bookmark-fill text-primary me-2"></i>
                Subject Details
            </h2>
            <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-sm btn-warning ms-2">
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
            <a href="{{ route('subjects.index') }}">
                <i class="bi bi-journal-bookmark me-1"></i> Subjects
            </a>
        </li>
        <li class="breadcrumb-item active">{{ $subject->name }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- BASIC INFORMATION --}}
            <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                <i class="bi bi-info-circle me-2"></i> Basic Information
            </h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Name:</strong> {{ $subject->name }}</div>
                <div class="col-md-6"><strong>Code:</strong> {{ $subject->code ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Short Name:</strong> {{ $subject->short_name ?? 'N/A' }}</div>
            </div>

            {{-- DEPARTMENT --}}
            <h5 class="mt-4 mb-4 border-bottom pb-2 text-success fw-semibold">
                <i class="bi bi-diagram-3 me-2"></i> Department
            </h5>
            <div class="row mb-3">
                <div class="col-md-12">
                    {{ $subject->department->name ?? 'Not Assigned' }}
                </div>
            </div>

            {{-- SUBJECT DETAILS --}}
            <h5 class="mt-4 mb-4 border-bottom pb-2 text-info fw-semibold">
                <i class="bi bi-file-earmark-text me-2"></i> Subject Details
            </h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Credit Hours:</strong> {{ $subject->credit_hours ?? 'N/A' }}</div>
                <div class="col-md-6">
                    <strong>Type:</strong>
                    @php
                        $types = [1 => 'Core', 2 => 'Elective', 3 => 'Lab', 4 => 'Special'];
                    @endphp
                    {{ $types[$subject->type] ?? 'N/A' }}
                </div>
                <div class="col-md-12"><strong>Description:</strong> {{ $subject->description ?? 'N/A' }}</div>
            </div>

            {{-- GRADING --}}
            <h5 class="mt-4 mb-4 border-bottom pb-2 text-warning fw-semibold">
                <i class="bi bi-bar-chart me-2"></i> Grading
            </h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Full Marks:</strong> {{ $subject->full_marks ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Passing Marks:</strong> {{ $subject->passing_marks ?? 'N/A' }}</div>
            </div>

            {{-- STATUS --}}
            <h5 class="mt-4 mb-4 border-bottom pb-2 text-secondary fw-semibold">
                <i class="bi bi-check-circle me-2"></i> Status
            </h5>
            <div class="row mb-3">
                <div class="col-md-12">{{ $subject->is_active ? 'Active' : 'Inactive' }}</div>
            </div>

            {{-- TRACKING --}}
            <h5 class="mt-4 mb-4 border-bottom pb-2 text-dark fw-semibold">
                <i class="bi bi-clock-history me-2"></i> Tracking Info
            </h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Created At:</strong>
                    {{ $subject->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Updated At:</strong>
                    {{ $subject->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</div>
            </div>

            {{-- ACTIONS --}}
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <x-back-button :href="route('subjects.index')" class="btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </x-back-button>
                <a href="{{ route('subjects.edit', $subject) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Edit Subject
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
