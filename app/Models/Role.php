<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Permission;


class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'name'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class,'roles_users','user_id', 'role_id','id','id');
        
    }
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'roles_permissions','role_id', 'permission_id','id','id');
    
    }
}
