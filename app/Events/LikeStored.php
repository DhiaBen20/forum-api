<?php

namespace App\Events;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LikeStored
{
    use Dispatchable, SerializesModels;

    public function __construct(public User $user, public Post|Comment $likeable) {}
}
