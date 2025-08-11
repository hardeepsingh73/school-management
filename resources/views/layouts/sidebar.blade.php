<div id="sidebar" class="bg-white border-end d-none d-lg-block" style="width: 250px;">
    <div class="list-group list-group-flush vh-100 overflow-auto">
        <ul class="side-menu list-unstyled mb-0">
            <!-- Dashboard -->
            <li class="sidebar-layout {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="d-flex align-items-center p-3 text-decoration-none">
                    <i class="fas fa-home me-3"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Management Header -->
            <li class="px-3 pt-3 pb-2">
                <span class="text-uppercase small fw-bold text-muted">Management</span>
            </li>

            <!-- Staff Management -->
            @canany(['view users', 'view roles', 'view permissions', 'delete permissions'])
            @php
            $staffActive = request()->routeIs('users.*') || request()->routeIs('roles.*') ||
            request()->routeIs('permissions.*');
            @endphp
            <li class="sidebar-layout {{ $staffActive ? 'active' : '' }}">
                <a href="#staff-nav"
                    class="d-flex align-items-center p-3 text-decoration-none {{ $staffActive ? '' : 'collapsed' }}"
                    data-bs-toggle="collapse" aria-expanded="{{ $staffActive ? 'true' : 'false' }}"
                    aria-controls="staff-nav">
                    <i class="fas fa-user-shield me-3"></i>
                    <span>Staff Management</span>
                    <i class="fas fa-chevron-right ms-auto arrow-active"></i>
                </a>
                <ul id="staff-nav" class="submenu collapse {{ $staffActive ? 'show' : '' }} list-unstyled ps-4"
                    data-bs-parent="#sidebar">
                    @can('view users')
                    <li class="sidebar-layout {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <a href="{{ route('users.index') }}"
                            class="d-flex align-items-center py-2 text-decoration-none">
                            <i class="fas fa-users-cog me-3"></i>
                            <span>Staff Users</span>
                        </a>
                    </li>
                    @endcan
                    @can('view roles')
                    <li class="sidebar-layout {{ request()->routeIs('roles.*') ? 'active' : '' }}">
                        <a href="{{ route('roles.index') }}"
                            class="d-flex align-items-center py-2 text-decoration-none">
                            <i class="fas fa-user-tag me-3"></i>
                            <span>Roles</span>
                        </a>
                    </li>
                    @endcan
                    @can('view permissions')
                    <li class="sidebar-layout {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                        <a href="{{ route('permissions.index') }}"
                            class="d-flex align-items-center py-2 text-decoration-none">
                            <i class="fas fa-key me-3"></i>
                            <span>Permissions</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </li>
            @endcanany

            <!-- Settings -->
            @can('edit settings')
            <li class="sidebar-layout {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                <a href="{{ route('settings.index') }}" class="d-flex align-items-center p-3 text-decoration-none">
                    <i class="fas fa-cog me-3"></i>
                    <span>Settings</span>
                </a>
            </li>
            @endcan

            <!-- Login History -->
            @can('view login history')
            @if (setting('login_history', true))
            <li class="sidebar-layout {{ request()->routeIs('login-history.*') ? 'active' : '' }}">
                <a href="{{ route('login-history.index') }}" class="d-flex align-items-center p-3 text-decoration-none">
                    <i class="fas fa-history me-3"></i>
                    <span>Login History</span>
                </a>
            </li>
            @endif
            @endcan

            <!-- Activity Logs -->
            @can('view activity logs')
            @if (setting('activity_logs', true))
            <li class="sidebar-layout {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                <a href="{{ route('activity-logs.index') }}" class="d-flex align-items-center p-3 text-decoration-none">
                    <i class="fas fa-clipboard-list me-3"></i>
                    <span>Activity Logs</span>
                </a>
            </li>
            @endif
            @endcan

            <!-- Error Logs -->
            @can('view error logs')
            @if (setting('error_logs', true))
            <li class="sidebar-layout {{ request()->routeIs('error-logs.*') ? 'active' : '' }}">
                <a href="{{ route('error-logs.index') }}" class="d-flex align-items-center p-3 text-decoration-none">
                    <i class="fas fa-exclamation-triangle me-3"></i>
                    <span>Error Logs</span>
                </a>
            </li>
            @endif
            @endcan
        </ul>
    </div>
</div>