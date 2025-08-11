<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api'),
            new Middleware('permission:view users', only: ['index']),
            new Middleware('permission:create users', only: ['create', 'store']),
            new Middleware('permission:delete users', only: ['destroy']),
        ];
    }

    public function index(Request $request)
    {
        $users = User::latest()->paginate(10);
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => ['sometimes', 'string', Rule::exists('roles', 'name')],
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            if (!empty($validated['roles'])) {
                $user->assignRole($validated['roles']);
            }

            DB::commit();
            return response()->json(['status' => true, 'user' => $user], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // Add authorization if needed
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => ['sometimes', 'string', Rule::exists('roles', 'name')],
        ]);

        DB::beginTransaction();
        try {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            if (!empty($validated['roles'])) {
                $user->syncRoles([$validated['roles']]);
            }
            DB::commit();
            return response()->json(['status' => true, 'user' => $user]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (in_array($user->id, [1, 4])) {
            return response()->json(['status' => false, 'message' => 'This user cannot be deleted.'], 403);
        }
        $user->delete();
        return response()->json(['status' => true, 'message' => 'User deleted successfully.']);
    }
}
