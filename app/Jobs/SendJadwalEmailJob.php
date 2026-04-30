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
use Illuminate\Support\Facades\Mail;

class SendJadwalEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $jadwal;
    public $isUpdate;

    public function __construct(JadwalIbadah $jadwal, bool $isUpdate = false)
    {
        $this->jadwal = $jadwal;
        $this->isUpdate = $isUpdate;
    }

    public function handle(): void
    {
        $users = User::where('role', 'jemaat')->where('is_approved', true)->get();
        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new JadwalNotification($this->jadwal, $this->isUpdate));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Gagal mengirim email jadwal ke {$user->email}: " . $e->getMessage());
            }
        }
    }
}
