<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 fw-semibold">
                <i class="bi bi-file-earmark-text me-2"></i> Results Management
            </h1>
        </div>
    </x-slot>

    <!-- Breadcrumbs -->
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item">
            <a class="btn-link text-decoration-none" href="{{ route('dashboard') }}">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <i class="bi bi-file-earmark-text me-1"></i> Results
        </li>
    </x-slot>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <!-- Card Header -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h2 class="h5 mb-0 fw-semibold">All Results</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                    data-bs-target="#resultFilters" aria-expanded="false">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @can('create results')
                    <a href="{{ route('results.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add Result
                    </a>
                @endcan
            </div>
        </div>

        <!-- Filter Section -->
        <div class="collapse" id="resultFilters">
            <div class="p-3 border-bottom">
                <form method="POST" action="{{ route('results.index') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small">Student Name</label>
                            <input type="text" name="student" class="form-control" value="{{ request('student') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Exam</label>
                            <input type="text" name="exam" class="form-control" value="{{ request('exam') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Class</label>
                            <input type="text" name="class" class="form-control" value="{{ request('class') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Grade</label>
                            <input type="text" name="grade" class="form-control" value="{{ request('grade') }}">
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('results.index') }}" class="btn btn-outline-secondary">
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
                            <th class="ps-4">Student</th>
                            <th>Exam</th>
                            <th>Class</th>
                            <th>Total Marks</th>
                            <th>Obtained</th>
                            <th>%</th>
                            <th>Grade</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($results as $result)
                            <tr>
                                <td class="ps-4">{{ $result->student->user->name }}</td>
                                <td>{{ $result->exam->subject->name ?? 'N/A' }}</td>
                                <td>{{ $result->schoolClass->full_name ?? 'N/A' }}</td>
                                <td>{{ number_format($result->total_marks, 2) }}</td>
                                <td>{{ number_format($result->obtained_marks, 2) }}</td>
                                <td>{{ $result->percentage ?? 'N/A' }}</td>
                                <td> <span class="badge bg-info">{{ $result->grade ?? 'N/A' }}</span> </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        @can('edit results')
                                            <a href="{{ route('results.edit', $result->id) }}"
                                                class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete results')
                                            <form action="{{ route('results.destroy', $result->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-entry"
                                                    data-id="{{ $result->id }}" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="bi bi-file-earmark-text fs-1 text-muted"></i>
                                    <h5 class="mt-3">No results found</h5>
                                    <p class="text-muted">Try adjusting your search filters</p>
                                    @can('create results')
                                        <a href="{{ route('results.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add New Result
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($results->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$results" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
