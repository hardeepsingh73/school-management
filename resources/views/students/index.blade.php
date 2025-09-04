<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 fw-semibold">
                <i class="bi bi-person-vcard me-2"></i> Student Management
            </h1>
        </div>
    </x-slot>

    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item">
            <a class="btn-link text-decoration-none" href="{{ route('dashboard') }}">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            <i class="bi bi-people me-1"></i> Students
        </li>
    </x-slot>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h2 class="h5 mb-0 fw-semibold">All Students</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                    data-bs-target="#studentFilters" aria-expanded="false">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @can('create students')
                    <a href="{{ route('students.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add Student
                    </a>
                @endcan
            </div>
        </div>

        <div class="collapse" id="studentFilters">
            <div class="p-3 border-bottom">
                <form method="POST" action="{{ route('students.index') }}">
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
                            <label class="form-label small">Class</label>
                            <select name="class" class="form-select">
                                <option value="">All Classes</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}"
                                        {{ request('class') == $class->id ? 'selected' : '' }}>
                                        {{ $class->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('students.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Student</th>
                            <th>Class</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($students as $student)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $student->user->profileImage ? Storage::url($student->user->profileImage->path) : 'https://ui-avatars.com/api/?name=' . urlencode($student->user->name) . '&size=40' }}"
                                            class="rounded-circle border shadow-sm" width="36" height="36"
                                            alt="User">
                                        <span>{{ $student->user->name }}</span>
                                    </div>
                                </td>

                                <td>
                                    @if ($student->schoolClass)
                                        <span class="badge bg-secondary">
                                            {{ $student->schoolClass->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td class="text-truncate" style="max-width:200px;">{{ $student->user->email }}</td>
                                <td><span
                                        class="badge {{ $student->user->status_badge_class }}">{{ $student->user->status_label ?? 'N/A' }}</span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('students.show', $student->id) }}"
                                            class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                            title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @can('edit students')
                                            <a href="{{ route('students.edit', $student->id) }}"
                                                class="btn btn-sm btn-outline-secondary" data-bs-toggle="tooltip"
                                                title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete students')
                                            <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="btn btn-sm btn-outline-danger delete-entry delete-student"
                                                    data-id="{{ $student->id }}" data-bs-toggle="tooltip" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-person-vcard fs-1 text-muted"></i>
                                    <h5 class="mt-3">No students found</h5>
                                    <p class="text-muted">Try adjusting your search filters</p>
                                    @can('create students')
                                        <a href="{{ route('students.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add New Student
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($students->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$students" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
