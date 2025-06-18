<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->unreadNotifications;
    }

    public function markAsRead(Request $request, $notificationId)
    {
        // Manually find the notification that belongs to the authenticated user.
        $notification = $request->user()
            ->notifications()
            ->where('id', $notificationId)
            ->first();

        // If a notification was found, mark it as read.
        if ($notification) {
            $notification->markAsRead();
            return response()->noContent(); // Return a "204 No Content" success response
        }

        // If no notification was found for this user with this ID, return an error.
        return response()->json(['message' => 'Notification not found.'], 404);
    }
}
