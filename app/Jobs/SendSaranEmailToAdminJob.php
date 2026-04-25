<?php

namespace App\Jobs;

use App\Mail\SaranAdminNotification;
use App\Models\Saran;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSaranEmailToAdminJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $saran;

    public function __construct(Saran $saran)
    {
        $this->saran = $saran;
    }

    public function handle(): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new SaranAdminNotification($this->saran));
        }
    }
}
