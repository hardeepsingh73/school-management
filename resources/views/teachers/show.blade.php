<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-person-badge text-primary me-2"></i>
                Teacher Details
            </h2>
            <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-sm btn-warning ms-2">
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
            <a href="{{ route('teachers.index') }}">
                <i class="bi bi-people me-1"></i> Teachers
            </a>
        </li>
        <li class="breadcrumb-item active">{{ $teacher->user->name }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            {{-- USER ACCOUNT --}}
            <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                <i class="bi bi-person-lines-fill me-2"></i> User Account
            </h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>First Name:</strong> {{ $teacher->first_name }}</div>
                <div class="col-md-6"><strong>Last Name:</strong> {{ $teacher->last_name }}</div>
                <div class="col-md-6"><strong>Email:</strong> {{ $teacher->user->email ?? 'N/A' }}</div>
            </div>

            {{-- TEACHER DETAILS --}}
            <h5 class="mt-4 mb-4 border-bottom pb-2 text-success fw-semibold">
                <i class="bi bi-info-circle me-2"></i> Teacher Details
            </h5>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Employee Number:</strong> {{ $teacher->employee_number }}</div>
                <div class="col-md-4"><strong>Teacher Code:</strong> {{ $teacher->teacher_code ?? 'N/A' }}</div>
                <div class="col-md-4"><strong>Department:</strong> {{ $teacher->department->name ?? 'N/A' }}</div>
                <div class="col-md-4"><strong>Designation:</strong> {{ $teacher->designation ?? 'N/A' }}</div>
                <div class="col-md-4"><strong>Qualification:</strong> {{ $teacher->qualification ?? 'N/A' }}</div>
                <div class="col-md-4"><strong>Joining Date:</strong> {{ $teacher->joining_date ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Phone:</strong> {{ $teacher->phone ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Emergency Contact Name:</strong> {{ $teacher->emergency_contact ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Emergency Contact Phone:</strong> {{ $teacher->emergency_phone ?? 'N/A' }}</div>
            </div>

            {{-- ACTIONS --}}
            <div class="mt-4 d-flex justify-content-between">
                <x-back-button :href="route('teachers.index')" class="btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </x-back-button>
                <a href="{{ route('teachers.edit', $teacher) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Edit Teacher
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
