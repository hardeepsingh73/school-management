<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-4 text-dark mb-0">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
    </x-slot>
    <div class="container py-3">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white border-0 py-3">
                        <h3 class="h6 mb-0 fw-medium">Welcome</h3>
                    </div>
                    <div class="card-body p-4">
                        <div class="alert alert-success mb-0">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ __("You're successfully logged in!") }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
