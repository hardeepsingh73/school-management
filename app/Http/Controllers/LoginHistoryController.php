<?php

namespace App\Http\Controllers;

use App\Models\LoginHistory;
use App\Models\User;
use App\Services\SearchService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LoginHistoryController extends Controller implements HasMiddleware
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view login history', only: ['index']),
        ];
    }

    /**
     * Display a listing of login histories.
     */
    public function index(Request $request): View
    {
        $users = User::orderBy('name')->get();

        $query = $this->searchService->search(
            LoginHistory::with('user'),
            ['ip_address', 'login_at', 'user_id' => '='],
            $request
        );

        $histories = $query->latest('login_at')->paginate(10);

        return view('login-history.index', compact('histories', 'users'));
    }
    /**
     * Get current user's login history
     */
    public function myHistory(Request $request): View
    {
        $query = $this->searchService->search(
            $request->user()->loginHistories(),
            ['ip_address', 'login_at'],
            $request
        );

        $histories = $query->latest('login_at')->paginate(10);

        return view('login-history.personal', compact('histories'));
    }
    /**
     * Clear all login history logs.
     */
    public function clear(): RedirectResponse
    {
        LoginHistory::truncate();

        return redirect()->route('login-history.index')->with('success', 'All login history logs have been cleared.');
    }
}
