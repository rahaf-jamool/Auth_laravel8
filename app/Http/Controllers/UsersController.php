<?php

namespace App\Http\Controllers;

use App\Models\User;
// use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\support\Facades\Auth;

// use App\Traits\GlobalTrait;
// use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{   
    // use GlobalTrait;

    public function __construct(User $user){
        $this->user = $user;
        // $this->middleware('can:user-list')->only('getAllUsers,getByIdUser');
        // $this->middleware('can:user-create')->only('createUser');
        // $this->middleware('can:user-edit')->only('updateUser');
        // $this->middleware('can:user-delete')->only('deleteUser');
    }

    public function getAllUsers()
    {
        try {
            // Gate::authorize('user-list',$user);
            $user_id = User::latest()->get();
            $users= User::find($user_id);
            $users = User::with('roles')->get();
            if (count($users) > 0) {
                return response()->json([
                    'Users' => $users,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'Users' => $users,
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'This User not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return response([
                'status' => false,
                'stateNum' => 401,
                'message' => 'Error! Users doesnt exist yet'
            ], 401);
        }
    }
    public function getByIdUser($id)
    {
        try {
            // Gate::authorize('user-list',$user);
            $user = User::findOrFail($id);
            if (isset($user)) {
                return response([
                    'User' => $user,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'This User not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 401,
                'message' => 'Error! Users doesnt exist yet'
            ], 401);
        }
    }
    public function createUser(Request $request)
    {
        try {
            // Gate::authorize('user-create',$user);
            $validator = Validator::make($request->all(),[
                'fullName' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'alpha_num|bail|string|confirmed|required|min:8',
                'roles' => 'required',
                'permissions' => 'required'
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }

            $user = User::create(array_merge(
                $validator->validated(),
                ['password'=> bcrypt($request->password)]
            ));
             $id=$user->id;
            $token = JWTAuth::fromUser($user);

            if ($request->has('roles')) {
                $role = User::find($id);
                $role->roles()->syncWithoutDetaching($request->get('roles'));
            }
            
           if ($request->has('permissions')) {
               $permissions = User::find($id);
               $permissions->permissions()->syncWithoutDetaching($request->get('permissions'));
           }
            return response([
                'User' => [$token,$user],
                'status' => true,
                'stateNum' => 200,
                'message' => 'done'
            ], 200);
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return response([
                'status' => false,
                'stateNum' => 401,
                'message' => 'Error! Users doesnt exist yet'
            ], 401);
        }
    }
    
    // check
    public function updateUser(Request $request, User $user, $id)
    {
        try {
            // Gate::authorize('user-edit',$user);
            $user = User::find($id);
            if (isset($user)) {
                $validator = Validator::make($request->all(),[
                    'fullName' => 'required|string',
                    'email' => 'required|email|unique:users',
                    'password' => 'alpha_num|bail|string|confirmed|required|min:8',
                    'roles' => 'required',
                    'permissions' => 'required'
                ]);

                if($validator->fails()){
                    return response()->json($validator->errors(),422);
                }
                $token = JWTAuth::fromUser($user);

                $user->update($request->all());
                // $user_id=$user->id;

                if($request->has('password')){
                    $user->password = bcrypt($request->password);
                }
            
                if ($request->has('roles')) {
                    $role = User::find($user->id);
                    $role->roles()->syncWithoutDetaching($request->get('roles'));
                }
            
                if ($request->has('permissions')) {
                    $permissions = User::find($user->id);
                    $permissions->permissions()->syncWithoutDetaching($request->get('permissions'));
                }    
                return response([
                    'User' => [$user,$token],
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'message' => 'This User not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return response([
                'status' => false,
                'stateNum' => 401,
                'message' => 'Error! Users doesnt exist yet'
            ], 401);
        }
    }
    
    public function deleteUser($id){
        try {
            // Gate::authorize('user-delete',$user);
            $user = User::find($id);
            if (isset($user)) {
                $user = User::destroy($id);
                return response([
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ],200);
            } else {
                return response([
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'This User not found'
                ],401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 401,
                'message' => 'Error! Users doesnt exist yet'
            ],401);
        }
    } 
    public function index(User $user){
        $roles= $user->with('permissions')->get();
        // $permission = $roles->with('permissions')->get();
        return $roles;
    }
}

