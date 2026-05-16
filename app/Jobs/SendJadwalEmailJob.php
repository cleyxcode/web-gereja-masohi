<?php

namespace App\Jobs;

use App\Models\JadwalIbadah;
use App\Models\User;
use App\Notifications\JadwalDatabaseNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

class SendJadwalEmailJob
{
    public function __construct(
        public JadwalIbadah $jadwal,
        public bool $isUpdate = false
    ) {}

    public static function dispatch(JadwalIbadah $jadwal, bool $isUpdate = false): void
    {
        $users = User::with('pushSubscriptions')
            ->where('role', 'jemaat')
            ->where('is_approved', true)
            ->get();

        Log::info("[SendJadwalNotification] Mengirim notifikasi ke {$users->count()} jemaat untuk jadwal ID {$jadwal->id}");

        // Langsung ke database tanpa queue worker
        Notification::send($users, new JadwalDatabaseNotification($jadwal, $isUpdate));

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
            $tanggal = \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y');
            $prefix = $isUpdate ? 'Update Jadwal Ibadah:' : 'Jadwal Ibadah Baru:';
            $payload = json_encode([
                'title' => $prefix . ' ' . $tanggal,
                'body'  => "Jadwal ibadah hari {$tanggal} telah dipublikasikan. Tempat: {$jadwal->tempat}.",
                'url'   => url('/jadwal/' . $jadwal->id),
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
