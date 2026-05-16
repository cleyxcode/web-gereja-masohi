<?php

namespace App\Jobs;

use App\Models\Berita;
use App\Models\User;
use App\Notifications\BeritaDatabaseNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendBeritaEmailJob
{
    public function __construct(
        public Berita $berita,
        public bool $isUpdate = false
    ) {}

    public static function dispatch(Berita $berita, bool $isUpdate = false): void
    {
        $users = User::where('role', 'jemaat')
            ->where('is_approved', true)
            ->get();

        Log::info("[SendBeritaNotification] Mengirim notifikasi ke {$users->count()} jemaat untuk berita ID {$berita->id}");

        // Langsung ke database tanpa queue worker
        Notification::send($users, new BeritaDatabaseNotification($berita, $isUpdate));
    }
}
