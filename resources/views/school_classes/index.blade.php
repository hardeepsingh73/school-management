<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 fw-semibold">
                <i class="bi bi-collection me-2"></i> Class Management
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
            <i class="bi bi-collection me-1"></i> Classes
        </li>
    </x-slot>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <!-- Card Header -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h2 class="h5 mb-0 fw-semibold">All Classes</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                    data-bs-target="#classFilters" aria-expanded="false">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @can('create school classes')
                    <a href="{{ route('school_classes.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add Class
                    </a>
                @endcan
            </div>
        </div>

        <!-- Filter Section -->
        <div class="collapse" id="classFilters">
            <div class="p-3 border-bottom">
                <form method="POST" action="{{ route('school_classes.index') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small">Class Name</label>
                            <input type="text" name="name" class="form-control" value="{{ request('name') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small">Section</label>
                            <input type="text" name="section" class="form-control" value="{{ request('section') }}">
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('school_classes.index') }}" class="btn btn-outline-secondary">
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
                            <th>Section</th>
                            <th>Class Teacher</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $class->full_name }}</td>
                                <td>{{ $class->section ?? '-' }}</td>
                                <td>
                                    @if ($class->classTeacher)
                                        {{ $class->classteacher->user->name }}
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        @can('edit school classes')
                                            <a href="{{ route('school_classes.edit', $class) }}"
                                                class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete school classes')
                                            <form method="POST" action="{{ route('school_classes.destroy', $class) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-entry"
                                                    data-id="{{ $class->id }}" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="bi bi-collection fs-1 text-muted"></i>
                                    <h5 class="mt-3">No classes found</h5>
                                    <p class="text-muted">Try adjusting your search filters</p>
                                    @can('create school classes')
                                        <a href="{{ route('school_classes.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add New Class
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($classes->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$classes" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
