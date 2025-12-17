<?php

namespace App\Listeners;

use App\Events\LikeStored;
use App\Models\User;
use App\Notifications\LikeReceived;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendLikeNotification implements ShouldQueue
{
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(LikeStored $event): void
    {
        /** @var User */
        $notifiable = $event->likeable->user;

        if ($event->user->is($notifiable)) {
            return;
        }

        $notifiable->notify(
            new LikeReceived($event->likeable, $event->user)
        );
    }
}
