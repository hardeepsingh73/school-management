<div id="sidebar" class="bg-white border-end d-none d-lg-block shadow-sm" style="width: 250px;">
    <div class="list-group list-group-flush vh-100 overflow-auto">
        <ul class="side-menu list-unstyled mb-0">
            {{--
            <!-- Dashboard -->
            <li class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}"
                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }}">
                    <i class="bi bi-house-door me-2 fs-5"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            --}}

            <!-- ===================== MANAGEMENT ===================== -->
            <li class="sidebar-header px-3 pt-3 pb-2">
                <span class="text-uppercase small fw-bold text-muted">Management</span>
            </li>

            <!-- Staff Management (Collapsible) -->
            @canany(['view users', 'view roles', 'view permissions'])
                @php
                    $staffActive =
                        request()->routeIs('users.*') ||
                        request()->routeIs('roles.*') ||
                        request()->routeIs('permissions.*');
                @endphp
                <li class="sidebar-item {{ $staffActive ? 'active' : '' }}">
                    <a href="#staff-nav"
                        class="list-group-item list-group-item-action d-flex align-items-center {{ $staffActive ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" aria-expanded="{{ $staffActive ? 'true' : 'false' }}"
                        aria-controls="staff-nav">
                        <i class="bi bi-shield-lock me-2 fs-5"></i>
                        <span>Staff Management</span>
                        <i class="bi bi-chevron-down ms-auto transition-all"></i>
                    </a>
                    <ul id="staff-nav" class="submenu collapse {{ $staffActive ? 'show' : '' }} list-unstyled"
                        data-bs-parent="#sidebar">
                        @can('view users')
                            <li class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                                <a href="{{ route('users.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('users.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-people me-2"></i>
                                    <span>Staff Users</span>
                                </a>
                            </li>
                        @endcan

                        @can('view roles')
                            <li class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
                                <a href="{{ route('roles.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('roles.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-person-badge me-2"></i>
                                    <span>Roles</span>
                                </a>
                            </li>
                        @endcan

                        @can('view permissions')
                            <li class="{{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                                <a href="{{ route('permissions.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('permissions.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-key me-2"></i>
                                    <span>Permissions</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            <!-- Academic Management -->
            @canany(['view students', 'view teachers', 'view departments', 'view subjects', 'view school classes'])
                @php
                    $academicActive =
                        request()->routeIs('students.*') ||
                        request()->routeIs('teachers.*') ||
                        request()->routeIs('departments.*') ||
                        request()->routeIs('subjects.*') ||
                        request()->routeIs('school_classes.*');
                @endphp

                <li class="sidebar-item {{ $academicActive ? 'active' : '' }}">
                    <a href="#academic-nav"
                        class="list-group-item list-group-item-action d-flex align-items-center {{ $academicActive ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" aria-expanded="{{ $academicActive ? 'true' : 'false' }}"
                        aria-controls="academic-nav">
                        <i class="bi bi-journal-bookmark me-2 fs-5"></i>
                        <span>Academic</span>
                        <i class="bi bi-chevron-down ms-auto transition-all"></i>
                    </a>
                    <ul id="academic-nav" class="submenu collapse {{ $academicActive ? 'show' : '' }} list-unstyled"
                        data-bs-parent="#sidebar">
                        @can('view students')
                            <li class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
                                <a href="{{ route('students.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('students.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-person-vcard me-2"></i>
                                    <span>Students</span>
                                </a>
                            </li>
                        @endcan

                        @can('view teachers')
                            <li class="{{ request()->routeIs('teachers.*') ? 'active' : '' }}">
                                <a href="{{ route('teachers.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('teachers.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-person-badge me-2"></i>
                                    <span>Teachers</span>
                                </a>
                            </li>
                        @endcan

                        @can('view departments')
                            <li class="{{ request()->routeIs('departments.*') ? 'active' : '' }}">
                                <a href="{{ route('departments.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('departments.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-building me-2"></i>
                                    <span>Departments</span>
                                </a>
                            </li>
                        @endcan

                        @can('view subjects')
                            <li class="{{ request()->routeIs('subjects.*') ? 'active' : '' }}">
                                <a href="{{ route('subjects.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('subjects.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-book me-2"></i>
                                    <span>Subjects</span>
                                </a>
                            </li>
                        @endcan

                        @can('view school classes')
                            <li class="{{ request()->routeIs('school_classes.*') ? 'active' : '' }}">
                                <a href="{{ route('school_classes.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('school_classes.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-collection me-2"></i>
                                    <span>Classes</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            <!-- Attendance & Exams -->
            @canany([
                'view attendances',
                'view exams',
                'view results',
                'view timetables',
                'view
                class-subject',
                ])
                @php
                    $schoolActive =
                        request()->routeIs('attendances.*') ||
                        request()->routeIs('exams.*') ||
                        request()->routeIs('results.*') ||
                        request()->routeIs('timetables.*') ||
                        request()->routeIs('class-subject.*');
                @endphp

                <li class="sidebar-item {{ $schoolActive ? 'active' : '' }}">
                    <a href="#school-nav"
                        class="list-group-item list-group-item-action d-flex align-items-center {{ $schoolActive ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" aria-expanded="{{ $schoolActive ? 'true' : 'false' }}"
                        aria-controls="school-nav">
                        <i class="bi bi-calendar-check me-2 fs-5"></i>
                        <span>School Operations</span>
                        <i class="bi bi-chevron-down ms-auto transition-all"></i>
                    </a>
                    <ul id="school-nav" class="submenu collapse {{ $schoolActive ? 'show' : '' }} list-unstyled"
                        data-bs-parent="#sidebar">
                        @can('view attendances')
                            <li class="{{ request()->routeIs('attendances.*') ? 'active' : '' }}">
                                <a href="{{ route('attendances.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('attendances.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-clipboard-check me-2"></i>
                                    <span>Attendances</span>
                                </a>
                            </li>
                        @endcan

                        @can('view exams')
                            <li class="{{ request()->routeIs('exams.*') ? 'active' : '' }}">
                                <a href="{{ route('exams.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('exams.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-file-text me-2"></i>
                                    <span>Exams</span>
                                </a>
                            </li>
                        @endcan

                        @can('view results')
                            <li class="{{ request()->routeIs('results.*') ? 'active' : '' }}">
                                <a href="{{ route('results.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('results.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-graph-up me-2"></i>
                                    <span>Results</span>
                                </a>
                            </li>
                        @endcan

                        @can('view timetables')
                            <li class="{{ request()->routeIs('timetables.*') ? 'active' : '' }}">
                                <a href="{{ route('timetables.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('timetables.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-calendar-week me-2"></i>
                                    <span>Timetables</span>
                                </a>
                            </li>
                        @endcan

                        @can('view class-subject')
                            <li class="{{ request()->routeIs('class-subject.*') ? 'active' : '' }}">
                                <a href="{{ route('class-subject.index') }}"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('class-subject.*') ? 'active fw-semibold' : '' }}">
                                    <i class="bi bi-journal-check me-2"></i>
                                    <span>Class Subjects</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            <!-- ===================== LOGS ===================== -->
            <li class="sidebar-header px-3 pt-3 pb-2">
                <span class="text-uppercase small fw-bold text-muted">Logs</span>
            </li>

            @canany(['view activity-logs', 'view api-logs', 'view email-logs', 'view error-logs', 'view login-history'])
                @php
                    $logsActive =
                        request()->routeIs('activity-logs.*') ||
                        request()->routeIs('api-logs.*') ||
                        request()->routeIs('email-logs.*') ||
                        request()->routeIs('error-logs.*') ||
                        request()->routeIs('login-history.*');
                @endphp

                <li class="sidebar-item {{ $logsActive ? 'active' : '' }}">
                    <a href="#logs-nav"
                        class="list-group-item list-group-item-action d-flex align-items-center {{ $logsActive ? '' : 'collapsed' }}"
                        data-bs-toggle="collapse" aria-expanded="{{ $logsActive ? 'true' : 'false' }}"
                        aria-controls="logs-nav">
                        <i class="bi bi-journal-text me-2 fs-5"></i>
                        <span>System Logs</span>
                        <i class="bi bi-chevron-down ms-auto transition-all"></i>
                    </a>

                    <ul id="logs-nav" class="submenu collapse {{ $logsActive ? 'show' : '' }} list-unstyled"
                        data-bs-parent="#sidebar">
                        {{-- Activity Logs --}}
                        @can('view activity logs')
                            @if (setting('activity_logs', true))
                                <li class="{{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                                    <a href="{{ route('activity-logs.index') }}"
                                        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('activity-logs.*') ? 'active fw-semibold' : '' }}">
                                        <i class="bi bi-list-check me-2"></i>
                                        <span>Activity Logs</span>
                                    </a>
                                </li>
                            @endif
                        @endcan

                        {{-- API Logs --}}
                        @can('view api logs')
                            @if (setting('api_logs', true))
                                <li class="{{ request()->routeIs('api-logs.*') ? 'active' : '' }}">
                                    <a href="{{ route('api-logs.index') }}"
                                        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('api-logs.*') ? 'active fw-semibold' : '' }}">
                                        <i class="bi bi-activity me-2"></i>
                                        <span>API Logs</span>
                                    </a>
                                </li>
                            @endif
                        @endcan

                        {{-- Email Logs --}}
                        @can('view email logs')
                            @if (setting('email_logs', true))
                                <li class="{{ request()->routeIs('email-logs.*') ? 'active' : '' }}">
                                    <a href="{{ route('email-logs.index') }}"
                                        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('email-logs.*') ? 'active fw-semibold' : '' }}">
                                        <i class="bi bi-envelope me-2"></i>
                                        <span>Email Logs</span>
                                    </a>
                                </li>
                            @endif
                        @endcan

                        {{-- Error Logs --}}
                        @can('view error logs')
                            @if (setting('error_logs', true))
                                <li class="{{ request()->routeIs('error-logs.*') ? 'active' : '' }}">
                                    <a href="{{ route('error-logs.index') }}"
                                        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('error-logs.*') ? 'active fw-semibold' : '' }}">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        <span>Error Logs</span>
                                    </a>
                                </li>
                            @endif
                        @endcan

                        {{-- Login History --}}
                        @can('view login history')
                            @if (setting('login_history', true))
                                <li class="{{ request()->routeIs('login-history.*') ? 'active' : '' }}">
                                    <a href="{{ route('login-history.index') }}"
                                        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('login-history.*') ? 'active fw-semibold' : '' }}">
                                        <i class="bi bi-clock-history me-2"></i>
                                        <span>Login History</span>
                                    </a>
                                </li>
                            @endif
                        @endcan
                    </ul>
                </li>
            @endcanany

            <!-- Settings -->
            @can('edit settings')
                <li class="sidebar-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                    <a href="{{ route('settings.index') }}"
                        class="list-group-item list-group-item-action d-flex align-items-center {{ request()->routeIs('settings.*') ? 'active fw-semibold' : '' }}">
                        <i class="bi bi-gear me-2 fs-5"></i>
                        <span>Settings</span>
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</div>
