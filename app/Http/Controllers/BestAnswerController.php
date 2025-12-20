<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class BestAnswerController extends Controller
{
    public function __invoke(#[CurrentUser()] User $user, Comment $comment): JsonResponse
    {
        $post = $comment->parentPost();

        if (! $post) {
            abort(404);
        }

        Gate::denyIf($post->user_id !== $user->id);

        $post->update([
            'best_answer_id' => $post->best_answer_id === $comment->id ? null : $comment->id,
        ]);

        return response()->json();
    }
}
