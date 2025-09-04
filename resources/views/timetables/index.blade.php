<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 mb-0 fw-semibold">
                <i class="bi bi-calendar-week me-2"></i> Timetable Management
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
            <i class="bi bi-calendar-week me-1"></i> Timetables
        </li>
    </x-slot>

    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <!-- Card Header -->
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h2 class="h5 mb-0 fw-semibold">All Timetables</h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse"
                    data-bs-target="#timetableFilters" aria-expanded="false">
                    <i class="bi bi-funnel me-1"></i> Filter
                </button>
                @can('create timetables')
                    <a href="{{ route('timetables.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Add Timetable
                    </a>
                @endcan
            </div>
        </div>

        <!-- Filter Section -->
        <div class="collapse" id="timetableFilters">
            <div class="p-3 border-bottom">
                <form method="POST" action="{{ route('timetables.index') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label small">Class</label>
                            <input type="text" name="class" class="form-control" value="{{ request('class') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Teacher</label>
                            <input type="text" name="teacher" class="form-control" value="{{ request('teacher') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ request('subject') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small">Day of Week</label>
                            <select name="day_of_week" class="form-select">
                                <option value="">All</option>
                                @foreach (consthelper('Timetable::$days') as $index => $day)
                                    <option value="{{ $index }}"
                                        {{ request('day_of_week') == $index ? 'selected' : '' }}>{{ $day }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                            <a href="{{ route('timetables.index') }}" class="btn btn-outline-secondary">
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
                            <th>Teacher</th>
                            <th>Subject</th>
                            <th>Day</th>
                            <th>Start - End</th>
                            <th>Room</th>
                            <th>Type</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($timetables as $timetable)
                            <tr>
                                <td class="ps-4">{{ $timetable->schoolClass->full_name ?? 'N/A' }}</td>
                                <td>{{ $timetable->teacher->user->name ?? 'N/A' }}</td>
                                <td>{{ $timetable->subject->name ?? 'N/A' }}</td>
                                <td>
                                    {{ ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'][$timetable->day_of_week] ?? 'N/A' }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($timetable->start_time)->format('H:i') }} -
                                    {{ \Carbon\Carbon::parse($timetable->end_time)->format('H:i') }}</td>
                                <td>{{ $timetable->room ?? '-' }}</td>
                                <td>
                                    @php
                                        $types = [1 => 'Regular', 2 => 'Makeup', 3 => 'Special'];
                                        $badges = [1 => 'primary', 2 => 'warning', 3 => 'info'];
                                    @endphp
                                    <span class="badge bg-{{ $badges[$timetable->schedule_type] ?? 'secondary' }}">
                                        {{ $types[$timetable->schedule_type] ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('timetables.show', $timetable->id) }}"
                                            class="btn btn-sm btn-outline-primary" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @can('edit timetables')
                                            <a href="{{ route('timetables.edit', $timetable->id) }}"
                                                class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('delete timetables')
                                            <form action="{{ route('timetables.destroy', $timetable->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger delete-entry"
                                                    data-id="{{ $timetable->id }}" title="Delete">
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
                                    <i class="bi bi-calendar-week fs-1 text-muted"></i>
                                    <h5 class="mt-3">No timetables found</h5>
                                    <p class="text-muted">Try adjusting your search filters</p>
                                    @can('create timetables')
                                        <a href="{{ route('timetables.create') }}" class="btn btn-primary mt-3">
                                            <i class="bi bi-plus-lg me-2"></i> Add New Timetable
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($timetables->hasPages())
                <div class="mt-4">
                    <x-pagination :paginator="$timetables" />
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
