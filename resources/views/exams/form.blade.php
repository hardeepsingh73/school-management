<x-app-layout>
    {{-- Page Header --}}
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                {{ isset($exam) ? 'Edit Exam' : 'Create New Exam' }}
            </h2>
        </div>
    </x-slot>

    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item">
            <a class="btn-link text-decoration-none" href="{{ route('dashboard') }}">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </li>
        <li class="breadcrumb-item">
            <a class="btn-link text-decoration-none" href="{{ route('exams.index') }}">
                <i class="bi bi-file-earmark-text me-1"></i> Exams
            </a>
        </li>
        <li class="breadcrumb-item active">{{ isset($exam) ? 'Edit' : 'Create' }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ isset($exam) ? route('exams.update', $exam) : route('exams.store') }}" method="POST"
                novalidate>
                @csrf
                @if (isset($exam))
                    @method('PUT')
                @endif


                {{-- ===== ACADEMIC INFORMATION ===== --}}
                <h5 class="mt-5 mb-4 border-bottom pb-2 text-success fw-semibold">
                    <i class="bi bi-building me-2"></i> Academic Information
                </h5>
                <div class="row g-3">

                    {{-- Subject --}}
                    <div class="col-md-6">
                        <label class="form-label">Subject</label>
                        <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror">
                            <option value="">-- Select Subject --</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ old('subject_id', $exam->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- Class --}}
                    <div class="col-md-6">
                        <label class="form-label">Class</label>
                        <select name="class_id" class="form-select @error('class_id') is-invalid @enderror">
                            <option value="">-- Select Class --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $exam->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                    {{ $class->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                    {{-- Exam Date --}}
                    <div class="col-md-4">
                        <label class="form-label">Exam Date <x-star /></label>
                        <input type="date" name="exam_date"
                            class="form-control @error('exam_date') is-invalid @enderror"
                            value="{{ old('exam_date', isset($exam) ? \Carbon\Carbon::parse($exam->exam_date)->format('Y-m-d') : '') }}">
                        @error('exam_date')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                    {{-- Type --}}
                    <div class="col-md-4">
                        <label class="form-label">Exam Type</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror">
                            @foreach (consthelper('Exams::$types') as $id => $label)
                                <option value="{{ $id }}"
                                    {{ old('status', $exam->type ?? '') == $id ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('type')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

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
                                    <button type="button" id="add-info-btn" class="btn btn-success w-100">Add</button>
                                </div>
                            </div>

                            <ul id="additional-info-list" class="list-group mb-3"></ul>

                            <input type="hidden" name="additional_information" id="additional_information" />
                        </div>

                        @error('additional_information')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>
                </div>

                {{-- ACTIONS --}}
                <div class="mt-4 d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary px-4">
                        {{ isset($exam) ? 'Update Exam' : 'Create Exam' }}
                    </button>
                    <x-back-button :href="route('exams.index')" class="btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
                    </x-back-button>
                </div>
            </form>
        </div>
    </div>

    <x-slot name="script">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $(function() {
                    var entries = {};

                    // Try parse existing JSON
                    try {
                        entries = {!! json_encode(old('additional_information', $exam->additional_information ?? '{}')) !!};
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
