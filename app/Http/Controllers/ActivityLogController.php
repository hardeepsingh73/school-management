<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['subject', 'causer'])
            ->latest();

        if ($request->has('user')) {
            $query->where('causer_id', $request->user);
        }

        if ($request->has('type')) {
            $query->where('subject_type', $request->type);
        }

        if ($request->has('event')) {
            $query->where('event', $request->event);
        }

        $logs = $query->paginate(10);

        return view('activity-logs.index', compact('logs'));
    }

    public function show(ActivityLog $activityLog)
    {
        return view('activity-logs.show', compact('activityLog'));
    }
    public function export(Request $request)
    {
        $logs = ActivityLog::query()
            ->when($request->has('user'), fn($q) => $q->where('causer_id', $request->user))
            ->when($request->has('type'), fn($q) => $q->where('subject_type', $request->type))
            ->when($request->has('event'), fn($q) => $q->where('event', $request->event))
            ->latest()
            ->get();

        $fileName = 'activity_logs_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID',
                'Event',
                'Description',
                'Subject Type',
                'Subject ID',
                'Causer Type',
                'Causer ID',
                'IP Address',
                'User Agent',
                'URL',
                'Method',
                'Created At'
            ]);

            // Add data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->event,
                    $log->description,
                    $log->subject_type,
                    $log->subject_id,
                    $log->causer_type,
                    $log->causer_id,
                    $log->ip_address,
                    $log->user_agent,
                    $log->url,
                    $log->method,
                    $log->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    /**
     * Clear all activity logs.
     */
    public function clear(): RedirectResponse
    {
        ActivityLog::truncate();

        return redirect()->route('activity-logs.index')->with('success', 'All activity logs have been cleared.');
    }
}
