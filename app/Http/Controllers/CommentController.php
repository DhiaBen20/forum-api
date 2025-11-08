<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController extends Controller
{
    public function index(Post $post): JsonResponse
    {
        $comments = $post->comments()
            ->withCount(['likes', 'replies'])
            ->with('user')
            ->orderBy('created_at')
            ->simplePaginate(15);

        return response()->json(CommentCollection::make($comments), 200);
    }

    public function store(Request $request, Post $post, #[CurrentUser()] User $user): JsonResponse
    {
        $validated = $request->validate(['body' => ['required', 'max:5000']]);

        $comment = $post->comments()->create([
            'body' => $validated['body'],
            'user_id' => $user->id,
        ]);

        return response()->json(CommentResource::make($comment), 201);
    }

    public function update(Request $request, Comment $comment, #[CurrentUser()] User $user): JsonResponse
    {
        Gate::allowIf(fn () => $comment->user_id === $user->id);

        $validated = $request->validate(['body' => ['required', 'max:5000']]);

        $comment->update(['body' => $validated['body']]);

        return response()->json(CommentResource::make($comment), 200);
    }

    public function destroy(Comment $comment, #[CurrentUser()] User $user): JsonResponse
    {
        Gate::allowIf(fn () => $comment->user_id === $user->id);

        $comment->delete();

        return response()->json(status: 204);
    }
}
