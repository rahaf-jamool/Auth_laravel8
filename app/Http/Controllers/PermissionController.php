<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    public function __construct(Permission $permission){
        $this->permission = $permission;
        // $this->middleware('auth');
    }
    public function getAllPermission()
    {
        try {
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
    public function getByIdPermission($id)
    {
        try {
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
    public function createPermission(Request $request)
    {
        try {
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
    public function updatePermission($id,Request $request)
    {
        try {
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
    public function deletePermission($id)
    {
        try {
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
