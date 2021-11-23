<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Post;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;

use App\Policies\PostPolicy;
use App\Policies\UserPolicy;
use App\Policies\RolePolicy;
use App\Policies\PermissionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
       Post::class => PostPolicy::class,
       User::class => UserPolicy::class,
       Role::class => RolePolicy::class,
       Permission::class => PermissionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //post
        Gate::define('post-list',[PostPolicy::class,'view']);
        Gate::define('post-create',[PostPolicy::class,'create']);
        Gate::define('post-edit',[PostPolicy::class,'update']);
        Gate::define('post-delete',[PostPolicy::class,'delete']);
        Gate::define('post-delete',[PostPolicy::class,'restore']);
        //user
        Gate::define('user-list',[UserPolicy::class,'view']);
        Gate::define('user-create',[UserPolicy::class,'create']);
        Gate::define('user-edit',[UserPolicy::class,'update']);
        Gate::define('user-delete',[UserPolicy::class,'delete']);
        Gate::define('user-delete',[UserPolicy::class,'restore']);
        //role
        Gate::define('role-list',[RolePolicy::class,'view']);
        Gate::define('role-create',[RolePolicy::class,'create']);
        Gate::define('role-edit',[RolePolicy::class,'update']);
        Gate::define('role-delete',[RolePolicy::class,'delete']);
        Gate::define('role-delete',[RolePolicy::class,'restore']);
        //permission
        Gate::define('permission-list',[PermissionPolicy::class,'view']);
        Gate::define('permission-create',[PermissionPolicy::class,'create']);
        Gate::define('permission-edit',[PermissionPolicy::class,'update']);
        Gate::define('permission-delete',[PermissionPolicy::class,'delete']);
        Gate::define('permission-delete',[PermissionPolicy::class,'restore']);
    }
}
