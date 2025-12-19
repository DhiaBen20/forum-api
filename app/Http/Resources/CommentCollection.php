<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\CursorPaginator;

/** @mixin CursorPaginator<int, Comment> */
class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->through(
            fn ($comment) => CommentResource::make($comment)->additional($this->additional)
        )->toArray();
    }
}
