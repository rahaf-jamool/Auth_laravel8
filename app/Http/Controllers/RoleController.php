<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Role;
use App\Models\User;
use App\Traits\GlobalTrait;
use Illuminate\Support\Facades\Gate;
class RoleController extends Controller
{ 
    use GlobalTrait;

    public function __construct(Role $role,User $user){
        $this->role = $role;
        $this->user = $user;
        $this->middleware('can:role-list')->only('getAllRoles,getByIdRole');
        $this->middleware('can:role-create')->only('createRole');
        $this->middleware('can:role-edit')->only('updateRole');
        $this->middleware('can:role-delete')->only('deleteRole');
    }

    public function getAllRoles(User $user)
    {
        try {
            Gate::authorize('role-list',$user);
            $role_id = Role::all();
            $roles= Role::find($role_id);
            $roles = Role::with('permissions')->get();
            if (count($roles) > 0) {
                return response([
                    'Roles' => $roles,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'Roles' => $roles,
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'Roles doesnt exist yet'
                ], 401);
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Roles doesnt exist yet'
            ], 400);
        }
    }
    public function getByIdRole($id,User $user)
    {
        try {
            Gate::authorize('role-list',$user);
            $role = Role::find($id);
            if (isset($role)) {
                return response([
                    'role' => $role,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'Role' => $role,
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'This Role not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Roles doesnt exist yet'
            ], 400);
        }
    }
    public function createRole(Request $request,User $user)
    {
        try {
            Gate::authorize('role-create',$user);
            $validator = Validator::make($request->all(),[
                'name' => 'required|string|unique:roles',
                'permissions' => 'required'
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }

            $role_id = Role::create(array_merge(
                $validator->validated(),
            ));
            $id=$role_id->id;

            if($request->has('permissions')){
               $role= Role::find($id);
                $role->permissions()->syncWithoutDetaching($request->get('permissions'));
            }
            return response([
                'Role' => $role,
                'status' => true,
                'stateNum' => 200,
                'message' => 'done'
            ], 200);
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Roles doesnt exist yet'
            ], 400);
        }
    }
    public function updateRole($id,Request $request,User $user)
    {
        try {
            Gate::authorize('role-edit',$user);
            $role = Role::where('id', '=', $id)->first();
            if (isset($role)) {
                $validator = Validator::make($request->all(),[
                    'name' => 'required|string|unique:roles',
                    'permissions' => 'required'
                ]);
    
                if($validator->fails()){
                    return response()->json($validator->errors(),422);
                }
                $role->update($request->all());
                if($request->has('permissions')){
                   $role= Role::find($id);
                    $role->permissions()->syncWithoutDetaching($request->get('permissions'));
                }
                return response([
                    'Role' => $role,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'Role' => $role,
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'This Role not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Roles doesnt exist yet'
            ], 400);
        }
    }
    public function deleteRole(User $user){
        Gate::authorize('role-delete',$user);
    }
}
