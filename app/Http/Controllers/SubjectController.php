<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\Subject;
use App\Models\Department;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SubjectController extends Controller implements HasMiddleware
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
     * Define permissions for routes.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view subjects', only: ['index', 'show']),
            new Middleware('permission:create subjects', only: ['create', 'store']),
            new Middleware('permission:edit subjects', only: ['edit', 'update']),
            new Middleware('permission:delete subjects', only: ['destroy']),
        ];
    }
    /**
     * Display a paginated list of subjects with search capability.
     *
     * Searchable fields include:
     *  - name
     *  - code
     *  - department (by department_id)
     *  - type
     *  - is_active
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Subject::with('department');

        $subjects = $this->searchService->search(
            $query,
            [
                'name',
                'code',
                'department' => ['relationship' => true, 'request_key' => 'department_id', 'field' => 'department_id'],
                'type',
                'is_active',
            ],
            $request
        )->orderBy('name')->paginate(10);

        $departments = Department::orderBy('name')->get();

        return view('subjects.index', compact('subjects', 'departments'));
    }
    /**
     * Show the form for creating a new subject.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $departments = Department::orderBy('name')->get();
        return view('subjects.form', compact('departments'));
    }
    /**
     * Store a newly created subject in storage.
     *
     * @param  \App\Http\Requests\SubjectRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(SubjectRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            Subject::create($request->validated());
            DB::commit();
            return redirect()->route('subjects.index')->with('success', 'Subject created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('subjects.index')->with('error', 'Creation failed: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified subject.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\View\View
     */
    public function show(Subject $subject)
    {
        $subject->load('department');
        return view('subjects.show', compact('subject'));
    }
    /**
     * Show the form for editing the specified subject.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\View\View
     */
    public function edit(Subject $subject)
    {
        $departments = Department::orderBy('name')->get();
        return view('subjects.form', compact('subject', 'departments'));
    }
    /**
     * Update the specified subject in storage.
     *
     * @param  \App\Http\Requests\SubjectRequest  $request
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(SubjectRequest $request, Subject $subject): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $subject->update($request->validated());
            DB::commit();
            return redirect()->route('subjects.index')->with('success', 'Subject updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('subjects.index')->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified subject from storage.
     *
     * @param  \App\Models\Subject  $subject
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Subject $subject): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $subject->delete();
            DB::commit();
            return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('subjects.index')->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
}
