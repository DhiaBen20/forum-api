<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

/** @mixin Post */
class PostResource extends JsonResource
{
    protected bool $excerpt = false;

    public function withExcerpt(): self
    {
        $this->excerpt = true;

        return $this;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->when($this->excerpt, Str::of(strip_tags($this->body_in_html))->replace("\n", ' ')->excerpt()),
            'body' => $this->when(! $this->excerpt, $this->body),
            'body_in_html' => $this->when(! $this->excerpt, $this->body_in_html),
            'user' => UserResource::make($this->whenLoaded('user')),
            'commentsCount' => $this->whenCounted('comments'),
            'likesCount' => $this->whenCounted('likes'),
            'isLiked' => $this->whenExistsLoaded('likes', default: false),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
