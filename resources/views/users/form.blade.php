<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
            <div>
                <h2 class="fw-bold fs-3 mb-1">{{ isset($user) ? 'Edit User' : 'Create New User' }}</h2>
                <p class="text-muted mb-0 small">
                    {{ isset($user) ? 'Update user details and permissions' : 'Add a new user to the system' }}
                </p>
            </div>
            <div>
                <x-back-button :href="route('users.index')" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Users
                </x-back-button>
            </div>
        </div>
    </x-slot>

    <!-- Breadcrumbs -->
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index') }}" class="text-decoration-none">Users</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ isset($user) ? 'Edit' : 'Create' }}</li>
    </x-slot>

    <div class="container mt-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST"
                    enctype="multipart/form-data" id="userForm" class="row g-3 needs-validation" novalidate>
                    @csrf
                    @if (isset($user))
                        @method('PUT')
                    @endif

                    <!-- Full Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Full Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name ?? '') }}">
                        @error('name')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email Address <span
                                class="text-danger">*</span></label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email ?? '') }}">
                        @error('email')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="col-md-6">
                        <label for="gender" class="form-label fw-semibold">Gender</label>
                        <select name="gender" id="gender" class="form-select @error('gender') is-invalid @enderror">
                            <option value="">Select Gender</option>
                            @foreach (['male', 'female', 'other', 'unspecified'] as $genderOption)
                                <option value="{{ $genderOption }}"
                                    {{ old('gender', $user->gender ?? '') === $genderOption ? 'selected' : '' }}>
                                    {{ ucfirst($genderOption) }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- DOB -->
                    <div class="col-md-6">
                        <label for="dob" class="form-label fw-semibold">Date of Birth</label>
                        <input type="date" name="dob" id="dob"
                            class="form-control @error('dob') is-invalid @enderror"
                            value="{{ old('dob', isset($user) && $user->dob ? $user->dob->format('Y-m-d') : '') }}">
                        @error('dob')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Blood Group -->
                    <div class="col-md-6">
                        <label for="blood_group" class="form-label fw-semibold">Blood Group</label>
                        <select name="blood_group" id="blood_group"
                            class="form-select @error('blood_group') is-invalid @enderror">
                            <option value="">Select Blood Group</option>
                            @foreach (['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}"
                                    {{ old('blood_group', $user->blood_group ?? '') === $bg ? 'selected' : '' }}>
                                    {{ $bg }}
                                </option>
                            @endforeach
                        </select>
                        @error('blood_group')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-md-6">
                        <label for="phone" class="form-label fw-semibold">Phone</label>
                        <input type="text" name="phone" id="phone"
                            class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $user->phone ?? '') }}">
                        @error('phone')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-semibold">
                            {{ isset($user) ? 'New Password' : 'Password' }}
                            @unless (isset($user))
                                <span class="text-danger">*</span>
                            @endunless
                        </label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="{{ isset($user) ? 'Leave blank to keep current password' : 'Minimum 8 characters' }}">
                            <button type="button" class="btn btn-outline-secondary toggle-password"
                                data-target="#password">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password')
                                <x-error-msg>{{ $message }}</x-error-msg>
                            @enderror
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold">
                            Confirm Password
                            @unless (isset($user))
                                <span class="text-danger">*</span>
                            @endunless
                        </label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            <button type="button" class="btn btn-outline-secondary toggle-password"
                                data-target="#password_confirmation">
                                <i class="bi bi-eye"></i>
                            </button>
                            @error('password_confirmation')
                                <x-error-msg>{{ $message }}</x-error-msg>
                            @enderror
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status"
                            class="form-select @error('status') is-invalid @enderror">
                            @foreach (consthelper('User::$statuses') as $id => $status)
                                <option value="{{ $id }}"
                                    {{ old('status', $teacher->status ?? '') == $id ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Profile Image -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Profile Image</label>
                        @if (isset($user) && $user->profileImage)
                            <div class="mb-2 text-center">
                                <img src="{{ Storage::url($user->profileImage->path) }}" alt="Profile Image"
                                    class="rounded shadow-sm img-fluid" style="max-height: 120px;">
                                <div class="small text-muted mt-1">Current profile image</div>
                            </div>
                        @endif
                        <input type="file" name="profile_image" id="profile_image"
                            class="form-control @error('profile_image') is-invalid @enderror" accept="image/*">
                        <small class="form-text text-muted">Accepted: JPG, PNG, GIF. Max size: 2MB</small>
                        @error('profile_image')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Roles -->
                    @can('edit users')
                        <div class="col-12">
                            <label class="form-label fw-semibold">Roles</label>
                            @if ($roles->isNotEmpty())
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach ($roles as $role)
                                        <div class="form-check">
                                            <input type="radio" id="role-{{ $role->id }}" name="roles"
                                                value="{{ $role->name }}" class="form-check-input"
                                                {{ (isset($user) && $user->roles->contains($role->id)) || old('roles') == $role->name ? 'checked' : '' }}>
                                            <label for="role-{{ $role->id }}"
                                                class="form-check-label">{{ $role->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-warning small mb-0">No roles available</div>
                            @endif
                            @error('roles')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    @endcan

                    <!-- Address -->
                    <div class="col-12">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address ?? '') }}</textarea>
                        @error('address')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                        </button>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save me-1"></i> {{ isset($user) ? 'Update User' : 'Create User' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
