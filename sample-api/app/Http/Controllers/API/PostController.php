<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'author_id' => 'required|integer',
            'title' => 'required',
            'type' => 'required',
            'post' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $postData = [
            'author_id' => $request->input('author_id'),
            'title' => $request->input('title'),
            'type' => $request->input('type'),
            'post' => $request->input('post'),
        ];

        $post = Post::create($postData);

        return response()->json([
            'status' => 'success',
            'data' => $post,
        ], 201);
    }

    public function index()
    {
        $posts = Post::with('author')
            ->orderByDesc('updated_at') // Add this line to order by the 'updated_at' column in descending order
            ->get();

        $postData = $posts->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'type' => $post->type,
                'author_id' => $post->author_id,
                'author_name' => $post->author->first_name . ' ' . $post->author->last_name,
                'post' => $post->post,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => $postData,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'type' => 'required',
            'post' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $postData = [
            'title' => $request->input('title'),
            'type' => $request->input('type'),
            'post' => $request->input('post'),
        ];

        $post = Post::find($id);

        if (!$post) {
            return response()->json(['error' => 'Post not found'], 404);
        }

        $post->update($postData);

        return response()->json([
            'status' => 'success',
            'data' => $post,
        ]);
    }

    public function delete($id)
{
    $post = Post::find($id);

    if (!$post) {
        return response()->json(['error' => 'Post not found'], 404);
    }

    $post->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'Post deleted successfully',
    ]);
}
}
