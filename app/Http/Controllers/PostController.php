<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;


class PostController extends Controller
{
    public function getAllPosts()
    {
        try {
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
    public function getByIdPost($id)
    {
        try {
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
    public function createPost()
    {
        try {
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
    public function updatePost(Request $request, $id)
    {
        try {
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
    public function deletePost($id)
    {
        try {
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
