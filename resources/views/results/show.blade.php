<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-clipboard-data text-primary me-2"></i>
                Result Details
            </h2>
            <a href="{{ route('results.edit', $result) }}" class="btn btn-sm btn-warning ms-2">
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
            <a href="{{ route('results.index') }}">
                <i class="bi bi-clipboard-data me-1"></i> Results
            </a>
        </li>
        <li class="breadcrumb-item active">Result #{{ $result->id }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            {{-- BASIC INFORMATION --}}
            <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                <i class="bi bi-info-circle me-2"></i> Basic Information
            </h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Student:</strong> {{ $result->student->user->name ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Exam:</strong> {{ $result->exam->name ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Class:</strong> {{ $result->schoolClass->full_name ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Total Marks:</strong> {{ $result->total_marks ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Obtained Marks:</strong> {{ $result->obtained_marks ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Percentage:</strong>
                    {{ $result->percentage ? $result->percentage . '%' : 'N/A' }}</div>
                <div class="col-md-6"><strong>Grade:</strong> {{ $result->grade ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Rank:</strong> {{ $result->rank ?? 'N/A' }}</div>
            </div>

            {{-- SUBJECT-WISE RESULTS --}}
            <h5 class="mt-4 mb-4 border-bottom pb-2 text-success fw-semibold">
                <i class="bi bi-journal-text me-2"></i> Subject-wise Results
            </h5>
            <div class="table-responsive mb-3">
                <table class="table table-bordered table-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Subject Name</th>
                            <th>Total Marks</th>
                            <th>Obtained Marks</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $subjects = json_decode($result->subjects, true) ?? [];
                        @endphp
                        @forelse($subjects as $subject)
                            <tr>
                                <td>{{ $subject['subject_name'] }}</td>
                                <td>{{ $subject['total_marks'] }}</td>
                                <td>{{ $subject['obtained_marks'] }}</td>
                                <td>{{ $subject['grade'] ?? 'N/A' }}</td>
                                <td>{{ $subject['remarks'] ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No subjects found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- ADDITIONAL DETAILS --}}
            <h5 class="mt-4 mb-4 border-bottom pb-2 text-info fw-semibold">
                <i class="bi bi-chat-left-quote me-2"></i> Additional Details
            </h5>
            <div class="row mb-3">
                <div class="col-md-12"><strong>Remarks:</strong> {{ $result->remarks ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Published:</strong> {{ $result->is_published ? 'Yes' : 'No' }}</div>
                <div class="col-md-6"><strong>Published At:</strong>
                    {{ $result->published_at ? $result->published_at->format('d M Y, h:i A') : 'N/A' }}</div>
            </div>

            {{-- TRACKING --}}
            <h5 class="mt-4 mb-4 border-bottom pb-2 text-warning fw-semibold">
                <i class="bi bi-person-lines-fill me-2"></i> Tracking Info
            </h5>
            <div class="row mb-3">
                <div class="col-md-6"><strong>Created By:</strong> {{ $result->creator->name ?? 'N/A' }}</div>
                <div class="col-md-6"><strong>Updated By:</strong> {{ $result->updater->name ?? 'N/A' }}</div>
            </div>

            {{-- ACTIONS --}}
            <div class="mt-4 d-flex justify-content-between">
                <x-back-button :href="route('results.index')" class="btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </x-back-button>
                <a href="{{ route('results.edit', $result) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-1"></i> Edit Result
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
