<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class RoleController extends Controller  implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view roles', only: ['index']),
            new Middleware('permission:edit roles', only: ['edit']),
            new Middleware('permission:create roles', only: ['create']),
            new Middleware('permission:delete roles', only: ['destroy']),
        ];
    }

    //list roles
    public function index()
    {
        $roles = Role::orderBy('name', 'ASC')->paginate(10);
        return view('roles.index', [
            'roles' => $roles
        ]);
    }

    //create roles
    public function create()
    {

        $permissions = Permission::get();
        return view('roles.form', [
            'permissions' => $permissions
        ]);
    }

    //store roles in db
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3'
        ]);

        // dd($request->permission);
        if ($validator->passes()) {
            $role = Role::create(['name' => $request->name]);
            if (!empty($request->permissions)) {
                foreach ($request->permissions as $name) {
                    $role->givePermissionTo($name);
                }
            }
            return redirect()->route('roles.index')->with('success', 'Role added successfully.');
        } else {
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
        }
    }

    //edit role
    public function edit(Role $role)
    {
        $hasPermissions = $role->permissions->pluck('name');
        $permissions = Permission::get();

        return view('roles.form', [
            'permissions' => $permissions,
            'hasPermissions' => $hasPermissions,
            'role' => $role
        ]);
    }

    //update role
    public function update($id, Request $request)
    {

        $role = Role::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id . ',id|min:3'
        ]);

        if ($validator->passes()) {
            $role->name = $request->name;
            $role->save();

            if (!empty($request->permissions)) {
                $permissionNames = Permission::whereIn('id', $request->permissions)->pluck('name');
                $role->syncPermissions($permissionNames);
            } else {
                $role->syncPermissions([]);
            }
            return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
        } else {
            return redirect()->route('roles.edit',  ['role' => $role])->withInput()->withErrors($validator);
        }
    }

    //delete role
    public function destroy(Role $role): JsonResponse
    {
        if (Gate::denies('delete', $role)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action',
            ], 403);
        }

        $role->delete();

        return response()->json([
            'status' => true,
            'message' => 'Role deleted successfully.',
        ]);
    }
}
