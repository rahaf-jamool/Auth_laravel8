<?php

namespace App\Traits;

// use Illuminate\Http\Response;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use App\Models\User;
// ->with('Permission')
trait GlobalTrait
{
    public function author($perm,$user){
        $u = $user->first();
        $roles=$u->permissions()->get();
        foreach ($roles as $role){
             $permission = $role->where('name',$perm)->first();
        }
        if (isset($permission)) {
            return true;
        } else
            return false;
    }
}
