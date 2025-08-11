<?php

namespace App\Http\Controllers;

use App\Helpers\RoleDataHelper;
use App\Helpers\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UserController extends Controller implements HasMiddleware
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view users', only: ['index']),
            new Middleware('permission:create users', only: ['create', 'store']), // Enable create permission
            new Middleware('permission:delete users', only: ['destroy']), // Enable delete permission
            // The 'edit users' permission is handled by Gate/Policy in edit/update methods
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('name', 'ASC')->get();

        // Use the RoleDataHelper to get authorized users
        $query = RoleDataHelper::users(true);
        $users = $this->searchService->search(
            $query,
            [
                'name',
                'email',
                'roles' => [
                    'relationship' => true,
                    'field' => 'id',
                    'operator' => '=',
                    'request_key' => 'role'
                ]
            ],
            request()
        );

        $users = $users->latest()->paginate(10);
        return view('users.index', [
            'users' => $users,
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $excludedRoles = [
            // Settings::get('role_client', 'client'),
        ];

        if (!auth()->user()->hasRole(Settings::get('role_super_admin', 'superadmin'))) {
            $excludedRoles[] = Settings::get('role_super_admin', 'superadmin');
        }

        $roles = Role::whereNotIn('name', $excludedRoles)
            ->orderBy('name', 'ASC')
            ->get();

        return view('users.form', [
            'roles' => $roles,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $selectedRole = $request->input('roles');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => ['sometimes', 'string', Rule::exists('roles', 'name')],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->route('users.create')->withInput()->withErrors($validator);
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->assignRole($selectedRole);

            DB::commit();

            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('users.create')->withInput()->withErrors(['error' => 'Something went wrong. ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // No implementation provided, typically used for showing a single user's details
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Policy check: Only allow if editing self OR has 'edit users' permission
        // Ensure your UserPolicy has a 'manage' method that correctly handles this.
        $this->authorize('manage', $user);
        $excludedRoles = [
            // Settings::get('role_client', 'client'),
        ];

        if (!auth()->user()->hasRole(Settings::get('role_super_admin', 'superadmin'))) {
            $excludedRoles[] = Settings::get('role_super_admin', 'superadmin');
        }

        $roles = Role::whereNotIn('name', $excludedRoles)
            ->orderBy('name', 'ASC')
            ->get();

        // When editing, get the current user's role name (assuming single role)
        $currentRoleName = $user->getRoleNames()->first(); // Spatie returns Collection of role names

        return view('users.form', [
            'user' => $user,
            'roles' => $roles,
            'currentRoleName' => $currentRoleName, // Pass the current role name for radio button selection
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('manage', $user);

        $selectedRole = $request->input('roles');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => ['required', 'string', Rule::exists('roles', 'name')],
        ];


        $validated = $request->validate($rules);

        DB::beginTransaction();

        try {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            $user->syncRoles([$selectedRole]);

            DB::commit();

            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('users.edit', $user->id)->withInput()->withErrors(['error' => 'Something went wrong. ' . $e->getMessage()]);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        // Preventing deletion of specific IDs (e.g., system accounts)
        if (in_array($user->id, [1, 4])) { // Consider making these IDs configurable or using roles
            return response()->json([
                'status' => false,
                'message' => 'This user cannot be deleted.',
            ], 403);
        }

        // Policy check: This will check the 'delete' method in your UserPolicy
        if (Gate::denies('delete', $user)) {
            // You might also use $this->authorize('delete', $user); which throws an exception
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action',
            ], 403);
        }
        $this->authorize('manage', $user);

        $user->delete();

        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully.',
        ]);
    }
}
