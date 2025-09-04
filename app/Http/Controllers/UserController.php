<?php

namespace App\Http\Controllers;

use App\Helpers\RoleDataHelper;
use App\Helpers\Settings;
use App\Http\Requests\UserRequest;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Services\FileService;
use App\Services\SearchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class UserController extends Controller implements HasMiddleware
{
    /**
     * Services for searching and file handling.
     *
     * @var \App\Services\SearchService
     * @var \App\Services\FileService
     */
    protected $searchService;
    protected $fileService;
    /**
     * Inject dependencies into the controller.
     *
     * @param  \App\Services\SearchService  $searchService
     * @param  \App\Services\FileService  $fileService
     */
    public function __construct(SearchService $searchService, FileService $fileService)
    {
        $this->searchService = $searchService;
        $this->fileService = $fileService;
    }
    /**
     * Define middleware permissions for specific controller actions.
     *
     * Note: The 'edit users' permission is checked via Gate/Policy in edit/update.
     *
     * @return \Illuminate\Routing\Controllers\Middleware[]
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view users', only: ['index']),
            new Middleware('permission:create users', only: ['create', 'store']),
            new Middleware('permission:edit users', only: ['edit', 'update']),
            new Middleware('permission:delete users', only: ['destroy']),
        ];
    }

    /**
     * Display a paginated list of users with optional filtering by role/name/email.
     *
     * Uses RoleDataHelper to enforce role-based visibility restrictions.
     * Applies search filters via SearchService.
     *
     * @param App\Http\Requests\UserRequest $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('name', 'ASC')->get();
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
            $request
        )->latest()->paginate(10);

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user.
     *
     * Roles that the current user should not see (e.g., super admins) are excluded.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $excludedRoles = [];

        if (!auth()->user()->hasRole(Settings::get('role_super_admin', 'superadmin'))) {
            $excludedRoles[] = Settings::get('role_super_admin', 'superadmin');
        }

        $roles = Role::whereNotIn('name', $excludedRoles)->orderBy('name')->get();

        return view('users.form', compact('roles'));
    }
    /**
     * Store a newly created user in storage.
     *
     * Validate input, create the user, assign role, handle profile image, and send welcome email.
     *
     * @param  App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request): RedirectResponse
    {
        DB::beginTransaction();

        try {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'gender' => $request->gender,
                'dob' => $request->dob,
                'address' => $request->address,
                'blood_group' => $request->blood_group,
                'phone' => $request->phone,
                'status' => $request->status ?? 1,
            ];

            $user = User::create($userData);

            if (!$user) {
                throw new \Exception('Failed to create user account');
            }

            if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
                /** @var UploadedFile $profileImageFile */
                $profileImageFile = $request->file('profile_image');
                $fileRecord = $this->fileService->attachFile($user, $profileImageFile, File::TYPE_IMAGE);
                $user->profile_image_id = $fileRecord->id;
                $user->save();
            }

            if ($request->filled('roles')) {
                $assigned = $user->assignRole($request->roles);
                if (!$assigned) {
                    throw new \Exception('Failed to assign user role');
                }
            }

            try {
                Mail::to($user->email)->send(new WelcomeMail($user));
            } catch (\Throwable $e) {
                // Rollback or handle failure
                DB::rollBack();
                throw new \Exception("Welcome email error: " . $e->getMessage(), 0, $e);
            }
            DB::commit();

            return redirect()->route('users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.index')->with('error', 'User creation failed: ' . $e->getMessage());
        }
    }
    /**
     * Show the form for editing a user.
     *
     * Authorization enforced via UserPolicy::manage to allow only owners or authorized users.
     * Excludes super admin role from roles list unless current user is super admin.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        $this->authorize('manage', $user);

        $excludedRoles = [];

        if (!auth()->user()->hasRole(Settings::get('role_super_admin', 'superadmin'))) {
            $excludedRoles[] = Settings::get('role_super_admin', 'superadmin');
        }

        $roles = Role::whereNotIn('name', $excludedRoles)->orderBy('name')->get();

        return view('users.form', compact('user', 'roles'));
    }
    /**
     * Update the specified user in storage.
     *
     * Validate input, update user details, handle profile image, and sync roles.
     *
     * @param  App\Http\Requests\UserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('manage', $user);

        DB::beginTransaction();

        try {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->dob = $request->dob;
            $user->address = $request->address;
            $user->blood_group = $request->blood_group;
            $user->phone = $request->phone;
            $user->status = $request->status ?? 1;

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            if ($request->hasFile('profile_image') && $request->file('profile_image')->isValid()) {
                if ($user->profile_image_id) {
                    $this->fileService->deleteFile($user->profile_image_id);
                }
                $profileImageFile = $request->file('profile_image');
                $fileRecord = $this->fileService->attachFile($user, $profileImageFile, File::TYPE_IMAGE);
                $user->profile_image_id = $fileRecord->id;
            }

            if (!$user->save()) {
                throw new \Exception('Failed to update user account');
            }

            if ($request->filled('roles')) {
                $synced = $user->syncRoles([$request->roles]);
                if (!$synced) {
                    throw new \Exception('Failed to update user roles');
                }
            }

            DB::commit();

            return redirect()->route('users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.index')->with('error', 'User update failed: ' . $e->getMessage());
        }
    }
    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->authorize('manage', $user);

        if (Gate::denies('delete', $user)) {
            return redirect()->route('users.index')->with('error', 'Unauthorized action.');
        }

        DB::beginTransaction();

        try {
            if (!$user->delete()) {
                throw new \Exception('Failed to delete user');
            }

            DB::commit();

            return redirect()->route('users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('users.index')->with('error', 'User deletion failed: ' . $e->getMessage());
        }
    }
}
