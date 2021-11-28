<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\User;
use App\Traits\GlobalTrait;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\PermissionRequest;

class PermissionController extends Controller
{
    use GlobalTrait;

    public function __construct(Permission $permission,User $user){
        $this->permission = $permission;
        $this->user = $user;
        $this->middleware('can:permission-list')->only('getAllPermission,getByIdPermission');
        $this->middleware('can:permission-create')->only('createPermission');
        $this->middleware('can:permission-edit')->only('updatePermission');
        $this->middleware('can:permission-delete')->only('deletePermission');
    }
    public function getAllPermission(User $user)
    {
        try {
            Gate::authorize('permission-list',$user);
            $permissions = Permission::all();
            if (count($permissions) > 0) {
                return $response= $this->returnData('Permission',$permissions,'done');
            } else {
                return $response= $this->returnSuccessMessage('Permission','Permission doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function getByIdPermission($id,User $user)
    {
        try {
            Gate::authorize('permission-list',$user);
            $permission = Permission::find($id);
            if (isset($permission)) {
                return $response= $this->returnData('Permission',$permission,'done');
            } else {
                return $response= $this->returnSuccessMessage('Permission','Permission doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function createPermission(PermissionRequest $request,User $user)
    {
        try {
            Gate::authorize('permission-create',$user);

            $permission = Permission::create(array_merge(
                $request->validated(),
            ));
            return $response= $this->returnData('Permission',$permission,'done');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function updatePermission($id,PermissionRequest $request,User $user)
    {
        try {
            Gate::authorize('permission-edit',$user);
            $permission = Permission::find($id);
            if (isset($permission)) {
                $request->validated();
                $permission->update($request->all());
                return $response= $this->returnData('Permission',$permission,'done');
            } else {
                return $response= $this->returnSuccessMessage('Permission','Permission doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function deletePermission($id,User $user)
    {
        try {
            Gate::authorize('permission-delete',$user);
            $permission = Permission::find($id);
            if (isset($permission)) {
                $permission = Permission::destroy($id);
                return $this->returnData('Permission', $permission,'This Permission Is deleted Now');
            } else {
                return $response= $this->returnSuccessMessage('This Permission not found','done');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
