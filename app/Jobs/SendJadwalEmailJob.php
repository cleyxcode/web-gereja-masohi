<?php

namespace App\Jobs;

use App\Models\JadwalIbadah;
use App\Models\User;
use App\Notifications\JadwalDatabaseNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendJadwalEmailJob
{
    public function __construct(
        public JadwalIbadah $jadwal,
        public bool $isUpdate = false
    ) {}

    public static function dispatch(JadwalIbadah $jadwal, bool $isUpdate = false): void
    {
        $users = User::where('role', 'jemaat')
            ->where('is_approved', true)
            ->get();

        Log::info("[SendJadwalNotification] Mengirim notifikasi ke {$users->count()} jemaat untuk jadwal ID {$jadwal->id}");

        // Langsung ke database tanpa queue worker
        Notification::send($users, new JadwalDatabaseNotification($jadwal, $isUpdate));
    }
}
