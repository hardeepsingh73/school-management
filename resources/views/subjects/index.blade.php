<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 fw-semibold">
                <i class="bi bi-book me-2"></i> Subject Management
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
            <i class="bi bi-book me-1"></i> Subjects
        </li>
    </x-slot>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <!-- Card Header -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h2 class="h5 mb-0 fw-semibold">All Subjects</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                    data-bs-target="#subjectFilters" aria-expanded="false">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @can('create subjects')
                    <a href="{{ route('subjects.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add Subject
                    </a>
                @endcan
            </div>
        </div>

        <!-- Filter Section -->
        <div class="collapse" id="subjectFilters">
            <div class="p-3 border-bottom">
                <form method="POST" action="{{ route('subjects.index') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ request('name') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Department</label>
                            <select name="department_id" class="form-select">
                                <option value="">All</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}"
                                        {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 d-flex justify-content-end gap-2 align-items-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('subjects.index') }}" class="btn btn-outline-secondary">
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
                            <th class="ps-4">Name</th>
                            <th>Department</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($subjects as $subject)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $subject->name }}</td>
                                <td>{{ $subject->department->name ?? '-' }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        @can('edit subjects')
                                            <a href="{{ route('subjects.edit', $subject->id) }}"
                                                class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete subjects')
                                            <form method="POST" action="{{ route('subjects.destroy', $subject->id) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-entry"
                                                    data-id="{{ $subject->id }}" title="Delete">
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
                                    <i class="bi bi-book fs-1 text-muted"></i>
                                    <h5 class="mt-3">No subjects found</h5>
                                    <p class="text-muted">Try adjusting your search filters</p>
                                    @can('create subjects')
                                        <a href="{{ route('subjects.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add New Subject
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($subjects->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$subjects" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
