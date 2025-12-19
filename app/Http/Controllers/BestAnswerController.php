<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;

class BestAnswerController extends Controller
{
    public function __invoke(#[CurrentUser()] User $user, Comment $comment): JsonResponse
    {
        $post = $comment->post;

        if (! $post) {
            abort(404);
        }

        if ($post->user_id !== $user->id) {
            abort(403);
        }

        $post->update([
            'best_answer_id' => $post->best_answer_id === $comment->id ? null : $comment->id,
        ]);

        return response()->json(['message' => 'Best answer updated']);
    }
}
