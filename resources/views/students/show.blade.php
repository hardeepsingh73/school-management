<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-person-vcard text-primary me-2"></i>
                Student Details
            </h2>
            <a href="{{ route('students.edit', $student) }}" class="btn btn-sm btn-warning ms-2">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </x-slot>

    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}"><i class="bi bi-house-door me-1"></i> Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('students.index') }}"><i class="bi bi-people me-1"></i>Students</a>
        </li>
        <li class="breadcrumb-item active">{{ $student->user->name }}</li>
    </x-slot>

    @php $info = $student->additional_info; @endphp

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                <i class="bi bi-person-lines-fill me-2"></i> User Account
            </h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Name:</strong> {{ $student->user->name }}</div>
                <div class="col-md-6"><strong>Email:</strong> {{ $student->user->email ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Gender:</strong>
                    {{ isset($info['gender']) ? ucfirst($info['gender']) : 'N/A' }}</div>
                <div class="col-md-6"><strong>Date of Birth:</strong> {{ $info['dob'] ?? 'N/A' }}</div>
            </div>

            <h5 class="mt-4 mb-4 border-bottom pb-2 text-success fw-semibold">
                <i class="bi bi-info-circle me-2"></i> Student Details
            </h5>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Roll Number:</strong> {{ $info['roll_number'] ?? 'N/A' }}</div>
                <div class="col-md-4"><strong>Admission Number:</strong> {{ $info['admission_number'] ?? 'N/A' }}</div>
                <div class="col-md-4"><strong>Class:</strong> {{ $student->schoolClass->name ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Primary Phone:</strong> {{ $info['primary_phone'] ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Secondary Phone:</strong> {{ $info['secondary_phone'] ?? 'N/A' }}</div>
                <div class="col-12"><strong>Address:</strong> {!! nl2br(e($info['address'] ?? 'N/A')) !!}</div>
                <div class="col-12"><strong>Emergency Contacts:</strong> {!! nl2br(e($info['emergency_contacts'] ?? 'N/A')) !!}</div>
            </div>

            <h5 class="mt-4 mb-4 border-bottom pb-2 text-danger fw-semibold">
                <i class="bi bi-heart-pulse me-2"></i> Medical & Other Information
            </h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Medical Conditions:</strong> {{ $info['medical_conditions'] ?? 'None' }}
                </div>
                <div class="col-md-6"><strong>Special Needs:</strong> {{ $info['special_needs'] ?? 'None' }}</div>
                <div class="col-md-6"><strong>Allergies:</strong> {{ $info['allergies'] ?? 'None' }}</div>
                <div class="col-md-6"><strong>Status:</strong>
                    {{ $student->user->status == 1 ? 'Active' : 'Inactive' }}</div>
            </div>

            <h5 class="mt-4 mb-4 border-bottom pb-2 text-info fw-semibold">
                <i class="bi bi-calendar-check me-2"></i> Admission & Leaving
            </h5>
            <div class="row mb-3">
                <div class="col-md-4"><strong>Admission Date:</strong> {{ $info['admission_date'] ?? 'N/A' }}</div>
                <div class="col-md-4"><strong>Leaving Date:</strong> {{ $info['leaving_date'] ?? 'N/A' }}</div>
                <div class="col-md-4"><strong>Leaving Reason:</strong> {!! nl2br(e($info['leaving_reason'] ?? 'N/A')) !!}</div>
            </div>

            <div class="mt-4 d-flex justify-content-between">
                <x-back-button :href="route('students.index')" class="btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </x-back-button>
                <a href="{{ route('students.edit', $student) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Edit Student
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
