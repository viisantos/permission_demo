<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('permission:delete user', ['only' => ['destroy']]);
        $this->middleware('permission:update user', ['only' => ['update','edit']]);
        $this->middleware('permission:view user',   ['only' => ['index','show']]);
        $this->middleware('permission:create user', ['only' => ['create','store']]);
    }
    public function index(){
        $users = User::get();
        return view('role-permission.user.index', compact('users'));
    }

    public function create(){
        $roles = Role::pluck('name','name')->all();
        return view('role-permission.user.create', compact('roles'));
    }
    public function store(Request $request){
      /*
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|max:20',
            'roles' => 'required'
        ]);*/


        $user = User::create([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        $user->syncRoles($request->roles);


        return redirect('/users')->with('status','User created successfully with roles');

    }

    public function edit(User $user){
        $roles = Role::pluck('name','name')->all();
        $userRoles = $user->roles->pluck('name','name')->all();
        return view('role-permission.user.edit', compact('user','roles','userRoles'));
    }

    public function update(Request $request, User $user){

        $data = [
            'name'  => $request->name,
            'email' => $request->email
        ];

        if(!empty($request->password)){
            $data += [
                'password' => Hash::make($request->password),
            ];
        }

        $user->update($data);
        $user->syncRoles($request->roles);

        return redirect('/users')->with('status','User updated successfully');
    }

    public function destroy(User $user){
        //$user = User::findOrFail($userId);
        $user->delete();
        return redirect('/users')->with('status','User deleted successfully');
    }
}
