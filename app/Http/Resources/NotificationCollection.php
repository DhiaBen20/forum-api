<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Notifications\DatabaseNotification;

class NotificationCollection extends ResourceCollection
{
    /**
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(fn (DatabaseNotification $notification) => [
            'id' => $notification->id,
            'type' => $notification->type,
            'data' => $notification->data,
            'isRead' => (bool) $notification->read_at,
        ])->toArray();
    }
}
