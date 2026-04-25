<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Saran extends Model
{
    protected $table = 'saran';

    protected $fillable = [
        'user_id',
        'isi_saran',
        'status',
        'balasan',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted(): void
    {
        static::created(function (Saran $saran) {
            \App\Jobs\SendSaranEmailToAdminJob::dispatch($saran);
        });

        static::updated(function (Saran $saran) {
            // Send email to user when status changes or balasan is updated
            if ($saran->wasChanged('status') || $saran->wasChanged('balasan')) {
                \App\Jobs\SendSaranUserEmailJob::dispatch($saran);
            }
        });
    }
}