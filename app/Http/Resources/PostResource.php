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
            'excerpt' => $this->when($this->excerpt, $this->excerpt()),
            'body' => $this->when(! $this->excerpt, $this->body),
            'user' => UserResource::make($this->whenLoaded('user')),
            'commentsCount' => $this->whenCounted('comments'),
            'likesCount' => $this->whenCounted('likes'),
            'isLiked' => $this->whenExistsLoaded('likes', default: false),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }

    protected function excerpt(): string
    {
        $bodyInHtml = Str::markdown($this->body, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        return Str::of(strip_tags($bodyInHtml))->replace("\n", ' ')->excerpt() ?? '';
    }
}
