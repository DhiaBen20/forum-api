<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property-read int $id
 * @property-read string $body
 * @property-read string $body_in_html
 * @property-read int|null $post_id
 * @property-read int $user_id
 * @property-read int|null $comment_id
 * @property-read int|null $reply_to_id_id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Comment extends Model
{
    protected $appends = ['body_in_html'];

    /** @return Attribute<string,never> */
    protected function bodyInHtml(): Attribute
    {
        return Attribute::make(
            get: fn ($_, array $attributes) => Str::markdown(
                $attributes['body'],
                ['html_input' => 'escape', 'allow_unsafe_links' => false]
            )
        );
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return MorphMany<Like, $this> */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /** @return HasMany<Comment, $this> */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
