<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Models\Student;
use App\Models\User;
use App\Models\SchoolClass;
use App\Services\SearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller implements HasMiddleware
{
    /**
     * The SearchService for applying query filters and searching students.
     *
     * @var \App\Services\SearchService
     */
    protected $searchService;

    /**
     * Inject dependencies.
     *
     * @param \App\Services\SearchService $searchService
     */
    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Define middleware permissions for specific controller actions.
     *
     * @return \Illuminate\Routing\Controllers\Middleware[]
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view students', only: ['index', 'show']),
            new Middleware('permission:create students', only: ['create', 'store']),
            new Middleware('permission:edit students', only: ['edit', 'update']),
            new Middleware('permission:delete students', only: ['destroy']),
        ];
    }

    /**
     * Display a paginated list of students with optional filtering.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Fetch all classes for filter dropdown
        $classes = SchoolClass::orderBy('name', 'ASC')->get();

        // Base query with relationships
        $query = Student::with(['user', 'schoolClass']);

        // Apply search/filter
        $students = $this->searchService->search(
            $query,
            [
                'user' => ['relationship' => true, 'request_key' => 'name', 'field' => 'name'],
                'user' => ['relationship' => true, 'request_key' => 'email', 'field' => 'email'],
                'schoolClass' => ['relationship' => true, 'request_key' => 'class', 'field' => 'id']
            ],
            $request
        )->latest()->paginate(10);

        return view('students.index', compact('students', 'classes'));
    }

    /**
     * Show the form for creating a new student.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $classes = SchoolClass::orderBy('name', 'ASC')->get();

        return view('students.form', compact('classes'));
    }

    /**
     * Store a newly created student in the database.
     *
     * @param \App\Http\Requests\StudentRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StudentRequest $request)
    {
        DB::beginTransaction();

        try {
            // Create the User
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'gender' => $request->gender,
                'dob' => $request->dob,
                'address' => $request->address,
                'blood_group' => $request->blood_group,
                'phone' => $request->phone,
                'status' => $request->status ?? 1,
            ]);

            // Create Student with user_id and school_class_id plus additional_information
            $student = Student::create([
                'user_id' => $user->id,
                'school_class_id' => $request->school_class_id,
                'additional_information' => $request->additional_information ? json_decode($request->additional_information, true) : null,
            ]);

            // Assign student role if applicable (assuming assignRole method exists)
            $user->assignRole('student');

            DB::commit();

            return redirect()->route('students.index')->with('success', 'Student created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create student: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified student.
     *
     * @param \App\Models\Student $student
     * @return \Illuminate\View\View
     */
    public function show(Student $student)
    {
        $student->load(['user', 'schoolClass']);
        return view('students.show', compact('student'));
    }

    /**
     * Show the form for editing the specified student.
     *
     * @param \App\Models\Student $student
     * @return \Illuminate\View\View
     */
    public function edit(Student $student)
    {
        $classes = SchoolClass::orderBy('name', 'ASC')->get();
        return view('students.form', compact('student', 'classes'));
    }

    /**
     * Update the specified student in storage.
     *
     * @param \App\Http\Requests\StudentRequest $request
     * @param \App\Models\Student $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StudentRequest $request, Student $student)
    {
        DB::beginTransaction();

        try {
            // Update User info
            $user = $student->user;
            $user->name = $request->name;
            $user->email = $request->email;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->gender = $request->gender;
            $user->dob = $request->dob;
            $user->address = $request->address;
            $user->blood_group = $request->blood_group;
            $user->phone = $request->phone;
            $user->status = $request->status;

            $user->save();

            // Update Student fields
            $student->school_class_id = $request->school_class_id;
            $student->additional_information = $request->additional_information ? json_decode($request->additional_information, true) : null;
            $student->save();

            DB::commit();

            return redirect()->route('students.index')->with('success', 'Student updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to update student: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified student from storage.
     *
     * @param \App\Models\Student $student
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Student $student): RedirectResponse
    {
        $this->authorize('delete', $student);

        DB::beginTransaction();

        try {
            $userDeleted = $student->user->delete();
            $studentDeleted = $student->delete();

            if (!$userDeleted || !$studentDeleted) {
                throw new \Exception('Failed to delete student records');
            }

            DB::commit();
            return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('students.index')->with('error', 'Failed to delete student: ' . $e->getMessage());
        }
    }
}
