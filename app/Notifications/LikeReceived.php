<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class LikeReceived extends Notification
{
    use Queueable;

    public function __construct(public Post|Comment $likeable, public User $actor) {}

    /**
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
            'target' => [
                'type' => $this->getLikeableType(),
                'id' => $this->likeable->id,
            ],
            'actor' => [
                'id' => $this->actor->id,
                'name' => $this->actor->name,
            ],
        ];
    }

    /**
     * @param  \App\Models\User  $notifiable
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage(
            $this->toArray($notifiable)
        );
    }

    protected function getLikeableType(): string
    {
        $likeable = $this->likeable;

        if ($likeable instanceof Post) {
            return 'post';
        }

        if ($likeable->post_id) {
            return 'comment';
        }

        return 'reply';
    }
}
