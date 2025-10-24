<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::query()
            ->withCount(['replies', 'likes'])
            ->with('user')
            ->whereNull('parent_id')
            ->orderBy('posts.created_at', 'desc')
            ->orderBy('posts.id', 'asc')
            ->cursorPaginate(15);

        return response()->json(new PostCollection($posts), 200);
    }

    public function show(mixed $post): JsonResponse
    {
        $post = Post::query()->with('user')->withCount(['likes', 'replies'])->firstOrFail($post);

        return response()->json(new PostResource($post), 200);
    }
}
