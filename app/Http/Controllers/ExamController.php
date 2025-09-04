<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExamRequest;
use App\Models\Department;
use App\Models\Exams;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ExamController extends Controller implements HasMiddleware
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
            new Middleware('permission:view exams', only: ['index', 'show']),
            new Middleware('permission:create exams', only: ['create', 'store']),
            new Middleware('permission:edit exams', only: ['edit', 'update']),
            new Middleware('permission:delete exams', only: ['destroy']),
        ];
    }
    /**
     * List exams with filters & pagination.
     */
    public function index(Request $request)
    {
        $query = Exams::with(['subject', 'class']);

        $exams = $this->searchService->search(
            $query,
            [
                'subject'    => ['relationship' => true, 'request_key' => 'subject_id', 'field' => 'subject_id'],
                'schoolclass'    => ['relationship' => true, 'request_key' => 'class_id', 'field' => 'class_id'],
                'type',
            ],
            $request
        )->paginate(10);

        $subjects = Subject::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('exams.index', compact('exams',  'subjects', 'classes'));
    }
    /**
     * Show the create form.
     */
    public function create()
    {
        $subjects = Subject::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('exams.form', compact('subjects', 'classes'));
    }
    /**
     * Store a new exam with transaction handling.
     */
    public function store(ExamRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            Exams::create($request->validated());
            DB::commit();
            return redirect()->route('exams.index')->with('success', 'Exam created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('exams.index')->with('error', 'Creation failed: ' . $e->getMessage());
        }
    }
    /**
     * Show details of a specific exam.
     */
    public function show(Exams $exam)
    {
        $exam->load(['subject', 'class']);
        return view('exams.show', compact('exam'));
    }
    /**
     * Show the edit form for a specific exam.
     */
    public function edit(Exams $exam)
    {
        $subjects = Subject::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('exams.form', compact('exam',  'subjects', 'classes'));
    }
    /**
     * Update an existing exam with transaction handling.
     */
    public function update(ExamRequest $request, Exams $exam): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $exam->update($request->validated());
            DB::commit();
            return redirect()->route('exams.index')->with('success', 'Exam updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('exams.index')->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
    /**
     * Delete an exam with transaction handling.
     */
    public function destroy(Exams $exam): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $exam->delete();
            DB::commit();
            return redirect()->route('exams.index')->with('success', 'Exam deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('exams.index')->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
}
