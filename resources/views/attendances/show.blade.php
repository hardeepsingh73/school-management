<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-person-check-fill text-primary me-2"></i>
                Attendance Details
            </h2>
            <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-sm btn-warning ms-2">
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
            <a href="{{ route('attendances.index') }}">
                <i class="bi bi-person-check-fill me-1"></i> Attendances
            </a>
        </li>
        <li class="breadcrumb-item active">Attendance #{{ $attendance->id }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- Basic Information --}}
            <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                <i class="bi bi-info-circle me-2"></i> Basic Information
            </h5>

            <div class="row mb-3">
                <div class="col-md-3"><strong>Subject:</strong> {{ $attendance->subject->name ?? 'N/A' }}</div>
                <div class="col-md-3"><strong>Class:</strong> {{ $attendance->schoolClass->full_name ?? 'N/A' }}</div>
                <div class="col-md-3"><strong>Date:</strong> {{ $attendance->date?->format('d M Y') ?? 'N/A' }}</div>
            </div>

            {{-- Attendance List --}}
            <h5 class="mb-3 border-bottom pb-2 text-secondary fw-semibold">
                <i class="bi bi-people-fill me-2"></i> Student Attendance
            </h5>

            @php
                $attendanceLabels = ['present' => 'Present', 'absent' => 'Absent'];
            @endphp

            @if (is_array($attendanceData) && count($attendanceData) > 0)
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendanceData as $studentId => $status)
                            @php
                                $student = $students->get($studentId);
                                $studentName = $student?->user->name ?? 'Unknown Student';
                                $statusLabel = $attendanceLabels[$status] ?? ucfirst($status);
                            @endphp
                            <tr>
                                <td>{{ $studentName }}</td>
                                <td>
                                    @if ($status === 'present')
                                        <span class="badge bg-success">Present</span>
                                    @elseif ($status === 'absent')
                                        <span class="badge bg-danger">Absent</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $statusLabel }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No attendance records found for students.</p>
            @endif

            {{-- Recorded By --}}
            <h5 class="mt-4 mb-4 border-bottom pb-2 text-success fw-semibold">
                <i class="bi bi-person-badge me-2"></i> Recorded By
            </h5>
            <div class="row mb-3">
                <div class="col-md-12">{{ $attendance->recordedBy->user->name ?? 'N/A' }}</div>
            </div>

            {{-- Tracking Info --}}
            <h5 class="mt-4 mb-4 border-bottom pb-2 text-warning fw-semibold">
                <i class="bi bi-clock-history me-2"></i> Tracking Info
            </h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Created At:</strong>
                    {{ $attendance->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Updated At:</strong>
                    {{ $attendance->updated_at?->format('d M Y, h:i A') ?? 'N/A' }}</div>
            </div>

            {{-- Actions --}}
            <div class="mt-4 d-flex justify-content-between align-items-center">
                <x-back-button :href="route('attendances.index')" class="btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </x-back-button>
                <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Edit Attendance
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
