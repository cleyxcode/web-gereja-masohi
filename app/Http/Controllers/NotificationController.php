<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function fetchUnread()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['count' => 0, 'latest' => []]);

        return response()->json([
            'count' => $user->unreadNotifications->count(),
            'latest' => $user->notifications()->take(10)->get()
        ]);
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi telah ditandai sudah dibaca.');
    }

    public function read($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        $notification->markAsRead();

        // Redirect to the URL provided in the notification data if it exists
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return back();
    }
}
