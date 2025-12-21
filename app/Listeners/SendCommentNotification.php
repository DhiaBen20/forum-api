<?php

namespace App\Listeners;

use App\CommentType;
use App\Events\CommentStored;
use App\Models\Comment;
use App\Models\User;
use App\Notifications\CommentReceived;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCommentNotification implements ShouldQueue
{
    public function handle(CommentStored $event): void
    {
        $notifiable = $this->getNotifiable($event->comment);

        if (! $notifiable || $event->currentUser->is($notifiable)) {
            return;
        }

        $notifiable->notify(
            new CommentReceived($event->currentUser, $event->comment)
        );
    }

    protected function getNotifiable(Comment $comment): ?User
    {
        $commentedOn = match ($comment->type) {
            CommentType::CommentToPost => $comment->post,
            CommentType::ReplyToReply => $comment->replyTo,
            CommentType::ReplyToComment => $comment->comment
        };

        return $commentedOn?->user;
    }
}
