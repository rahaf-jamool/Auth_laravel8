<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
trait GlobalTrait
{
    public function author($perm,$user){
        $roles=$user->roles()->with('permissions')->get();
        foreach ($roles as $role){
             $permission = $role->permissions->where('name',$perm)->first();
        }
        if (isset($permission)) {
            return true;
        } else
            return false;
    }
    public function returnError($stateNum, $msg)
    {
        return response()->json([
            'status' => false,
            'stateNum' => $stateNum,
            'msg' => $msg
        ]);
        // ->header('Access-Control-Allow-Origin', '*')
        //     ->header('Access-Control-Allow-Methods', '*');
    }
    public function returnSuccessMessage($msg, $stateNum )
    {
        return response()->json(
            ['status' => true,
            'stateNum' => $stateNum,
            'msg' => $msg
        ]);
        // ->header('Access-Control-Allow-Origin', '*')
        //     ->header('Access-Control-Allow-Methods', '*');
    }
    public function returnData( $key,$value, $msg )
    {
        return response()->json(
            [
                $key=>$value
                ,'status' => true,
                'stateNum' => '201',
                'msg' => $msg
            ]
            );
            // ->header('Access-Control-Allow-Origin', '*')
            // ->header('Access-Control-Allow-Methods', '*');
    }
    /**
     * Data Response
     * @param $data
     * @return JsonResponse
     */
    public function dataResponse($data): JsonResponse
    {
        return response()->json(['content' => $data], Response::HTTP_OK);
    }
    /**
     * Success Response
     * @param string $message
     * @param int $code
     * @return JsonResponse
     */
    public function successResponse(string $message, $code = Response::HTTP_OK)
    {
        return response()->json(['success' => $message, 'code' => $code], $code);
    }
    /**
     * Error Response
     * @param $message
     * @param int $code
     * @return JsonResponse
     *
     */
    public function errorResponse($message, $code = Response::HTTP_BAD_REQUEST)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }
}
