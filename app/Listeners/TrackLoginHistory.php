<?php

namespace App\Listeners;

use App\Helpers\Settings;
use App\Models\LoginHistory;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;

class TrackLoginHistory
{
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create the event listener.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        if (!Settings::get('login_history', true)) {
            return;
        }
        LoginHistory::create([
            'user_id'    => $event->user->id,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'login_at'   => now(),
        ]);
    }
}
