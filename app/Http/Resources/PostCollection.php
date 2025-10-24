<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\CursorPaginator as PaginationCursorPaginator;

class PostCollection extends ResourceCollection
{
    /**
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var PaginationCursorPaginator<int, Post> */
        $paginator = $this->resource;

        return $paginator->through(fn ($post) => new PostResource($post)->withExcerpt())->toArray();
    }
}
