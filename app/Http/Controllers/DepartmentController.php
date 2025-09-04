<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use App\Models\Teacher;
use App\Services\SearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DepartmentController extends Controller implements HasMiddleware
{
    /**
     * SearchService instance.
     *
     * @var \App\Services\SearchService
     */
    protected $searchService;

    /**
     * Inject dependencies.
     */
    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Define route-specific permissions.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view departments', only: ['index', 'show']),
            new Middleware('permission:create departments', only: ['create', 'store']),
            new Middleware('permission:edit departments', only: ['edit', 'update']),
            new Middleware('permission:delete departments', only: ['destroy']),
        ];
    }

    /**
     * Display a paginated list of departments with filtering.
     */
    public function index(Request $request)
    {
        $headTeachers = Teacher::select('teachers.*')->join('users', 'teachers.user_id', '=', 'users.id')->orderBy('users.name')->get();

        $query = Department::with('headTeacher');

        $departments = $this->searchService->search(
            $query,
            ['name'],
            $request
        )->latest()->paginate(10);

        return view('departments.index', compact('departments', 'headTeachers'));
    }

    /**
     * Show the form for creating a new department.
     */
    public function create()
    {
        $headTeachers = Teacher::select('teachers.*')->join('users', 'teachers.user_id', '=', 'users.id')->orderBy('users.name')->get();
        return view('departments.form', compact('headTeachers'));
    }

    /**
     * Store a newly created department in storage.
     */
    public function store(DepartmentRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $department = Department::create([
                'name' => $request->name,
                'head_teacher_id' => $request->head_teacher_id,
            ]);

            if (!$department) {
                throw new \Exception('Failed to create department');
            }

            DB::commit();

            return redirect()->route('departments.index')->with('success', 'Department created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('departments.index')->with('error', 'Department creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing a department.
     */
    public function edit(Department $department)
    {
        $headTeachers = Teacher::select('teachers.*')->join('users', 'teachers.user_id', '=', 'users.id')->orderBy('users.name')->get();
        return view('departments.form', compact('department', 'headTeachers'));
    }

    /**
     * Update the specified department in storage.
     */
    public function update(DepartmentRequest $request, Department $department): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $updated = $department->update([
                'name' => $request->name,
                'head_teacher_id' => $request->head_teacher_id,
            ]);

            if (!$updated) {
                throw new \Exception('Failed to update department');
            }

            DB::commit();

            return redirect()->route('departments.index')->with('success', 'Department updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('departments.index')->with('error', 'Department update failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified department from storage.
     */
    public function destroy(Department $department): RedirectResponse
    {
        $this->authorize('delete', $department);

        DB::beginTransaction();

        try {
            $deleted = $department->delete();

            if (!$deleted) {
                throw new \Exception('Failed to delete department');
            }

            DB::commit();
            return redirect()->route('departments.index')->with('success', 'Department deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('departments.index')->with('error', 'Failed to delete department: ' . $e->getMessage());
        }
    }
}
