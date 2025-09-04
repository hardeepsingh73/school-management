<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassSubjectRequest;
use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use App\Services\SearchService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ClassSubjectController extends Controller implements HasMiddleware
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
            new Middleware('permission:view class subject', only: ['index', 'show']),
            new Middleware('permission:create class subject', only: ['create', 'store']),
            new Middleware('permission:edit class subject', only: ['edit', 'update']),
            new Middleware('permission:delete class subject', only: ['destroy']),
        ];
    }
    /**
     * Display a paginated list of class-subject mappings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $classSubjects = ClassSubject::with(['class', 'subject', 'teacher'])->paginate(10);
        $classes  = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::select('teachers.*')->join('users', 'teachers.user_id', '=', 'users.id')->orderBy('users.name')->get();

        return view('classsubject.index', compact('classSubjects', 'classes', 'subjects', 'teachers'));
    }
    /**
     * Show the form for creating a new class-subject mapping.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $classes  = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::select('teachers.*')->join('users', 'teachers.user_id', '=', 'users.id')->orderBy('users.name')->get();

        return view('classsubject.form', compact('classes', 'subjects', 'teachers'));
    }
    /**
     * Store a newly created class-subject mapping in storage.
     *
     * @param  \App\Http\Requests\ClassSubjectRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ClassSubjectRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            ClassSubject::create($request->validated());
            DB::commit();
            return redirect()->route('class-subject.index')->with('success', 'Class subject mapping created.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('class-subject.index')->with('error', 'Creation failed: ' . $e->getMessage());
        }
    }
    /**
     * Show the form for editing an existing class-subject mapping.
     *
     * Route Model Binding automatically fetches the ClassSubject instance.
     *
     * @param  \App\Models\ClassSubject  $classSubject
     * @return \Illuminate\View\View
     */
    public function edit(ClassSubject $classSubject)
    {
        $classes  = SchoolClass::orderBy('name')->get();
        $subjects = Subject::orderBy('name')->get();
        $teachers = Teacher::select('teachers.*')->join('users', 'teachers.user_id', '=', 'users.id')->orderBy('users.name')->get();

        return view('classsubject.form', compact('classSubject', 'classes', 'subjects', 'teachers'));
    }
    /**
     * Update the specified class-subject mapping in storage.
     *
     * @param  \App\Http\Requests\ClassSubjectRequest  $request
     * @param  \App\Models\ClassSubject  $classSubject
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ClassSubjectRequest $request, ClassSubject $classSubject): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $classSubject->update($request->validated());
            DB::commit();
            return redirect()->route('class-subject.index')->with('success', 'Mapping updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('class-subject.index')->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified class-subject mapping from storage.
     *
     * @param  \App\Models\ClassSubject  $classSubject
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ClassSubject $classSubject): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $classSubject->delete();
            DB::commit();
            return redirect()->route('class-subject.index')->with('success', 'Mapping deleted.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('class-subject.index')->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
}
