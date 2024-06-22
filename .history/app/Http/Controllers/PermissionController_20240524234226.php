<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{
    
    public function index(){
        $permissions = Permission::get();
        return view('role-permission.permission.index', compact('permissions'));
    }
    public function create(){
        return view('role-permission.permission.create');
    }
    public function edit(Permission $permission){
        return view('role-permission.permission.edit', compact('permission'));
    }
    public function store(Request $request){
        $request->validate([
            'name' => [
                'required',
                'string',
                'unique:permissions,name'
            ]
        ]);

        Permission::create([
            'name' => $request->name
        ]);

        return redirect('permissions')->with('status', 'permission created successfully');
    }
    public function update(Request $request, Permission $permission){
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
            $permission->update([
                'name' => $request->name
            ]);
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            //DB::rollBack();
            return back()->withError('An error occurred while updating developer.');
        }

        return redirect('permissions')->with('status', 'permission updated successfully');
    }
    public function show(){}
    public function destroy(Permission $permission){
        try{
            $permission->delete();
        }catch(\Exception $exception){
            dd($exception->getMessage());
        }
        return redirect('permissions')->with('status','Permission deleted successfully!');

    }


}
