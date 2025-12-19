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
 * @property-read string $title
 * @property-read string $slug
 * @property-read string $body
 * @property-read int $user_id
 * @property-read int|null $best_answer_id
 * @property-read User|null $user
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Post extends Model
{
    /**
     * @use HasFactory<\Database\Factories\PostFactory>
     * @use Likeable<$this>
     */
    use HasFactory, Likeable;

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<Comment, $this> */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /** @return BelongsTo<Comment, $this> */
    public function bestAnswer(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'best_answer_id');
    }
}
