<?php

namespace App\Http\Controllers;

use App\Models\ErrorLog;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ErrorLogController extends Controller implements HasMiddleware
{
    protected SearchService $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view error logs', only: ['index']),
            new Middleware('permission:clear error logs', only: ['clear']),
        ];
    }

    /**
     * Display a listing of error logs.
     */
    public function index(Request $request): View
    {
        $query = $this->searchService->search(
            ErrorLog::query(),
            ['error_type', 'message'],
            $request
        );

        $errorLogs = $query->latest()->paginate(10);

        return view('error_logs.index', compact('errorLogs'));
    }

    /**
     * Clear all error logs.
     */
    public function clear(): RedirectResponse
    {
        ErrorLog::truncate();

        return redirect()->route('error-logs.index')->with('success', 'All error logs have been cleared.');
    }
}
