<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'comments', 'likes'])->get();

        return response()->json([
            'success' => true,
            'data' => $posts,
        ]);
    }

    public function store(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
            'image_url' => 'nullable',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }
        // Jika validasi berhasil
        $post = Post::create([
            'user_id' => $user->id,
            'content' => $request->get('content'),
            'image_url' => $request->get('image_url'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Post created successfully',
            'data' => $post,
        ], 201);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id)->with(['user', 'comments', 'likes'])->get();

        return response()->json([
            'success' => true,
            'data' => $post,
        ], 200);
    }

    public function update($id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
            'image_url' => 'nullable',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 400);
        }

        $post = Post::findOrFail($id);

        // Jika validasi berhasil
        $post->content = $request->get('content');
        $post->image_url = $request->get('image_url');

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully',
            'data' => $post,
        ], 200);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully',
        ], 200);
    }
}
