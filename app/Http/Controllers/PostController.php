<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use App\Traits\GlobalTrait;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\PostRequest;

class PostController extends Controller
{
    use GlobalTrait;

    public function __construct(Post $post,User $user){
        $this->post = $post;
        $this->user = $user;
        $this->middleware('can:post-list')->only('getAllPosts,getByIdPost');
        $this->middleware('can:post-create')->only('createPost');
        $this->middleware('can:post-edit')->only('updatePost');
        $this->middleware('can:post-delete')->only('deletePost');
    }

    public function getAllPosts(User $user)
    {
        try {
            Gate::authorize('post-list',$user);
            $posts = Post::all();
            if (count($posts) > 0) {
                return $response= $this->returnData('Posts',$posts,'done');
            } else {
                return $response= $this->returnSuccessMessage('Post','Post doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function getByIdPost($id,User $user)
    {
        try {
            Gate::authorize('post-list',$user);
            $post = Post::find($id);
            if (isset($post)) {
                return $response= $this->returnData('Post',$post,'done');
            } else {
                return $response= $this->returnSuccessMessage('This Post not found','done');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function createPost(PostRequest $request,User $user)
    {
        try {
            Gate::authorize('post-create',$user);
            $post = Post::create(array_merge(
                $request->validated(),
            ));
            return $response= $this->returnData('Post',$post,'done');
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function updatePost(PostRequest $request, $id,User $user)
    {
        try {
            Gate::authorize('post-edit',$user);
            $post = Post::find($id);
            if (isset($post)) {
                $request->validated();
                $post->update($request->all());
                return $response= $this->returnData('Post',$post,'done');
            } else {
                return $response= $this->returnSuccessMessage('This Post not found','done');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function deletePost($id,User $user)
    {
        try {
            Gate::authorize('post-delete',$user);
            $post = Post::find($id);
            if (isset($post)) {
                $post = Post::destroy($id);
                return $this->returnData('Post', $user,'This Post Is deleted Now');
            } else {
                return $response= $this->returnSuccessMessage('This Post not found','done');
            }
        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
}
