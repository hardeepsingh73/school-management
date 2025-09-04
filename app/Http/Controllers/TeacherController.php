<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Department;
use App\Services\SearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TeacherController extends Controller implements HasMiddleware
{
    /**
     * The SearchService for applying query filters and searching teachers.
     *
     * @var \App\Services\SearchService
     */
    protected SearchService $searchService;
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
     * Define permissions for routes.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view teachers', only: ['index', 'show']),
            new Middleware('permission:create teachers', only: ['create', 'store']),
            new Middleware('permission:edit teachers', only: ['edit', 'update']),
            new Middleware('permission:delete teachers', only: ['destroy']),
        ];
    }
    /**
     * Display a paginated list of teachers with optional filtering.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $departments = Department::orderBy('name')->get();

        $query = Teacher::with(['user', 'department']);

        $teachers = $this->searchService->search(
            $query,
            [
                'user' => ['relationship' => true, 'request_key' => 'name', 'field' => 'name'],
                'user' => ['relationship' => true, 'request_key' => 'email', 'field' => 'email'],
                'department' => ['relationship' => true, 'request_key' => 'department_id', 'field' => 'id'],
            ],
            $request
        )->latest()->paginate(10);

        return view('teachers.index', compact('teachers', 'departments'));
    }
    /**     
     * Show the form for creating a new teacher.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('teachers.form', compact('departments'));
    }
    /**
     * Store a newly created teacher along with associated user account.
     *
     * @param \App\Http\Requests\TeacherRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TeacherRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status,
                'password' => Hash::make($request->password),
                'dob' => $request->dob,
                'gender' => $request->gender,
                'address' => $request->address,
                'blood_group' => $request->blood_group,
                'phone' => $request->phone,
            ]);

            if (!$user) {
                throw new \Exception('Failed to create user account');
            }

            // Create teacher
            $teacher = Teacher::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'designation' => $request->designation,
                'additional_information' => $request->additional_information ? json_decode($request->additional_information, true) : null,
            ]);

            if (!$teacher) {
                throw new \Exception('Failed to create teacher record');
            }

            // Assign role if using roles, example:
            if (!$user->assignRole('teacher')) {
                throw new \Exception('Failed to assign teacher role');
            }

            DB::commit();

            return redirect()->route('teachers.index')->with('success', 'Teacher created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('teachers.index')->with('error', 'Teacher creation failed: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified teacher along with associated user and department.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\View\View
     */
    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'department']);
        return view('teachers.show', compact('teacher'));
    }
    /**
     * Show the form for editing the specified teacher.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\View\View
     */
    public function edit(Teacher $teacher)
    {
        $departments = Department::orderBy('name')->get();
        return view('teachers.form', compact('teacher', 'departments'));
    }
    /**
     * Update the specified teacher and associated user account.
     *
     * @param  \App\Http\Requests\TeacherRequest  $request
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        DB::beginTransaction();

        try {
            // Update user
            $userUpdated = $teacher->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status,
                'dob' => $request->dob,
                'gender' => $request->gender,
                'address' => $request->address,
                'phone' => $request->phone,
                'blood_group' => $request->blood_group,
            ]);

            if (!$userUpdated) {
                throw new \Exception('Failed to update user account');
            }

            // Update password if provided
            if ($request->filled('password')) {
                $passwordUpdated = $teacher->user->update([
                    'password' => Hash::make($request->password),
                ]);

                if (!$passwordUpdated) {
                    throw new \Exception('Failed to update password');
                }
            }
            // Update teacher
            $teacherUpdated = $teacher->update([
                'department_id' => $request->department_id,
                'designation' => $request->designation,
                'additional_information' => $request->additional_information ? json_decode($request->additional_information, true) : null,
            ]);

            if (!$teacherUpdated) {
                throw new \Exception('Failed to update teacher record');
            }

            DB::commit();

            return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('teachers.index')->with('error', 'Teacher update failed: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified teacher and associated user account from storage.
     *
     * @param  \App\Models\Teacher  $teacher
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Teacher $teacher): RedirectResponse
    {
        $this->authorize('delete', $teacher);

        DB::beginTransaction();

        try {
            $userDeleted = $teacher->user->delete();
            $teacherDeleted = $teacher->delete();

            if (!$userDeleted || !$teacherDeleted) {
                throw new \Exception('Failed to delete teacher records');
            }

            DB::commit();
            return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('teachers.index')->with('error', 'Failed to delete teacher: ' . $e->getMessage());
        }
    }
}
