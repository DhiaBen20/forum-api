<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Exists;

class ReplyController extends Controller
{
    public function index(Comment $comment): JsonResponse
    {
        $replies = $comment->replies()
            ->withCount('likes')
            ->with('user')
            ->orderBy('created_at')
            ->simplePaginate();

        return response()->json(CommentCollection::make($replies), 200);
    }

    public function store(Request $request, Comment $comment, #[CurrentUser()] User $user): JsonResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'max:5000'],
            'replyingTo' => ['nullable', new Exists('comments', 'id')->where('comment_id', $comment->id)],
        ]);

        $reply = $comment->replies()->create([
            'body' => $validated['body'],
            'user_id' => $user->id,
            'reply_to_id' => $validated['replyingTo'] ?? null,
        ]);

        return response()->json(CommentResource::make($reply), 201);
    }
}
