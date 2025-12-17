<?php

namespace App\Http\Controllers;

use App\CommentType;
use App\Events\CommentStored;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Resources\CommentCollection;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $parent = $request->query('parent');
        $type = $request->query('type');

        if (! in_array($type, ['post', 'comment'])) {
            return response()->json([], 400);
        }

        if (! DB::table(Str::plural($type))->where('id', $parent)->exists()) {
            return response()->json(status: 404);
        }

        $forignKey = $type.'_id';
        $relations = $type === 'post' ? ['likes', 'replies'] : ['likes'];

        $comments = Comment::query()
            ->where($forignKey, $parent)
            ->withCount($relations)
            ->isLikedByUser(Auth::guard('sanctum')->user())
            ->with('user')
            ->orderBy('created_at')
            ->simplePaginate(15);

        return response()->json(CommentCollection::make($comments), 200);
    }

    public function store(StoreCommentRequest $request, #[CurrentUser()] User $user): JsonResponse
    {
        $comment = Comment::create([
            'user_id' => $user->id,
            'body' => $request->validated('body'),
            'post_id' => $request->validated('post'),
            'comment_id' => $request->validated('comment'),
            'reply_to_id' => $request->validated('replyTo'),
        ]);

        CommentStored::dispatch(
            $request->enum('type', CommentType::class),
            $user,
            $comment,
        );

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
