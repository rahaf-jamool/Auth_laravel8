<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use App\Models\User;

class Permission extends Model
{
    use HasFactory;

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_permissions','permission_id', 'role_id', 'id','id');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'users_permissions', 'user_id', 'permission_id','id','id');
    }
}
