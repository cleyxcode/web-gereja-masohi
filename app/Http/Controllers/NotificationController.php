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

    public function pushSubscribe(Request $request)
    {
        $request->validate([
            'endpoint'    => 'required',
            'keys.p256dh' => 'required',
            'keys.auth'   => 'required',
        ]);

        $user = Auth::user();

        $user->pushSubscriptions()->updateOrCreate(
            ['endpoint' => $request->endpoint],
            [
                'public_key' => $request->keys['p256dh'],
                'auth_token' => $request->keys['auth'],
            ]
        );

        return response()->json(['success' => true]);
    }

    public function pushUnsubscribe(Request $request)
    {
        $request->validate([
            'endpoint' => 'required',
        ]);

        Auth::user()->pushSubscriptions()->where('endpoint', $request->endpoint)->delete();

        return response()->json(['success' => true]);
    }
}
