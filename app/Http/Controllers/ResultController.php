<?php

namespace App\Http\Controllers;

use App\Http\Requests\ResultRequest;
use App\Models\Result;
use App\Models\Student;
use App\Models\Exam;
use App\Models\Exams;
use App\Models\SchoolClass;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class ResultController extends Controller implements HasMiddleware
{
    /*    
     * Service that handles reusable search/filter functionality.
     *
     * @var \App\Services\SearchService
     */
    protected $searchService;
    /**     
     *  Inject dependencies into controller.
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
            new Middleware('permission:view results', only: ['index', 'show']),
            new Middleware('permission:create results', only: ['create', 'store']),
            new Middleware('permission:edit results', only: ['edit', 'update']),
            new Middleware('permission:delete results', only: ['destroy']),
        ];
    }
    /**
     * List results with filters & pagination.
     */
    public function index(Request $request)
    {
        $query = Result::with(['student', 'exam', 'schoolClass']);

        $results = $this->searchService->search(
            $query,
            [
                'student' => ['relationship' => true, 'request_key' => 'student_id', 'field' => 'student_id'],
                'exams' => ['relationship' => true, 'request_key' => 'exam_id', 'field' => 'exam_id'],
                'class' => ['relationship' => true, 'request_key' => 'class_id', 'field' => 'class_id'],
                'grade',
                'is_published',
            ],
            $request
        )->latest()->paginate(10);

        $students = Student::select('students.*')->join('users', 'students.user_id', '=', 'users.id')->orderBy('users.name')->get();
        $exams = Exams::orderBy('exam_date')->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('results.index', compact('results', 'students', 'exams', 'classes'));
    }
    /**
     * Show the create form.
     */
    public function create()
    {
        $students = Student::select('students.*')->join('users', 'students.user_id', '=', 'users.id')->orderBy('users.name')->get();
        $exams = Exams::orderBy('exam_date')->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('results.form', compact('students', 'exams', 'classes'));
    }
    /**
     * Store a new result.
     */
    public function store(ResultRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            // $data['created_by'] = auth()->id();

            if (!empty($data['is_published']) && $data['is_published']) {
                $data['published_at'] = Carbon::now();
            }

            $result = Result::create($data);

            if (!$result) {
                throw new \Exception('Failed to create result record');
            }

            DB::commit();
            return redirect()->route('results.index')->with('success', 'Result created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('results.index')->with('error', 'Creation failed: ' . $e->getMessage());
        }
    }
    /**
     * Display a specific result.
     */
    public function show(Result $result)
    {
        $result->load(['student', 'exam', 'schoolClass']);
        return view('results.show', compact('result'));
    }
    /**
     * Show the edit form.
     */
    public function edit(Result $result)
    {
        $students = Student::select('students.*')->join('users', 'students.user_id', '=', 'users.id')->orderBy('users.name')->get();
        $exams = Exams::orderBy('exam_date')->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('results.form', compact('result', 'students', 'exams', 'classes'));
    }
    /**
     * Update a result.
     */
    public function update(ResultRequest $request, Result $result): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            // $data['updated_by'] = auth()->id();

            if (!empty($data['is_published']) && $data['is_published'] && !$result->published_at) {
                $data['published_at'] = Carbon::now();
            }

            $updated = $result->update($data);

            if (!$updated) {
                throw new \Exception('Failed to update result record');
            }

            DB::commit();
            return redirect()->route('results.index')->with('success', 'Result updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('results.index')->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
    /**
     * Delete a result.
     */
    public function destroy(Result $result): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $deleted = $result->delete();
            if (!$deleted) {
                throw new \Exception('Failed to delete result record');
            }
            DB::commit();
            return redirect()->route('results.index')->with('success', 'Result deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('results.index')->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
}
