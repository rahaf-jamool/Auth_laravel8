<?php

namespace App\Http\Controllers;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\UserRequest;
use App\Traits\GlobalTrait;
use Illuminate\Support\Facades\Gate;

class UsersController extends Controller
{   
    use GlobalTrait;

    public function __construct(User $user){
        $this->user = $user;
        $this->middleware('can:user-list')->only('getAllUsers,getByIdUser');
        $this->middleware('can:user-create')->only('createUser');
        $this->middleware('can:user-edit')->only('updateUser');
        $this->middleware('can:user-delete')->only('deleteUser');
    }

    public function getAllUsers(User $user)
    {
        try {
            Gate::authorize('user-list',$user);
            $user_id = User::latest()->get();
            $users= User::find($user_id);
            $users = User::with('roles')->get();
            if (count($users) > 0) {
                return $response= $this->returnData('Users',$users,'done');
            } else {
                return $response= $this->returnSuccessMessage('User','User doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function getByIdUser($id,User $user)
    {
        try {
            Gate::authorize('user-list',$user);
            $user = User::findOrFail($id);
            if (isset($user)) {
                return $response= $this->returnData('Role',$user,'done');
            } else {
                return $response= $this->returnSuccessMessage('This User not found','done');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function createUser(UserRequest $request,User $user)
    {
        try {
            Gate::authorize('user-create',$user);
            $user = User::create(array_merge(
                $request->validated(),
                ['password'=> bcrypt($request->password)]
            ));
             $id=$user->id;
            $token = JWTAuth::fromUser($user);

            if ($request->has('roles')) {
                $role = User::find($id);
                $role->roles()->syncWithoutDetaching($request->get('roles'));
            }
            
        //    if ($request->has('permissions')) {
        //        $permissions = User::find($id);
        //        $permissions->permissions()->syncWithoutDetaching($request->get('permissions'));
        //    }
           return $response= $this->returnData('User',[$token,$user],'done');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
    // check
    public function updateUser(UserRequest $request, User $user, $id)
    {
        try {
            Gate::authorize('user-edit',$user);
            $user = User::find($id);
            if (isset($user)) {

                $token = JWTAuth::fromUser($user);
                $request->validated();
                $user->update($request->all());

                if($request->has('password')){
                    $user->password = bcrypt($request->password);
                }
            
                if ($request->has('roles')) {
                    $role = User::find($user->id);
                    $role->roles()->syncWithoutDetaching($request->get('roles'));
                }
            
                // if ($request->has('permissions')) {
                //     $permissions = User::find($user->id);
                //     $permissions->permissions()->syncWithoutDetaching($request->get('permissions'));
                // }    
                return $response= $this->returnData('User',[$user,$token],'done');
            } else {
                return $response= $this->returnSuccessMessage('This User not found','done');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    
    public function deleteUser($id,User $user){
        try {
            Gate::authorize('user-delete',$user);
            $user = User::find($id);
            if (isset($user)) {
                $user->destroy();
                return $this->returnData('User', $user,'This User Is deleted Now');
            } else {
                return $response= $this->returnSuccessMessage('This User not found','done');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    } 
}

