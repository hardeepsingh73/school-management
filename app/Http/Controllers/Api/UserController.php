<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Apply permissions middleware for various actions.
     *
     * Using the constructor ensures permissions are checked automatically
     * for each action without manually adding checks inside methods.
     */
    public function __construct()
    {
        $this->middleware('permission:view users')->only('index');
        $this->middleware('permission:create users')->only(['store']);
        $this->middleware('permission:delete users')->only('destroy');
    }

    /**
     * Display a paginated list of users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        // Retrieve users with latest first, paginate 10 per page.
        $users = User::latest()->paginate(10);

        return response()->json($users);
    }

    /**
     * Store a newly created user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles'    => ['sometimes', 'string', Rule::exists('roles', 'name')],
        ]);

        DB::beginTransaction();
        try {
            // 📝 Create user with hashed password
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Assign role if provided
            if (!empty($validated['roles'])) {
                $user->assignRole($validated['roles']);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'user'   => $user
            ], 201); // 201 = Created
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'error'  => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user.
     *
     * Uses Route Model Binding for cleaner code.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Update the specified user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        // ✅ Validate request
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'roles'    => ['sometimes', 'string', Rule::exists('roles', 'name')],
        ]);

        DB::beginTransaction();
        try {
            // Update user info
            $user->name  = $validated['name'];
            $user->email = $validated['email'];

            // Update password only if provided
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            // Update roles if provided
            if (!empty($validated['roles'])) {
                $user->syncRoles([$validated['roles']]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'user'   => $user
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'error'  => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user from the database.
     *
     * User deletion is restricted for certain protected accounts.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        // Prevent deletion of critical/system users
        if (in_array($user->id, [1, 4])) {
            return response()->json([
                'status'  => false,
                'message' => 'This user cannot be deleted.'
            ], 403); // Forbidden
        }

        $user->delete();

        return response()->json([
            'status'  => true,
            'message' => 'User deleted successfully.'
        ]);
    }
}
