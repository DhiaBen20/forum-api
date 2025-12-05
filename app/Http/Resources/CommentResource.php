<?php

namespace App\Http\Resources;

use App\Models\Comment;
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
        return [
            'id' => $this->id,
            'bodyInHtml' => $this->body_in_html,
            'user' => UserResource::make($this->whenLoaded('user')),
            'likesCount' => $this->whenCounted('likes'),
            'repliesCount' => $this->whenCounted('replies'),
            'isLiked' => $this->whenExistsLoaded('likes', default: false),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
