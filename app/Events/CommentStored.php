<?php

namespace App\Events;

use App\CommentType;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentStored
{
    use Dispatchable, SerializesModels;

    public function __construct(public ?CommentType $type, public User $currentUser, public Comment $comment) {}
}
