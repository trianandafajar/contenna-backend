<?php

namespace App\Http\Controllers\Resources;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:roles-list', ['only' => ['index', 'show', 'getAllAjax']]);
        $this->middleware('permission:roles-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:roles-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:roles-delete', ['only' => ['destroy']]);
    }
    
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = 10;
        $permissions = Permission::all();
        $query = Role::orderBy('id');
    
        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%");
        }
    
        $roles = $query->paginate($perPage);
    
        return view('pages.resources.roles.index', compact('roles', 'permissions'));
    }


    public function create()
    {
        $permissions = Permission::all();
        
        return view('pages.resources.roles.create', compact('permissions'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        $permissions = is_array($request->permission) ? $request->permission : [$request->permission];
        $webPermissions = Permission::where('guard_name', 'web')
            ->whereIn('id', $permissions)
            ->get();
        $role->givePermissionTo($webPermissions);

        $message = [
            "status" => $role ? "success" : "failed",
            "message" => $role ? "Data created successfully" : "Data failed to create!"
        ];

        if ($request->has('save_and_add_other')) {

            return redirect()->route('resources.roles.create')->with("message", $message);
        } else {
            return redirect()->route('resources.roles.index')->with("message", $message);
        }
    }


    public function show($id)
    {
        $role = Role::findOrFail($id);
        $rolePermissions = Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where("role_has_permissions.role_id", $id)
            ->get();
        $userCount = $role->users()->count();
        $users = $role->users;

        return view('pages.resources.roles.view', compact('role', 'rolePermissions', 'userCount', 'users'));
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $rolePermissions = Permission::join('role_has_permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
            ->where("role_has_permissions.role_id", $id)
            ->get();
        $permissions = Permission::all();

        return view('pages.resources.roles.edit', compact('role','rolePermissions','permissions'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permission' => 'required',
        ]);

        $requestData = $request->all();

        $role = Role::findOrFail($id);
        $role->update($requestData);
        $role->syncPermissions($request->permission);

        $message = [
            "status" => $role ? "success" : "failed",
            "message" => $role ? "Data updated successfully" : "Data failed to update!"
        ];

        if ($request->has('update_and_continue_editing')) {
            return Redirect::back()->with("message", $message);
        } else {
            return Redirect::route("resources.roles.index")->with("message", $message);
        }
    }

    public function destroy($id)
    {

        $role = Role::destroy($id);

        $message = [
            "status" => $role ? "success" : "failed",
            "message" => $role ? "Data deleted successfully" : "Data failed to delete!"
        ];

        return Redirect::route('resources.roles.index')->with('message', $message);
    }

    public function getAllAjax() {
        $roles = Role::all();

        return [
            "roles" => $roles
        ];
    }
}
