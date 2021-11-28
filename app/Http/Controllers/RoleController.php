<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\User;
use App\Traits\GlobalTrait;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\RoleRequest;
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
                return $response= $this->returnData('Roles',$roles,'done');
            } else {
                return $response= $this->returnSuccessMessage('Role','Role doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function getByIdRole($id,User $user)
    {
        try {
            Gate::authorize('role-list',$user);
            $role = Role::find($id);
            if (isset($role)) {
                return $response= $this->returnData('Role',$role,'done');
            } else {
                return $response= $this->returnSuccessMessage('This Role not found','done');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function createRole(RoleRequest $request,User $user)
    {
        try {
            Gate::authorize('role-create',$user);
            $role_id = Role::create(array_merge(
                $request->validated(),
            ));
            $id=$role_id->id;

            if($request->has('permissions')){
               $role= Role::find($id);
                $role->permissions()->syncWithoutDetaching($request->get('permissions'));
            }
                return $response= $this->returnData('Role',$role,'done');
        }catch (\Exception $ex) {
            return $this->returnError('403', $ex->getMessage());
        }
    }
    public function updateRole($id,RoleRequest $request,User $user)
    {
        try {
            Gate::authorize('role-edit',$user);
            $role = Role::where('id', '=', $id)->first();
            if (isset($role)) {
                $request->validated();
                $role->update($request->all());
                if($request->has('permissions')){
                   $role= Role::find($id);
                    $role->permissions()->syncWithoutDetaching($request->get('permissions'));
                }
                return $response= $this->returnData('Role',$role,'done');
            } else {
                return $response= $this->returnSuccessMessage('This Role not found','done');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function deleteRole($id,User $user){
        try {
            Gate::authorize('role-delete',$user);
            $role = Role::find($id);
            if (isset($user)) {
                $role = Role::destroy($id);
                return $this->returnData('Role', $role,'This Role Is deleted Now');
            } else {
                return $response= $this->returnSuccessMessage('This Role not found','done');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
}
