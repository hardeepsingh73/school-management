<x-app-layout>
    <x-slot name="header">
        <h2 class="fw-semibold fs-3 text-dark mb-0">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a class ="btn-link" href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a class ="btn-link" href="{{ route('profile') }}">Profile</a></li>
        <li class="breadcrumb-item active" aria-current="page">Edit</li>
    </x-slot>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h3 class="h5 mb-0">{{ __('Profile Information') }}</h3>
                </div>
                <div class="card-body p-4">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h3 class="h5 mb-0">{{ __('Update Password') }}</h3>
                </div>
                <div class="card-body p-4">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card mb-4 shadow-sm border-danger">
                <div class="card-header bg-white py-3 border-danger">
                    <h3 class="h5 mb-0 text-danger">{{ __('Delete Account') }}</h3>
                </div>
                <div class="card-body p-4">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
