<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements FilamentUser, HasAvatar
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'jenis_kelamin',
        'sektor',
        'unit',
        'no_hp',
        'alamat',
        'avatar',
        'avatar_url',   // dipakai filament-edit-profile
        'is_approved',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_approved'       => 'boolean',
            'password'          => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Apakah user sudah disetujui admin dan boleh login.
     */
    public function isApproved(): bool
    {
        if ($this->role === 'admin') {
            return true;
        }
        return $this->is_approved === true;
    }

    /**
     * Avatar URL untuk Filament (dipakai filament-edit-profile).
     * Prioritas: avatar_url → avatar (kolom lama)
     */
    public function getFilamentAvatarUrl(): ?string
    {
        $avatarColumn = config('filament-edit-profile.avatar_column', 'avatar_url');

        if ($this->$avatarColumn) {
            return Storage::url($this->$avatarColumn);
        }

        // Fallback ke kolom avatar lama
        if ($this->avatar) {
            return Storage::disk('public')->url($this->avatar);
        }

        return null;
    }

    /**
     * @deprecated Gunakan getFilamentAvatarUrl() sebagai gantinya.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->getFilamentAvatarUrl();
    }

    public function pendaftaran(): HasMany
    {
        return $this->hasMany(Pendaftaran::class);
    }

    public function saran(): HasMany
    {
        return $this->hasMany(Saran::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}