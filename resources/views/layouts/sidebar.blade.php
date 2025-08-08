<div id="sidebar" class="bg-white border-end d-none d-lg-block" style="width: 250px;">
    <div class="list-group list-group-flush vh-100 overflow-auto">
        @can('view settings')
            <a href="{{ route('settings') }}" class="list-group-item list-group-item-action py-3 {{ request()->routeIs('settings') ? 'active' : '' }}">
                <i class="bi bi-gear-fill me-2"></i> Settings
            </a>
        @endcan
    </div>
</div>