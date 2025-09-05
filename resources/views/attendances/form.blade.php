<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-bold fs-4 text-dark mb-0">
                <i class="bi bi-check2-square text-primary me-2"></i>
                {{ isset($attendance) ? 'Edit Attendance' : 'Record Attendance' }}
            </h2>
        </div>
    </x-slot>

    <x-slot name="breadcrumbs">
        <li class="breadcrumb-item"><a class="btn-link text-decoration-none" href="{{ route('dashboard') }}">
                <i class="bi bi-house-door me-1"></i> Dashboard</a></li>
        <li class="breadcrumb-item"><a class="btn-link text-decoration-none" href="{{ route('attendances.index') }}">
                <i class="bi bi-check2-square me-1"></i> Attendance</a></li>
        <li class="breadcrumb-item active">{{ isset($attendance) ? 'Edit' : 'Create' }}</li>
    </x-slot>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form
                action="{{ isset($attendance) ? route('attendances.update', $attendance) : route('attendances.store') }}"
                method="POST" novalidate>
                @csrf
                @if (isset($attendance))
                    @method('PUT')
                @endif

                <h5 class="mb-4 border-bottom pb-2 text-primary fw-semibold">
                    <i class="bi bi-person-lines-fill me-2"></i> Basic Information
                </h5>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Class <x-star /></label>
                        <select name="class_id" class="form-select @error('class_id') is-invalid @enderror">
                            <option value="">-- Select Class --</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}"
                                    {{ old('class_id', $attendance->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                    {{ $class->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Subject <x-star /></label>
                        <select name="subject_id" class="form-select @error('subject_id') is-invalid @enderror">
                            <option value="">-- Select Subject --</option>
                        </select>
                        @error('subject_id')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Date <x-star /></label>
                        <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                            value="{{ old('date', isset($attendance) ? $attendance->date->format('Y-m-d') : now()->format('Y-m-d')) }}">
                        @error('date')
                            <x-error-msg>{{ $message }}</x-error-msg>
                        @enderror
                    </div>

                    {{-- Student Attendance List --}}
                    <div class="col-12 mt-4" id="students-attendance-list" style="display:none;">
                        <h5 class="mb-3">Students Attendance</h5>
                        <div id="students-attendance-container"></div>
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <button type="submit" class="btn btn-primary px-4">
                        {{ isset($attendance) ? 'Update Attendance' : 'Record Attendance' }}
                    </button>
                    <x-back-button :href="route('attendances.index')" class="btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> {{ __('Back') }}
                    </x-back-button>
                </div>
            </form>
        </div>
    </div>

    {{-- Pass PHP attendance JSON to JS --}}
    <script>
        var savedAttendance = {!! $attendanceDataJson ?? '{}' !!};
    </script>

    <x-slot name="script">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                $(function() {
                    var $classSelect = $('select[name="class_id"]');
                    var $subjectSelect = $('select[name="subject_id"]');
                    var $studentsList = $('#students-attendance-container');
                    var $studentsSection = $('#students-attendance-list');

                    function loadSubjects(classId, selectedSubjectId = null) {
                        if (!classId) {
                            $subjectSelect.html('<option value="">-- Select Subject --</option>');
                            $studentsSection.hide();
                            $studentsList.empty();
                            return;
                        }
                        var url = '{{ url('attendances/subjects-by-class') }}/' + classId;
                        $.getJSON(url, function(subjects) {
                            var options = '<option value="">-- Select Subject --</option>';
                            subjects.forEach(function(subject) {
                                options += '<option value="' + subject.id + '">' + subject
                                    .name + '</option>';
                            });
                            $subjectSelect.html(options);
                            if (selectedSubjectId) {
                                $subjectSelect.val(selectedSubjectId).trigger('change');
                            } else {
                                $studentsSection.hide();
                                $studentsList.empty();
                            }
                        });
                    }

                    function loadStudents(classId, subjectId) {
                        if (!classId || !subjectId) {
                            $studentsSection.hide();
                            $studentsList.empty();
                            return;
                        }

                        var url = '{{ url('attendances/students-by-class-subject') }}/' + classId + '/' +
                            subjectId;
                        $.getJSON(url, function(students) {
                            if (students.length == 0) {
                                $studentsList.html(
                                    '<p>No students found for this class and subject.</p>');
                            } else {
                                var html =
                                    '<table class="table table-bordered"><thead><tr><th>Name</th><th>Attendance</th></tr></thead><tbody>';

                                students.forEach(function(student) {
                                    var studentId = student.id;
                                    var attendanceValue = savedAttendance ? savedAttendance[
                                        studentId] : null;

                                    html += '<tr>';
                                    html += '<td>' + (student.user?.name || 'Student') +
                                        '</td>';
                                    html += '<td>';

                                    html += '<div class="form-check form-check-inline">';
                                    html +=
                                        '<input class="form-check-input" type="radio" name="attendance[' +
                                        studentId + ']" id="present_' + studentId +
                                        '" value="present" ' + (attendanceValue === 'present' ?
                                            'checked' : '') + ' required>';
                                    html +=
                                        '<label class="form-check-label" for="present_' +
                                        studentId + '">Present</label>';
                                    html += '</div>';

                                    html += '<div class="form-check form-check-inline">';
                                    html +=
                                        '<input class="form-check-input" type="radio" name="attendance[' +
                                        studentId + ']" id="absent_' + studentId +
                                        '" value="absent" ' + (attendanceValue === 'absent' ?
                                            'checked' : '') + '>';
                                    html +=
                                        '<label class="form-check-label" for="absent_' +
                                        studentId + '">Absent</label>';
                                    html += '</div>';

                                    html += '</td>';
                                    html += '</tr>';
                                });

                                html += '</tbody></table>';
                                $studentsList.html(html);
                            }
                            $studentsSection.show();
                        });
                    }

                    // On class change, load subjects
                    $classSelect.change(function() {
                        var classId = $(this).val();
                        loadSubjects(classId);
                    });

                    // On subject change, load students
                    $subjectSelect.change(function() {
                        var classId = $classSelect.val();
                        var subjectId = $(this).val();
                        loadStudents(classId, subjectId);
                    });

                    // On page load: if editing existing attendance, load subjects and students
                    @if (isset($attendance))
                        loadSubjects(
                            '{{ old('class_id', $attendance->class_id) }}',
                            '{{ old('subject_id', $attendance->subject_id) }}'
                        );
                    @endif
                });
            });
        </script>
    </x-slot>
</x-app-layout>
