<?php

namespace App\Listeners;

use App\Events\PostAnswered;
use App\Notifications\MarkedAsBestAnswer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class SendMarkedAsBestAnswerNotification implements ShouldQueue
{
    public function handle(PostAnswered $event): void
    {
        if ((! $event->post->best_answer_id) || $event->post->user_id === $event->comment->user_id) {
            return;
        }

        Notification::send(
            $event->comment->user,
            new MarkedAsBestAnswer($event->currentUser, $event->post, $event->comment)
        );
    }
}
