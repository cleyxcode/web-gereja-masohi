<?php

namespace App\Jobs;

use App\Models\Berita;
use App\Models\User;
use App\Notifications\BeritaDatabaseNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class SendBeritaEmailJob
{
    public function __construct(
        public Berita $berita,
        public bool $isUpdate = false
    ) {}

    public static function dispatch(Berita $berita, bool $isUpdate = false): void
    {
        $users = User::with('pushSubscriptions')
            ->where('role', 'jemaat')
            ->where('is_approved', true)
            ->get();

        Log::info("[SendBeritaNotification] Mengirim notifikasi ke {$users->count()} jemaat untuk berita ID {$berita->id}");

        // Langsung ke database tanpa queue worker
        Notification::send($users, new BeritaDatabaseNotification($berita, $isUpdate));

        // Background Web Push Notification
        $auth = [
            'VAPID' => [
                'subject' => env('VAPID_SUBJECT'),
                'publicKey' => env('VAPID_PUBLIC_KEY'),
                'privateKey' => env('VAPID_PRIVATE_KEY'),
            ],
        ];

        try {
            $webPush = new WebPush($auth);
            $prefix = $isUpdate ? 'Update Berita:' : 'Berita Baru:';
            $payload = json_encode([
                'title' => $prefix . ' ' . $berita->judul,
                'body'  => 'Silakan cek berita terbaru di website.',
                'url'   => url('/berita/' . $berita->id),
                'icon'  => asset('images/logoupdate.png'),
            ]);

            foreach ($users as $user) {
                foreach ($user->pushSubscriptions as $sub) {
                    $subscription = Subscription::create([
                        'endpoint' => $sub->endpoint,
                        'publicKey' => $sub->public_key,
                        'authToken' => $sub->auth_token,
                        'contentEncoding' => $sub->content_encoding,
                    ]);

                    $webPush->queueNotification($subscription, $payload);
                }
            }

            foreach ($webPush->flush() as $report) {
                if (!$report->isSuccess() && $report->isSubscriptionExpired()) {
                    // Subscription kedaluwarsa, hapus dari DB
                    \App\Models\PushSubscription::where('endpoint', $report->getRequest()->getUri()->__toString())->delete();
                }
            }
        } catch (\Exception $e) {
            Log::error("[WebPush Error] " . $e->getMessage());
        }
    }
}
