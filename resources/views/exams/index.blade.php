<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 fw-semibold">
                <i class="bi bi-journal-check me-2"></i> Exam Management
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
            <i class="bi bi-journal-check me-1"></i> Exams
        </li>
    </x-slot>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">

        <!-- Card Header -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h2 class="h5 mb-0 fw-semibold">All Exams</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                    data-bs-target="#examFilters">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @can('create exams')
                    <a href="{{ route('exams.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add Exam
                    </a>
                @endcan
            </div>
        </div>

        <!-- Filter Section -->
        <div class="collapse" id="examFilters">
            <div class="p-3 border-bottom">
                <form method="POST" action="{{ route('exams.index') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label small">Subject</label>
                            <select name="subject_id" class="form-select">
                                <option value="">All</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}"
                                        {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Type</label>
                            <select name="type" class="form-select">
                                <option value="">All</option>
                                @foreach (consthelper('Exams::$types') as $key => $label)
                                    <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Class</label>
                            <select name="status" class="form-select">
                                <option value="">All</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('status') == $class->id ? 'selected' : '' }}>{{ $class->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('exams.index') }}" class="btn btn-outline-secondary">
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
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Exam Date</th>
                            <th>Class</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($exams as $exam)
                            <tr>
                                <td>{{ $exam->subject->name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $exam->type_badge_class }}">
                                        {{ $exam->type_label ?? 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($exam->exam_date)->format('Y-m-d') }}
                                </td>
                                <td>{{ $exam->class->full_name ?? 'N/A' }}
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        @can('edit exams')
                                            <a href="{{ route('exams.edit', $exam->id) }}"
                                                class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete exams')
                                            <form method="POST" action="{{ route('exams.destroy', $exam->id) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-entry"
                                                    data-id="{{ $exam->id }}" title="Delete">
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
                                    <i class="bi bi-journal-check fs-1 text-muted"></i>
                                    <h5 class="mt-3">No exams found</h5>
                                    <p class="text-muted">Try adjusting your search filters</p>
                                    @can('create exams')
                                        <a href="{{ route('exams.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add New Exam
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($exams->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$exams" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
