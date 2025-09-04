<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttendanceRequest;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Subject;
use App\Models\SchoolClass;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AttendanceController extends Controller implements HasMiddleware
{
    /**
     * Service that handles reusable search/filter functionality.
     *
     * @var \App\Services\SearchService
     */
    protected $searchService;
    /**
     * Inject dependencies into controller.
     *
     * @param  \App\Services\SearchService  $searchService
     */
    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }
    /**
     * Define route middleware permissions for this controller's actions.
     *
     * Using Laravel 10+ `HasMiddleware` + `Middleware` class approach.
     * Ensures that only users with the correct permissions can take
     * specific actions.
     *
     * @return array<int, \Illuminate\Routing\Controllers\Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view attendances', only: ['index', 'show']),
            new Middleware('permission:create attendances', only: ['create', 'store']),
            new Middleware('permission:edit attendances', only: ['edit', 'update']),
            new Middleware('permission:delete attendances', only: ['destroy']),
        ];
    }
    /**
     * Display a paginated list of attendance records with optional filters.
     *
     * Filters can be applied via query parameters:
     * - subject_id : Filter by subject
     * - class_id   : Filter by class
     * - date       : Filter by date
     * - status     : Filter by attendance status (present, absent, etc.)
     * - session    : Filter by session (morning, afternoon, etc.)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Attendance::with(['subject', 'schoolClass', 'recordedBy']);

        $attendances = $this->searchService->search(
            $query,
            [
                'subject'    => ['relationship' => true, 'request_key' => 'subject_id', 'field' => 'subject_id'],
                'schoolClass'    => ['relationship' => true, 'request_key' => 'class_id', 'field' => 'class_id'],
                'date',
                'status',
                'session',
            ],
            $request
        )->orderBy('date', 'desc')->paginate(10);


        $subjects = Subject::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('attendances.index', compact('attendances',  'subjects', 'classes'));
    }
    /**
     * Show the form for creating a new attendance record.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();

        return view('attendances.form', compact('classes'));
    }
    /**
     * Store a newly created attendance record in storage.
     *
     * @param  \App\Http\Requests\AttendanceRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AttendanceRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            // Encode attendance array as JSON string before saving
            $data['attendance'] = json_encode($request->input('attendance'));

            $data['recorded_by'] = auth()->id();

            Attendance::create($data);

            DB::commit();

            return redirect()->route('attendances.index')->with('success', 'Attendance record created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('attendances.index')->with('error', 'Creation failed: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified attendance record.
     *
     * Route Model Binding automatically fetches the Attendance instance.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\View\View
     */
    public function show(Attendance $attendance)
    {
        $attendance->load(['subject', 'schoolClass', 'recordedBy']);
        $attendanceData = is_string($attendance->attendance) ? json_decode($attendance->attendance, true) : $attendance->attendance;
        // Fetch student details for display
        $studentIds = array_keys($attendanceData);
        $students = \App\Models\Student::with('user')->whereIn('id', $studentIds)->get()->keyBy('id');
        return view('attendances.show', compact('attendance', 'attendanceData', 'students'));
    }
    /**
     * Show the form for editing the specified attendance record.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\View\View
     */
    public function edit(Attendance $attendance)
    {
        $subjects = Subject::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();
        $attendanceDataJson = $attendance->attendance ?? '{}';
        return view('attendances.form', compact('attendance',  'subjects', 'classes', 'attendanceDataJson'));
    }
    /**
     * Update the specified attendance record in storage.
     *
     * @param  \App\Http\Requests\AttendanceRequest  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AttendanceRequest $request, Attendance $attendance): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['attendance'] = json_encode($request->input('attendance'));

            $data['recorded_by'] = auth()->id();

            $attendance->update($data);

            DB::commit();
            return redirect()->route('attendances.index')->with('success', 'Attendance record updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('attendances.index')->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified attendance record from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Attendance $attendance): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $attendance->delete();

            DB::commit();
            return redirect()->route('attendances.index')->with('success', 'Attendance deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('attendances.index')->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }

    public function getSubjectsByClass($classId)
    {
        $subjects = Subject::whereHas('classes', function ($q) use ($classId) {
            $q->where('id', $classId);
        })->get(['id', 'name']);

        return response()->json($subjects);
    }

    public function getStudentsByClassSubject($classId, $subjectId)
    {
        $students = Student::with('user')->where('school_class_id', $classId)->whereHas('schoolClass.subjects', function ($q) use ($subjectId) {
            $q->where('subjects.id', $subjectId);
        })->get();

        return response()->json($students);
    }
}
