<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShadowLoginController extends Controller
{
    public function loginAsUser(User $user)
    {
        // Save original superadmin ID
        session()->put('shadow_admin_id', Auth::id());

        // Login as target user
        Auth::login($user);

        return redirect('/dashboard')->with('status', 'Logged in as user: ' . $user->name);
    }

    public function revertBack()
    {
        if (!session()->has('shadow_admin_id')) {
            abort(403, 'No shadow session found');
        }

        $adminId = session()->pull('shadow_admin_id');
        $admin = User::find($adminId);

        if ($admin) {
            Auth::login($admin);
            return redirect('/dashboard')->with('status', 'Reverted to Super Admin');
        }

        abort(403, 'Original user not found');
    }
}
