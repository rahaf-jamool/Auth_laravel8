<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\AuthRequest;
use Illuminate\support\Facades\Auth;
use App\Traits\GlobalTrait;
// use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request;
// use Illuminate\support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use GlobalTrait;
    public function __construct(){
        $this->middleware('auth:api',['except' => ['login','register']]);
    }
    protected function guard(){
        return Auth::guard();
    }
    public function register(AuthRequest $request)
    {
        try {
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
            return $this->returnData('User', [$token,$user],'done');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
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
        }catch(\Throwable $ex){
                if ($ex instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                    return $this->returnError('401', 'TokenInvalidException');
                }else if ($ex instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                    return $this->returnError('401', 'TokenInvalidException');
                } else if ( $ex instanceof \Tymon\JWTAuth\Exceptions\JWTException) {
                    return $this->returnError('401', $ex->getMessage());
                }
        }
    }
    protected function respondWithToken($token){
        return response()->json([
            'token' => $token,
            'token_type' => 'bearer',
            'token_validity' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    public function logout(Request $request)
    {
        // return 'hi';
        $token = $request -> token;
        if($token){
            try {
                auth('api')->setToken($token)->invalidate(); //logout
            }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return  $this -> returnError('','some thing went wrongs'.$e->getMessage());
            }
            return $this->returnSuccessMessage('Logged out successfully','200');
        }else{
            $this -> returnError('','some thing went wrongs');
        }
    }

    public function profile(){
        // return response()->json($this->guard()->user());
        return response()->json(auth('api')->user());
    }

    public function refresh(){
        return $this->respondWithToken($this->guard()->refresh());
    }
}
