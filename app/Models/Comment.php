<?php

namespace App\Models;

use App\Likeable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $body
 * @property-read int|null $post_id
 * @property-read int|null $comment_id
 * @property-read int|null $reply_to_id
 * @property-read int $user_id
 * @property-read User|null $user
 * @property-read Post|null $post
 * @property-read Comment|null $comment
 * @property-read Comment|null $replyTo
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Comment extends Model
{
    /**
     * @use Likeable<$this>
     * @use HasFactory<\Database\Factories\CommentFactory>
     */
    use HasFactory, Likeable;

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<Comment, $this> */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /** @return BelongsTo<Post, $this> */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /** @return BelongsTo<Comment, $this> */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class);
    }

    /** @return BelongsTo<Comment, $this> */
    public function replyTo(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'reply_to_id');
    }

    public function parentPost(): ?Post
    {
        return $this->post_id ? $this->post : Post::query()
            ->select('posts.*')
            ->join('comments', 'posts.id', 'comments.post_id')
            ->where('comments.id', $this->comment_id)
            ->first();
    }
}
