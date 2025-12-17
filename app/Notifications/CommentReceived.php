<?php

namespace App\Notifications;

use App\CommentType;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommentReceived extends Notification
{
    use Queueable;

    public function __construct(
        public User $actor,
        public CommentType $type,
        public Comment $comment,
    ) {}

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
            'post_id' => $this->getParentPostId(),
            'comment' => [
                'id' => $this->comment->id,
                'type' => $this->type->value,
            ],
        ];
    }

    protected function getParentPostId(): ?int
    {
        $comment = $this->comment;

        if ($comment->post_id) {
            return $comment->post_id;
        }

        return $comment->comment?->post_id;
    }
}
