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
            'excerpt' => $this->when($this->excerpt, Str::excerpt($this->body)),
            'body' => $this->when(! $this->excerpt, $this->body),
            'body_in_html' => $this->when(! $this->excerpt, $this->body_in_html),
            'user' => new UserResource($this->whenLoaded('user'))->basic(),
            'repliesCount' => $this->whenCounted('replies'),
            'likesCount' => $this->whenCounted('likes'),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
