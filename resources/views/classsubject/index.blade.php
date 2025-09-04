<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 fw-semibold">
                <i class="bi bi-diagram-3 me-2"></i> Class Subject Assignments
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
            <i class="bi bi-diagram-3 me-1"></i> Class Subject Assignments
        </li>
    </x-slot>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">

        <!-- Card Header -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h2 class="h5 mb-0 fw-semibold">All Assignments</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                    data-bs-target="#classSubjectFilters">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>

                @can('create class subject')
                    <a href="{{ route('class-subject.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Assign Subject
                    </a>
                @endcan
            </div>
        </div>

        <!-- Filter Section -->
        <div class="collapse" id="classSubjectFilters">
            <div class="p-3 border-bottom">
                <form method="POST" action="{{ route('class-subject.index') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small">Class</label>
                            <select name="class_id" class="form-select">
                                <option value="">All</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                        {{ $class->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
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

                        <div class="col-md-3">
                            <label class="form-label small">Teacher</label>
                            <select name="teacher_id" class="form-select">
                                <option value="">All</option>
                                @foreach ($teachers as $teacher)
                                    <option value="{{ $teacher->id }}"
                                        {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                        {{ $teacher->user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex justify-content-end gap-2 align-items-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('class-subject.index') }}" class="btn btn-outline-secondary">
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
                            <th class="ps-4">Class</th>
                            <th>Subject</th>
                            <th>Teacher</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classSubjects as $classSubject)
                            <tr>
                                <td class="ps-4"> {{ $classSubject->schoolClass->full_name }} </td>
                                <td>{{ $classSubject->subject->name }}</td>
                                <td>{{ $classSubject->teacher->user->name ?? '-' }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        @can('edit class subject')
                                            <a href="{{ route('class-subject.edit', [$classSubject->class_id, $classSubject->subject_id]) }}"
                                                class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete class subject')
                                            <form method="POST"
                                                action="{{ route('class-subject.destroy', [$classSubject->class_id, $classSubject->subject_id]) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-entry"
                                                    data-id="{{ $classSubject->class_id }}-{{ $classSubject->subject_id }}"
                                                    title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <i class="bi bi-diagram-3 fs-1 text-muted"></i>
                                    <h5 class="mt-3">No class subjects found</h5>
                                    <p class="text-muted">Try adjusting your search filters</p>
                                    @can('create class subject')
                                        <a href="{{ route('class-subject.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Assign Subject to Class
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($classSubjects->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$classSubjects" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
