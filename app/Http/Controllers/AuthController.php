<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api',['except' => ['login','register']]);
    }
    protected function guard(){
        return Auth::guard();
    }
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'fullName' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'alpha_num|bail|string|confirmed|required|min:8',
                // 'role' => 'required',
                // 'permissions' => 'required'
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(),422);
            }

            $user = User::create(array_merge(
                $validator->validated(),
                ['password'=> bcrypt($request->password)]
            ));
            return response([
                    'User' => $user,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error Register'
            ], 400);
        }
    }
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'bail|required|string|min:8'
            ]);
            if($validator->fails()){
                return response()->json($validator->errors(),400);
            }

            $token_validity = 24 * 60;

            $this->guard()->factory()->setTTL($token_validity);

            if(!$token = $this->guard()->attempt($validator->validated())){
                return response()->json(['error' => 'Unauthorized'],401);
            }
            return $this->respondWithToken($token);
        } catch (\Exception $ex) {
            return $ex->getMessage();
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error Login'
            ], 400);
        }
    }
    
    protected function respondWithToken($token){
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'token_validity' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response([
            'message' => 'Successfully logged out'
        ]);
    }

    public function profile(){
        return response()->json($this->guard()->user());
    }

    public function refresh(){
        return $this->respondWithToken($this->guard()->refresh());
    }
}
