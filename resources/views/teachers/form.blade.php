<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
            <div>
                <h2 class="fw-bold fs-3 mb-1">{{ isset($teacher) ? 'Edit Teacher' : 'Create New Teacher' }}</h2>
                <p class="text-muted mb-0 small">
                    {{ isset($teacher) ? 'Update teacher details' : 'Add a new teacher to the system' }}
                </p>
            </div>
            <div>
                <x-back-button :href="route('teachers.index')" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Teachers
                </x-back-button>
            </div>
        </div>
    </x-slot>

    <!-- Breadcrumbs -->
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('teachers.index') }}" class="text-decoration-none">Teachers</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">{{ isset($teacher) ? 'Edit' : 'Create' }}</li>
    </x-slot>

    <div class="container mt-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form action="{{ isset($teacher) ? route('teachers.update', $teacher) : route('teachers.store') }}"
                    method="POST" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                    @csrf
                    @if (isset($teacher))
                        @method('PUT')
                    @endif

                    <!-- Full Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold">Full Name <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" id="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $teacher->user->name ?? '') }}">
                        @error('name')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <label for="email" class="form-label fw-semibold">Email <span
                                class="text-danger">*</span></label>
                        <input type="email" name="email" id="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $teacher->user->email ?? '') }}">
                        @error('email')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="col-md-6">
                        <label for="password" class="form-label fw-semibold">
                            {{ isset($teacher) ? 'New Password' : 'Password' }}
                            @unless (isset($teacher))
                                <span class="text-danger">*</span>
                            @endunless
                        </label>
                        <div class="input-group">
                            <input type="password" name="password" id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="{{ isset($teacher) ? 'Leave blank to keep current password' : 'Minimum 8 characters' }}">
                            <button type="button" class="btn btn-outline-secondary toggle-password"
                                data-target="#password">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label fw-semibold">Confirm Password</label>
                        <div class="input-group">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control @error('password_confirmation') is-invalid @enderror">
                            <button type="button" class="btn btn-outline-secondary toggle-password"
                                data-target="#password_confirmation">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation')
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
                                    {{ old('gender', $teacher->user->gender ?? '') === $genderOption ? 'selected' : '' }}>
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
                            value="{{ old('dob', isset($teacher) && $teacher->user->dob ? $teacher->user->dob->format('Y-m-d') : '') }}">
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
                                    {{ old('blood_group', $teacher->user->blood_group ?? '') === $bg ? 'selected' : '' }}>
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
                            value="{{ old('phone', $teacher->user->phone ?? '') }}">
                        @error('phone')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Department <x-star /></label>
                        <select name="department_id" class="form-select @error('department_id') is-invalid @enderror">
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}"
                                    {{ old('department_id', $teacher->department_id ?? '') == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('department_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Designation</label>
                        <input type="text" name="designation"
                            class="form-control @error('designation') is-invalid @enderror"
                            value="{{ old('designation', $teacher->designation ?? '') }}">
                        @error('designation')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                    <!-- Status -->
                    <div class="col-md-6">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select name="status" id="status"
                            class="form-select @error('status') is-invalid @enderror">
                            @foreach (consthelper('User::$statuses') as $id => $status)
                                <option value="{{ $id }}"
                                    {{ old('status', $teacher->user->status ?? 1) == $id ? 'selected' : '' }}>
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
                        @if (isset($teacher->user) && $teacher->user->profileImage)
                            <div class="mb-2 text-center">
                                <img src="{{ Storage::url($teacher->user->profileImage->path) }}" alt="Profile Image"
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

                    <!-- Address -->
                    <div class="col-12">
                        <label for="address" class="form-label fw-semibold">Address</label>
                        <textarea name="address" id="address" rows="3" class="form-control @error('address') is-invalid @enderror">{{ old('address', $teacher->user->address ?? '') }}</textarea>
                        @error('address')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Additional Info -->
                    <div class="col-12">
                        <!-- Additional Info -->
                        <label for="additional_information" class="form-label fw-semibold">Additional
                            Information</label>

                        <div id="additional-info-container">
                            <div class="row g-2 mb-3">
                                <div class="col-md-4">
                                    <input type="text" id="new-label" class="form-control" placeholder="Label" />
                                </div>
                                <div class="col-md-6">
                                    <input type="text" id="new-value" class="form-control" placeholder="Value" />
                                </div>
                                <div class="col-md-2">
                                    <button type="button" id="add-info-btn"
                                        class="btn btn-success w-100">Add</button>
                                </div>
                            </div>

                            <ul id="additional-info-list" class="list-group mb-3"></ul>

                            <input type="hidden" name="additional_information" id="additional_information" />
                        </div>

                        @error('additional_information')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <!-- Actions -->
                    <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                        <button type="reset" class="btn btn-outline-secondary"><i
                                class="bi bi-arrow-counterclockwise me-1"></i> Reset</button>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save me-1"></i>
                            {{ isset($teacher) ? 'Update Teacher' : 'Create Teacher' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-slot name="script">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $(function() {
                    var entries = {};

                    // Try parse existing JSON
                    try {
                        entries = {!! json_encode(old('additional_information', $teacher->additional_information ?? '{}')) !!};
                        if (typeof entries === "string") {
                            entries = JSON.parse(entries || '{}');
                        }
                    } catch (e) {
                        entries = {};
                    }

                    function renderEntries() {
                        var $list = $('#additional-info-list').empty();
                        $.each(entries, function(label, value) {
                            var $li = $('<li>').addClass(
                                'list-group-item d-flex justify-content-between align-items-center');
                            $li.html('<div><strong>' + label + ':</strong> ' + value + '</div>');
                            var $delBtn = $('<button>').addClass('btn btn-sm btn-danger').text('Delete')
                                .appendTo(
                                    $li);
                            $delBtn.on('click', function() {
                                delete entries[label];
                                updateJSON();
                                renderEntries();
                            });
                            $list.append($li);
                        });
                        updateJSON();
                    }

                    function updateJSON() {
                        $('#additional_information').val(JSON.stringify(entries));
                    }

                    $('#add-info-btn').on('click', function() {
                        var label = $('#new-label').val().trim();
                        var value = $('#new-value').val().trim();
                        if (!label || !value) {
                            alert('Please fill in both label and value');
                            return;
                        }
                        if (entries.hasOwnProperty(label)) {
                            alert('Label already exists');
                            return;
                        }
                        entries[label] = value;
                        $('#new-label, #new-value').val('');
                        renderEntries();
                    });

                    renderEntries();
                });
            });
        </script>
    </x-slot>
</x-app-layout>
