<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\CursorPaginator;

/** @mixin CursorPaginator<int, Post> */
class PostCollection extends ResourceCollection
{
    /**
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->through(fn ($post) => PostResource::make($post)->withExcerpt())->toArray();
    }
}
