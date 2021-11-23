<?php

namespace App\Traits;

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
}
