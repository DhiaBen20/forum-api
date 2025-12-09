<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    protected function getLikeableRecord(string $type, string $id): null|Post|Comment
    {
        $query = $type === 'posts' ? Post::query() : Comment::query();

        return $query->where('id', $id)->first();
    }

    public function store(#[CurrentUser()] User $user, string $type, string $likeable): JsonResponse
    {
        $likeableModel = $this->getLikeableRecord($type, $likeable);

        if (! $likeableModel) {
            return response()->json(status: 404);
        }

        if ($likeableModel->likes()->where('user_id', $user->id)->exists()) {
            return response()->json(status: 409);
        }

        $likeableModel->likes()->create(['user_id' => $user->id]);

        return response()->json(status: 204);
    }

    public function destroy(#[CurrentUser()] User $user, string $type, string $likeable): JsonResponse
    {
        $likeableModel = $this->getLikeableRecord($type, $likeable);

        if (! $likeableModel) {
            return response()->json(status: 404);
        }

        $likeableModel->likes()->where('user_id', $user->id)->delete();

        return response()->json(status: 204);
    }
}
