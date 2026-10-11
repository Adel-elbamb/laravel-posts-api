<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // GET /api/posts
    public function index()
    {
        return response()->json(Post::latest()->get());
    }

    // POST /api/posts
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
        ]); 

        $post = Post::create($validated);

        return response()->json([
            'message' => 'Post created successfully',
            'data' => $post,
        ], 201);
    }

    // GET /api/posts/{id}
    public function show(string $id)
    {
        $post = Post::findOrFail($id);

        return response()->json($post);
    }

    // PUT/PATCH /api/posts/{id}
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        $post->update($validated);

        return response()->json([
            'message' => 'Post updated successfully',
            'data' => $post,
        ]);
    }

    // DELETE /api/posts/{id}
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);
    }
}
