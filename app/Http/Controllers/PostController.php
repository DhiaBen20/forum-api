<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::query()
            ->with('user')
            ->withCount(['comments', 'likes'])
            ->isLikedByUser(Auth::guard('sanctum')->user())
            ->orderBy('posts.created_at', 'desc')
            ->orderBy('posts.id', 'asc')
            ->cursorPaginate(15);

        return response()->json(PostCollection::make($posts), 200);
    }

    public function show(mixed $post): JsonResponse
    {
        $post = Post::query()
            ->where('slug', $post)
            ->with('user')
            ->withCount(['likes', 'comments'])
            ->isLikedByUser(Auth::guard('sanctum')->user())
            ->firstOrFail();

        return response()->json(PostResource::make($post), 200);
    }

    public function store(Request $request, #[CurrentUser()] User $user): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'max:256'],
            'body' => ['required', 'max:5000'],
        ]);

        $slug = Str::lower($validated['title']);

        $duplicatesCount = Post::query()->whereLike('slug', "$validated[title]%")->count();

        $post = Post::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'slug' => $duplicatesCount ? $slug.'-'.($duplicatesCount + 1) : $slug,
            'user_id' => $user->id,
        ]);

        return response()->json(PostResource::make($post), 201);
    }

    public function update(Request $request, Post $post, #[CurrentUser()] User $user): JsonResponse
    {
        Gate::allowIf(fn () => $post->user_id === $user->id);

        $validated = $request->validate([
            'title' => ['required', 'max:256'],
            'body' => ['required', 'max:5000'],
        ]);

        $post->update([
            'title' => $validated['title'],
            'body' => $validated['body'],
        ]);

        return response()->json(PostResource::make($post), 200);
    }

    public function destroy(Post $post, #[CurrentUser()] User $user): JsonResponse
    {
        Gate::allowIf(fn () => $post->user_id === $user->id);

        $post->delete();

        return response()->json(status: 204);
    }
}
