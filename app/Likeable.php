<?php

namespace App;

use App\Models\Like;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @template TLikeable of \Illuminate\Database\Eloquent\Model
 */
trait Likeable
{
    /** @return MorphMany<Like, $this> */
    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    /**
     * @param  Builder<TLikeable>  $query
     */
    #[Scope]
    protected function isLikedByUser(Builder $query, ?Authenticatable $user): void
    {
        if (! $user || ! ($user instanceof User)) {
            return;
        }

        $query->withExists(['likes' => function (Builder $query) use ($user) {
            $query->where('user_id', $user->id);
        }]);
    }
}
