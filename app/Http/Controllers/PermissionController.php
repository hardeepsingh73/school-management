<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view permissions', only: ['index']),
            new Middleware('permission:edit permissions', only: ['edit']),
            new Middleware('permission:create permissions', only: ['create']),
            new Middleware('permission:delete permissions', only: ['destroy']),
        ];
    }

    //list page
    public function index()
    {

        $permissions = Permission::orderBy('created_at', 'DESC')->paginate(10);
        return view('permissions.index', [
            'permissions' => $permissions
        ]);
    }
    //create page
    public function create()
    {
        return view('permissions.form');
    }
    //add permission
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3'
        ]);

        if ($validator->passes()) {
            Permission::create(['name' => $request->name]);
            return redirect()->route('permissions.index')->with('success', 'Permission added successfully.');
        } else {
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
        }
    }
    //update permission
    public function update($id, Request $request)
    {

        $permission = Permission::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:permissions,name,' . $id . ',id'
        ]);

        if ($validator->passes()) {
            $permission->name = $request->name;
            $permission->save();
            return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
        } else {
            return redirect()->route('permissions.edit',  ['permission' => $permission])->withInput()->withErrors($validator);
        }
    }
    //edit page
    public function edit(Permission $permission)
    {
        return view('permissions.form', [
            'permission' => $permission
        ]);
    }

    //delete permission
    public function destroy(Permission $permission): JsonResponse
    {
        if (Gate::denies('delete', $permission)) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized action',
            ], 403);
        }

        $permission->delete();

        return response()->json([
            'status' => true,
            'message' => 'Permission deleted successfully.',
        ]);
    }
}
