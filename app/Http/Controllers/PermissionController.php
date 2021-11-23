<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\User;
use App\Traits\GlobalTrait;
use Illuminate\Support\Facades\Gate;

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
            $permission = Permission::all();
            if (count($permission) > 0) {
                return response([
                    'Permission' => $permission,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'permission' => $permission,
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'Permission not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Permission doesnt exist yet'
            ], 400);
        }
    }
    public function getByIdPermission($id,User $user)
    {
        try {
            Gate::authorize('permission-list',$user);
            $permission = Permission::find($id);
            if (isset($permission)) {
                return response([
                    'Permission' => $permission,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'Permission' => $permission,
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'This Permission not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Permission doesnt exist yet'
            ], 400);
        }
    }
    public function createPermission(Request $request,User $user)
    {
        try {
            Gate::authorize('permission-create',$user);
            $this->validate($request, [
                'name' => 'required|max:50',
            ]);

            $permission = $this->permission->create([
                'name' => $request->name
            ]);
            return response([
                'Permission' => $permission,
                'status' => true,
                'stateNum' => 200,
                'message' => 'done'
            ], 200);
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Permission doesnt exist yet'
            ], 400);
        }
    }
    public function updatePermission($id,Request $request,User $user)
    {
        try {
            Gate::authorize('permission-edit',$user);
            $permission = Permission::find($id);
            if (isset($permission)) {
                $permission = new Permission;
                $permission->name = $request->name;
                $permission->save();
                return response([
                    'Permission' => $permission,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'This Permission not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Permission doesnt exist yet'
            ], 400);
        }
    }
    public function deletePermission($id,User $user)
    {
        try {
            Gate::authorize('permission-delete',$user);
            Permission::where('id', $id)->delete();
            return response([
                'status' => true,
                'stateNum' => 200,
                'message' => 'done'
            ], 200);
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Permission doesnt exist yet'
            ], 400);
        }
    }
}
