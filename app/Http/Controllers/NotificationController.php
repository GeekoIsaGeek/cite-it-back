<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAsRead(int $notificationId): JsonResponse
    {
        $notification = Notification::findOrFail($notificationId);
        $notification->update(['seen' => 1]);
        return response()->json($notification, 200);
    }

    public function markAllAsRead(): JsonResponse
    {
        Notification::where('user_id',auth()->user()->id)->update(['seen' => 1]);
        return response()->json(auth()->user()->notifications);
    }

    public function getNotifications(): JsonResponse
    {
        $notifications = Notification::where('user_id',auth()->user()->id)->orderBy('created_at','desc')->paginate(5);
        return response()->json($notifications);
    }
}
