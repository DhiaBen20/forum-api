<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationCollection;
use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    public function index(#[CurrentUser()] User $user): JsonResponse
    {
        return response()->json(
            NotificationCollection::make($user->unreadNotifications),
            200
        );
    }

    public function markAllAsRead(#[CurrentUser()] User $user): Response
    {
        $user->unreadNotifications->markAsRead();

        return response()->noContent();
    }

    public function markAsRead(#[CurrentUser()] User $user, string $id): Response
    {
        $notification = $user->notifications()->findOrFail($id);

        $notification->markAsRead();

        return response()->noContent();
    }
}
