<?php

namespace App\Jobs;

use App\Mail\JadwalNotification;
use App\Models\JadwalIbadah;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendJadwalEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Timeout per job: 60 detik sudah cukup untuk satu email.
     */
    public int $timeout = 60;

    /**
     * Coba ulang 2x jika gagal (misal SMTP timeout sementara).
     */
    public int $tries = 2;

    public function __construct(
        public JadwalIbadah $jadwal,
        public bool $isUpdate = false,
        public ?string $recipientEmail = null,
    ) {}

    public function handle(): void
    {
        // Jika recipientEmail diisi: kirim ke satu orang saja (mode fan-out)
        if ($this->recipientEmail) {
            try {
                Mail::to($this->recipientEmail)
                    ->send(new JadwalNotification($this->jadwal, $this->isUpdate));
            } catch (\Exception $e) {
                Log::error("Gagal mengirim email jadwal ke {$this->recipientEmail}: " . $e->getMessage());
            }
            return;
        }

        // Mode broadcast: buat job individual untuk setiap jemaat
        $users = User::where('role', 'jemaat')
            ->where('is_approved', true)
            ->select('id', 'email')
            ->get();

        Log::info("[SendJadwalEmailJob] Mengirim ke {$users->count()} jemaat untuk jadwal ID {$this->jadwal->id}");

        foreach ($users as $user) {
            if (!$user->email) continue;

            self::dispatch($this->jadwal, $this->isUpdate, $user->email)
                ->delay(now()->addSeconds(rand(1, 5))); // sebar beban SMTP
        }
    }
}
