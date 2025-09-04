<?php

namespace App\Http\Controllers;

use App\Http\Requests\SchoolClassRequest;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\RedirectResponse;

class SchoolClassController extends Controller implements HasMiddleware
{
    /**
     * Service for search and filtering.
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
     * Define permissions for routes.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view classes', only: ['index']),
            new Middleware('permission:create classes', only: ['create', 'store']),
            new Middleware('permission:edit classes', only: ['edit', 'update']),
            new Middleware('permission:delete classes', only: ['destroy']),
        ];
    }

    /**
     * List school classes with filters & pagination.
     */
    public function index(Request $request)
    {
        $query = SchoolClass::with(['classTeacher']);

        $classes = $this->searchService->search(
            $query,
            [
                'name',
                'section',
            ],
            $request
        )->paginate(10);

        $teachers = Teacher::select('teachers.*')->join('users', 'teachers.user_id', '=', 'users.id')->orderBy('users.name')->get();

        return view('school_classes.index', compact('classes',  'teachers'));
    }

    /**
     * Show the create form.
     */
    public function create()
    {
        $teachers = Teacher::select('teachers.*')->join('users', 'teachers.user_id', '=', 'users.id')->orderBy('users.name')->get();

        return view('school_classes.form', compact('teachers'));
    }

    /**
     * Store a new school class.
     */
    public function store(SchoolClassRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $class = SchoolClass::create($request->validated());

            if (!$class) {
                throw new \Exception('Failed to create class');
            }

            DB::commit();
            return redirect()->route('school_classes.index')->with('success', 'School Class created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('school_classes.index')->with('error', 'Creation failed: ' . $e->getMessage());
        }
    }

    /**
     * Show the edit form.
     */
    public function edit(SchoolClass $schoolClass)
    {
        $teachers = Teacher::select('teachers.*')->join('users', 'teachers.user_id', '=', 'users.id')->orderBy('users.name')->get();

        return view('school_classes.form', compact('schoolClass',  'teachers'));
    }

    /**
     * Update a school class.
     */
    public function update(SchoolClassRequest $request, SchoolClass $schoolClass): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $updated = $schoolClass->update($request->validated());

            if (!$updated) {
                throw new \Exception('Failed to update class');
            }

            DB::commit();
            return redirect()->route('school_classes.index')->with('success', 'School Class updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('school_classes.index')->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Delete a school class.
     */
    public function destroy(SchoolClass $schoolClass): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $deleted = $schoolClass->delete();

            if (!$deleted) {
                throw new \Exception('Failed to delete class');
            }

            DB::commit();
            return redirect()->route('school_classes.index')->with('success', 'School Class deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('school_classes.index')->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
}
