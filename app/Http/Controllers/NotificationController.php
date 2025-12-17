<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class NotificationController extends Controller
{
    /**
     * Get all unread notifications for the user.
     */
    public function index(Request $request)
    {
        return $request->user()->unreadNotifications;
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllAsRead(Request $request): Response
    {
        $request->user()->unreadNotifications->markAsRead();

        return response()->noContent();
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead(Request $request, string $id): Response
    {
        $notification = $request->user()->notifications()->findOrFail($id);
        
        $notification->markAsRead();

        return response()->noContent();
    }
}
