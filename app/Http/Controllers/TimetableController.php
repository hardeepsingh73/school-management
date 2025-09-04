<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimetableRequest;
use App\Models\Timetable;
use App\Models\SchoolClass;
use App\Models\Teacher;
use App\Models\Subject;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class TimetableController extends Controller implements HasMiddleware
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
            new Middleware('permission:view timetables', only: ['index', 'show']),
            new Middleware('permission:create timetables', only: ['create', 'store']),
            new Middleware('permission:edit timetables', only: ['edit', 'update']),
            new Middleware('permission:delete timetables', only: ['destroy']),
        ];
    }
    /**
     * Display a paginated list of timetable entries with optional filtering.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Timetable::with(['schoolClass', 'teacher', 'subject']);

        $timetables = $this->searchService->search(
            $query,
            [
                'teacher' => ['relationship' => true, 'request_key' => 'teacher_id', 'field' => 'teacher_id'],
                'subjects' => ['relationship' => true, 'request_key' => 'subject_id', 'field' => 'subject_id'],
                'schoolClass' => ['relationship' => true, 'request_key' => 'class_id', 'field' => 'class_id'],
                'day_of_week',
            ],
            $request
        )->orderBy('day_of_week')->orderBy('start_time')->paginate(10);

        $classes = SchoolClass::orderBy('name')->get();
        $teachers = Teacher::select('teachers.*')->join('users', 'teachers.user_id', '=', 'users.id')->orderBy('users.name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('timetables.index', compact('timetables', 'classes', 'teachers', 'subjects'));
    }
    /**
     * Show the form for creating a new timetable entry.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $classes = SchoolClass::orderBy('name')->get();
        $teachers = Teacher::select('teachers.*')->join('users', 'teachers.user_id', '=', 'users.id')->orderBy('users.name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('timetables.form', compact('classes', 'teachers', 'subjects'));
    }
    /**
     * Store a newly created timetable entry in storage.
     *
     * @param  \App\Http\Requests\TimetableRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(TimetableRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            Timetable::create($request->validated());

            DB::commit();
            return redirect()->route('timetables.index')->with('success', 'Timetable entry created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('timetables.index')->with('error', 'Creation failed: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified timetable entry.
     *
     * @param  \App\Models\Timetable  $timetable
     * @return \Illuminate\View\View
     */
    public function show(Timetable $timetable)
    {
        $timetable->load(['schoolClass', 'teacher', 'subject']);
        return view('timetables.show', compact('timetable'));
    }
    /**
     * Show the form for editing the specified timetable entry.
     *
     * @param  \App\Models\Timetable  $timetable
     * @return \Illuminate\View\View
     */
    public function edit(Timetable $timetable)
    {
        $classes = SchoolClass::orderBy('name')->get();
        $teachers = Teacher::select('teachers.*')->join('users', 'teachers.user_id', '=', 'users.id')->orderBy('users.name')->get();
        $subjects = Subject::orderBy('name')->get();

        return view('timetables.form', compact('timetable', 'classes', 'teachers', 'subjects'));
    }
    /**
     * Update the specified timetable entry in storage.
     *
     * @param  \App\Http\Requests\TimetableRequest  $request
     * @param  \App\Models\Timetable  $timetable
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TimetableRequest $request, Timetable $timetable): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $timetable->update($request->validated());

            DB::commit();
            return redirect()->route('timetables.index')->with('success', 'Timetable entry updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('timetables.index')->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified timetable entry from storage.
     *
     * @param  \App\Models\Timetable  $timetable
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Timetable $timetable): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $timetable->delete();

            DB::commit();
            return redirect()->route('timetables.index')->with('success', 'Timetable entry deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('timetables.index')->with('error', 'Deletion failed: ' . $e->getMessage());
        }
    }
    /**
     * Return timetable entries as JSON for calendar display.
     *
     * Accepts optional 'start' and 'end' query parameters to filter entries.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function timetables(Request $request)
    {
        $start = \Carbon\Carbon::parse($request->json('start'));
        $end = \Carbon\Carbon::parse($request->json('end'));

        $timetables = Timetable::where(function ($query) use ($start, $end) {
            $query->where(function ($q) use ($start, $end) {
                $q->whereNull('effective_from')->orWhere('effective_from', '<=', $end);
            })->where(function ($q) use ($start, $end) {
                $q->whereNull('effective_until')->orWhere('effective_until', '>=', $start);
            });
        })->get();

        $events = collect();

        foreach ($timetables as $tt) {
            $current = $start->copy();

            $diff = ($tt->day_of_week - $current->dayOfWeek + 7) % 7;
            $current->addDays($diff);
            while ($current->lessThanOrEqualTo($end)) {
                if (
                    (is_null($tt->effective_from) || $current->greaterThanOrEqualTo(\Carbon\Carbon::parse($tt->effective_from)))
                    &&
                    (is_null($tt->effective_until) || $current->lessThanOrEqualTo(\Carbon\Carbon::parse($tt->effective_until)))
                ) {
                    $eventStart = $current->copy()->setTimeFrom($tt->start_time);
                    $eventEnd = $current->copy()->setTimeFrom($tt->end_time);

                    $events->push([
                        'id' => $tt->id . '-' . $current->format('Ymd'),
                        'title' => $tt->subject->name . ' (' . $tt->schoolClass->full_name . ')',
                        'start' => $eventStart->toIso8601String(),
                        'end' => $eventEnd->toIso8601String(),
                        'room' => $tt->room,
                        'schedule_type' => $tt->schedule_type_label,
                        'description' => "Room: {$tt->room}\nType: {$tt->schedule_type_label}\nDuration: {$tt->duration} minutes",
                    ]);
                }
                $current->addWeek();
            }
        }

        return response()->json($events->values());
    }
}
