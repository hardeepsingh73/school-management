<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller implements HasMiddleware
{
    /**
     * Define permissions-based middleware for controller actions.
     *
     * This ensures that users must have the specified permissions
     * to access each method.
     *
     * @return array<int, \Illuminate\Routing\Controllers\Middleware>
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view roles', only: ['index']),
            new Middleware('permission:edit roles', only: ['edit', 'update']),
            new Middleware('permission:create roles', only: ['create', 'store']),
            new Middleware('permission:delete roles', only: ['destroy']),
        ];
    }

    /**
     * Display a paginated listing of roles.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve roles ordered by name ascending and paginate 10 per page
        $roles = Role::orderBy('name', 'ASC')->paginate(10);

        return view('roles.index', [
            'roles' => $roles
        ]);
    }

    /**
     * Show the form for creating a new role.
     *
     * Pass all permissions to the view so they can be assigned to the new role.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Retrieve all permissions to show as assignable options
        $permissions = Permission::all();

        return view('roles.form', [
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created role in storage.
     *
     * Validate input, create the role, and assign selected permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the new role name (must be unique, at least 3 characters)
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3'
        ]);

        if ($validator->passes()) {
            // Create the new Role
            $role = Role::create(['name' => $request->name]);

            // Assign selected permissions, if any
            if (!empty($request->permissions)) {
                // Get the permission names based on the IDs
                $permissions = Permission::whereIn('id', $request->permissions)->pluck('name');
                $role->givePermissionTo($permissions);
            }

            return redirect()->route('roles.index')
                ->with('success', 'Role added successfully.');
        } else {
            // Redirect back to form with validation errors and input data
            return redirect()->route('roles.create')
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Show the form for editing the specified role.
     *
     * Pass current role's permissions and all permissions to the view.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\View\View
     */
    public function edit(Role $role)
    {
        // Get names of permissions assigned to this role
        $hasPermissions = $role->permissions->pluck('name');

        // Retrieve all permissions for selection
        $permissions = Permission::all();

        return view('roles.form', [
            'permissions'    => $permissions,
            'hasPermissions' => $hasPermissions,
            'role'           => $role,
        ]);
    }

    /**
     * Update the specified role in storage.
     *
     * Validates input, updates the role name and synchronizes permissions.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request)
    {
        // Find role or abort 404 if not found
        $role = Role::findOrFail($id);

        // Validate form input; unique rule ignores this role's own ID
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id . ',id|min:3'
        ]);

        if ($validator->passes()) {
            // Update the role's name
            $role->name = $request->name;
            $role->save();

            // Synchronize the role's permissions
            if (!empty($request->permissions)) {
                // Get the permission names based on the IDs
                $permissions = Permission::whereIn('id', $request->permissions)->pluck('name');
                $role->syncPermissions($permissions);
            } else {
                $role->syncPermissions([]);
            }

            return redirect()->route('roles.index')
                ->with('success', 'Role updated successfully.');
        } else {
            // Redirect back to edit form with validation errors and old input
            return redirect()->route('roles.edit', ['role' => $role])
                ->withInput()
                ->withErrors($validator);
        }
    }

    /**
     * Remove the specified role from storage.
     *
     * Checks authorization via Gate before deleting.
     *
     * @param  \Spatie\Permission\Models\Role  $role
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        // Authorization check: deny if user can't delete role
        if (Gate::denies('delete', $role)) {
            return redirect()
                ->route('roles.index')
                ->with('error', 'Unauthorized action.');
        }

        // Delete the role
        $role->delete();

        // Redirect with success message
        return redirect()
            ->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
