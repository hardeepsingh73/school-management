<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 fw-semibold">
                <i class="bi bi-clipboard-check me-2"></i> Attendance Management
            </h1>
        </div>
    </x-slot>

    <!-- Breadcrumbs -->
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item">
            <a href="{{ route('dashboard') }}" class="btn-link text-decoration-none">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <i class="bi bi-clipboard-check me-1"></i> Attendances
        </li>
    </x-slot>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <!-- Card Header -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h2 class="h5 mb-0 fw-semibold">All Attendance Records</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                    data-bs-target="#attendanceFilters">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @can('create attendances')
                    <a href="{{ route('attendances.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add Attendance
                    </a>
                @endcan
            </div>
        </div>

        <!-- Filter Section -->
        <div class="collapse" id="attendanceFilters">
            <div class="p-3 border-bottom">
                <form method="POST" action="{{ route('attendances.index') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label small">Date</label>
                            <input type="date" name="date" value="{{ request('date') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Class</label>
                            <select name="class_id" class="form-select">
                                <option value="">All Classes</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->name }} {{ $class->section ? '(' . $class->section . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Subject</label>
                            <select name="subject_id" class="form-select">
                                <option value="">All Subjects</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}"
                                        {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Student</label>
                            <input type="text" name="student" value="{{ request('student') }}" class="form-control"
                                placeholder="Name or ID">
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('attendances.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table -->
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Class</th>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Recorded By</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $attendance)
                            <tr>
                                <td>{{ $attendance->schoolClass->full_name ?? 'N/A' }}</td>
                                <td>{{ $attendance->subject->name ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}</td>
                                <td>{{ $attendance->recordedBy->user->name ?? 'N/A' }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('attendances.show', $attendance->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @can('edit attendances')
                                            <a href="{{ route('attendances.edit', $attendance->id) }}"
                                                class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete attendances')
                                            <form method="POST"
                                                action="{{ route('attendances.destroy', $attendance->id) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-entry"
                                                    data-id="{{ $attendance->id }}" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="bi bi-clipboard-check fs-1 text-muted"></i>
                                    <h5 class="mt-3">No attendance records found</h5>
                                    <p class="text-muted">Try adjusting your search filters</p>
                                    @can('create attendances')
                                        <a href="{{ route('attendances.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add New Attendance
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($attendances->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$attendances" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
