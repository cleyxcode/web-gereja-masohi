<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Berita extends Model
{
    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'isi',
        'gambar',
        'tanggal',
        'created_by',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function booted(): void
    {
        static::created(function (Berita $berita) {
            \App\Jobs\SendBeritaEmailJob::dispatch($berita, false);
        });

        static::updated(function (Berita $berita) {
            // Check if there are changes before sending email
            if ($berita->wasChanged()) {
                \App\Jobs\SendBeritaEmailJob::dispatch($berita, true);
            }
        });
    }
}