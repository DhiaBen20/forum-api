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
        if (! $event->type) {
            return;
        }

        $notifiable = $this->getNotifiable($event->comment, $event->type);

        if (! $notifiable || $event->currentUser->is($notifiable)) {
            return;
        }

        $notifiable->notify(
            new CommentReceived($event->currentUser, $event->type, $event->comment)
        );
    }

    protected function getNotifiable(Comment $comment, CommentType $type): ?User
    {
        $commentedOn = match ($type) {
            CommentType::CommentToPost => $comment->post,
            CommentType::ReplyToReply => $comment->replyTo,
            CommentType::ReplyToComment => $comment->comment
        };

        return $commentedOn?->user;
    }
}
