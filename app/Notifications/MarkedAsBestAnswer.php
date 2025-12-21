<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MarkedAsBestAnswer extends Notification
{
    use Queueable;

    public function __construct(public User $actor, public Post $post, public Comment $comment) {}

    /**
     * @param  \App\Models\User  $notifiable
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * @param  \App\Models\User  $notifiable
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'actor' => [
                'id' => $this->actor->id,
                'name' => $this->actor->name,
            ],
            'post_id' => $this->post->id,
            'comment' => [
                'id' => $this->comment->id,
                'type' => $this->comment->type->value,
            ],
        ];
    }
}
