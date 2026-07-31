<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
            
            // Redirect based on notification type
            if (isset($notification->data['izincuti_id'])) {
                return redirect()->route('izincuti.index');
            }
        }
        
        return redirect()->back();
    }
    
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function getUnread()
    {
        $notifications = Auth::user()->unreadNotifications->map(function($notification) {
            return [
                'id' => $notification->id,
                'data' => $notification->data,
                'created_at' => $notification->created_at->diffForHumans(),
                'read_url' => route('notifications.read', $notification->id)
            ];
        });

        return response()->json([
            'count' => $notifications->count(),
            'notifications' => $notifications
        ]);
    }
}
