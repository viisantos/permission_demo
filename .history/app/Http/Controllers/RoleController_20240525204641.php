<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function __construct(){
        $this->middleware('permission:delete role', ['only' => ['destroy']]);
        $this->middleware('permission:update role', ['only' => ['update','edit']]);
        $this->middleware('permission:view role',   ['only' => ['index','show']]);
        $this->middleware('permission:create role', ['only' => ['create','store']]);
        $this->middleware('permission:manage permissions', [])
    }
    public function index(){
        $roles = Role::get();
        return view('role-permission.role.index', compact('roles'));
    }
    public function create(){
        return view('role-permission.role.create');
    }
    public function edit(Role $role){
        return view('role-permission.role.edit', compact('role'));
    }
    public function store(Request $request){
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ]);

        Role::create([
            'name' => $request->name
        ]);

        return redirect('roles')->with('status', 'role created successfully');
    }
    public function update(Request $request, Role $role){
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ]);

        //dd($request->name);

        //DB::beginTransaction();
        try {
            $role->update([
                'name' => $request->name
            ]);
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            //DB::rollBack();
            return back()->withError('An error occurred while updating developer.');
        }

        return redirect('roles')->with('status', 'role updated successfully');
    }
    public function show(){}
    public function destroy(Role $role){
        try{
            $role->delete();
        }catch(\Exception $exception){
            dd($exception->getMessage());
        }
        return redirect('roles')->with('status','Role deleted successfully!');

    }

    public function addPermissionToRole($roleId){
        $permissions = Permission::get();
        $role = Role::findOrFail($roleId);

        $rolePermissions = DB::table('role_has_permissions')
                             ->where('role_has_permissions.role_id', $roleId)
                             ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                             ->all();

        return view('role-permission.role.add-permissions', compact('role','permissions','rolePermissions'));
    }

    public function givePermissionToRole(Request $request, $roleId){
        $request->validate([
            'permission' => 'required'
        ]);

        $role = Role::findOrFail($roleId);
        $role->syncPermissions($request->permission);

        return redirect()->back()->with('status','Permissions added to role');
    }
}
