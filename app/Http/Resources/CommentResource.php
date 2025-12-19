<?php

namespace App\Http\Resources;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Comment */
class CommentResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $parentPost = array_key_exists('parentPost', $this->additional) ? $this->additional['parentPost'] : null;

        return [
            'id' => $this->id,
            'body' => $this->body,
            'user' => UserResource::make($this->whenLoaded('user')),
            'likesCount' => $this->whenCounted('likes'),
            'repliesCount' => $this->whenCounted('replies'),
            'isLiked' => $this->likes_exists ?? false,
            'isBestAnswer' => $this->when($parentPost instanceof Post, fn () => $parentPost->best_answer_id === $this->id),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
