<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

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
    Route::get('user/getAll', 'UsersController@getAllUsers');
    Route::get('user/getById/{id}', 'UsersController@getByIdUser');
    Route::post('user/create', 'UsersController@createUser');
    Route::put('user/update/{id}', 'UsersController@updateUser');
    Route::delete('user/delete/{id}','UsersControlle@deleteUser');
    // roles
    Route::get('roles/getAll','RoleController@getAllRoles');
    Route::get('role/getById/{id}', 'RoleController@getByIdRole');
    Route::post('role/create', 'RoleController@createRole');
    Route::put('role/update/{id}', 'RoleController@updateRole');
    Route::delete('role/delete/{id}', 'RoleController@deleteRole');
    // permission
    Route::get('permission/getAll', 'PermissionController@getAllPermission');
    Route::get('permission/getById/{id}', 'PermissionController@getByIdPermission');   
    Route::post('permission/create', 'PermissionController@createPermission');
    Route::put('permission/update/{id}', 'PermissionController@updatePermission');
    Route::delete('permission/delete/{id}', 'PermissionController@deletePermission');
    // posts
    Route::get('posts/getAll', 'PostController@getAllPosts');
    Route::get('post/getById/{id}', 'PostController@getByIdPost');
    Route::post('post/create', 'PostController@createPost');
    Route::put('post/update/{id}', 'PostController@updatePost');
    Route::delete('post/delete/{id}', 'PostController@deletePost');
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
