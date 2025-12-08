<?php

namespace App\Models;

use App\Likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $body
 * @property-read int|null $post_id
 * @property-read int|null $comment_id
 * @property-read int|null $reply_to_id_id
 * @property-read int $user_id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Comment extends Model
{
    /** @use Likeable<$this> */
    use Likeable;

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
}
