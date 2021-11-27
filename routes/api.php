<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Models\User;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::prefix('auth')->middleware([AdminCheck::class])->group(function () {
Route::get('/index','App\Http\Controllers\usersController@index');

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
    'prefix' => 'auth'
],function($router){
    //authentecation
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::get('profile', 'AuthController@profile');
    Route::post('refresh', 'AuthController@refresh');
    // users 
    Route::get('user/getAll', 'UsersController@getAllUsers')->can('user-list', User::class);
    Route::get('user/getById/{id}', 'UsersController@getByIdUser')->can('user-list', User::class);
    Route::post('user/create', 'UsersController@createUser')->can('user-create', User::class);
    Route::put('user/update/{id}', 'UsersController@updateUser')->can('user-edit', User::class);
    Route::delete('user/delete/{id}','UsersControlle@deleteUser')->can('user-delete', User::class);
    // roles
    Route::get('roles/getAll','RoleController@getAllRoles');
    Route::get('role/getById/{id}', 'RoleController@getByIdRole');
    Route::post('role/create', 'RoleController@createRole')->can('role-create', User::class);
    Route::put('role/update/{id}', 'RoleController@updateRole')->can('role-edit', User::class);
    Route::delete('role/delete/{id}', 'RoleController@deleteRole')->can('role-delete', User::class);
    // permission
    Route::get('permission/getAll', 'PermissionController@getAllPermission')->can('permission-list', User::class);
    Route::get('permission/getById/{id}', 'PermissionController@getByIdPermission')->can('permission-list', User::class);   
    Route::post('permission/create', 'PermissionController@createPermission')->can('permission-create', User::class);
    Route::put('permission/update/{id}', 'PermissionController@updatePermission')->can('permission-edit', User::class);
    Route::delete('permission/delete/{id}', 'PermissionController@deletePermission')->can('permission-delete', User::class);
    // posts
    Route::get('posts/getAll', 'PostController@getAllPosts')->can('post-list', User::class);
    Route::get('post/getById/{id}', 'PostController@getByIdPost')->can('post-list', User::class);
    Route::post('post/create', 'PostController@createPost')->can('post-create', User::class);
    Route::put('post/update/{id}', 'PostController@updatePost')->can('post-edit', User::class);
    Route::delete('post/delete/{id}', 'PostController@deletePost')->can('post-delete', User::class);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
