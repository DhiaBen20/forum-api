<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string $slug
 * @property-read string $body
 * @property-read string $body_in_html
 * @property-read int $user_id
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

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

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<Like, $this> */
    public function likes(): HasMany
    {
        return $this->hasMany(Like::class, 'likeable_id');
    }

    /** @return HasMany<Post, $this> */
    public function replies(): HasMany
    {
        return $this->hasMany(Post::class, 'parent_id');
    }
}
