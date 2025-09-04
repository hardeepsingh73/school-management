<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 fw-semibold">
                <i class="bi bi-person-badge me-2"></i> Teacher Management
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
            <i class="bi bi-people me-1"></i> Teachers
        </li>
    </x-slot>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">

        <!-- Card Header -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h2 class="h5 mb-0 fw-semibold">All Teachers</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                    data-bs-target="#teacherFilters" aria-expanded="false">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @can('create teachers')
                    <a href="{{ route('teachers.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add Teacher
                    </a>
                @endcan
            </div>
        </div>

        <!-- Filter Section -->
        <div class="collapse" id="teacherFilters">
            <div class="p-3 border-bottom">
                <form method="POST" action="{{ route('teachers.index') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small">Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Search by name"
                                value="{{ request('name') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Email</label>
                            <input type="text" name="email" class="form-control" placeholder="Search by email"
                                value="{{ request('email') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Department</label>
                            <select name="department_id" class="form-select">
                                <option value="">All Departments</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}"
                                        {{ request('department_id') == $department->id ? 'selected' : '' }}>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('teachers.index') }}" class="btn btn-outline-secondary">
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
                            <th class="ps-4">Teacher</th>
                            <th>Department</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teachers as $teacher)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $teacher->user->profileImage ? Storage::url($teacher->user->profileImage->path) : 'https://ui-avatars.com/api/?name=' . urlencode($teacher->user->name) . '&size=40' }}"
                                            class="rounded-circle border shadow-sm" width="36" height="36"
                                            alt="User">
                                        <span>{{ $teacher->user->name }}</span>
                                    </div>
                                </td>
                                <td>
                                    @if ($teacher->department)
                                        <span class="badge bg-secondary">
                                            {{ $teacher->department->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td class="text-truncate" style="max-width:200px;">
                                    {{ $teacher->user->email ?? 'N/A' }}
                                </td>
                                <td>{{ $teacher->phone ?? 'N/A' }}</td>
                                <td><span
                                        class="badge {{ $teacher->user->status_badge_class }}">{{ $teacher->user->status_label ?? 'N/A' }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('teachers.show', $teacher->id) }}"
                                            class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                            title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @can('edit teachers')
                                            <a href="{{ route('teachers.edit', $teacher->id) }}"
                                                class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete teachers')
                                            <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-entry delete-teacher"
                                                    data-id="{{ $teacher->id }}" data-bs-toggle="tooltip" title="Delete">
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
                                    <i class="bi bi-person-badge fs-1 text-muted"></i>
                                    <h5 class="mt-3">No teachers found</h5>
                                    <p class="text-muted">Try adjusting your search filters</p>
                                    @can('create teachers')
                                        <a href="{{ route('teachers.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add New Teacher
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($teachers->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$teachers" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
