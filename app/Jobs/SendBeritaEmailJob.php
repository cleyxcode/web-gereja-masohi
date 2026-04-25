<?php

namespace App\Jobs;

use App\Mail\BeritaNotification;
use App\Models\Berita;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendBeritaEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $berita;
    public $isUpdate;

    public function __construct(Berita $berita, bool $isUpdate = false)
    {
        $this->berita = $berita;
        $this->isUpdate = $isUpdate;
    }

    public function handle(): void
    {
        $users = User::where('role', 'jemaat')->where('is_approved', true)->get();
        foreach ($users as $user) {
            Mail::to($user->email)->send(new BeritaNotification($this->berita, $this->isUpdate));
        }
    }
}
