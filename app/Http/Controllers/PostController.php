<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Traits\GlobalTrait;
use Illuminate\Support\Facades\Gate;

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
                return response([
                    'Posts' => $posts,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'Posts' => $posts,
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'Posts not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Posts doesnt exist yet'
            ], 400);
        }
    }
    public function getByIdPost($id,User $user)
    {
        try {
            Gate::authorize('post-list',$user);
            $post = Post::find($id);
            if (isset($post)) {
                return response([
                    'Post' => $post,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'Posts not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Posts doesnt exist yet'
            ], 400);
        }
    }
    public function createPost(User $user)
    {
        try {
            Gate::authorize('post-create',$user);
            $title = request()->title;
            $body = request()->body;
            $user_id = request()->user_id;
            $post = new Post;
            $post->title = $title;
            $post->body = $body;
            $post->user_id = $user_id;
            $post->save();
            return response([
                'Post' => $post,
                'status' => true,
                'stateNum' => 200,
                'message' => 'done'
            ], 200);
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Posts doesnt exist yet'
            ], 400);
        }
    }
    public function updatePost(Request $request, $id,User $user)
    {
        try {
            Gate::authorize('post-edit',$user);
            $post = Post::find($id);
            if (isset($post)) {
                $post->update($request->all());
                return response([
                    'Post' => $post,
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'Posts not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Posts doesnt exist yet'
            ], 400);
        }
    }
    public function deletePost($id,User $user)
    {
        try {
            Gate::authorize('post-delete',$user);
            $post = Post::find($id);
            if (isset($post)) {
                $post = Post::destroy($id);
                return response([
                    'status' => true,
                    'stateNum' => 200,
                    'message' => 'done'
                ], 200);
            } else {
                return response([
                    'status' => true,
                    'stateNum' => 401,
                    'message' => 'Posts not found'
                ], 401);
            }
        } catch (\Exception $ex) {
            return response([
                'status' => false,
                'stateNum' => 400,
                'message' => 'Error! Posts doesnt exist yet'
            ], 400);
        }
    }
}
