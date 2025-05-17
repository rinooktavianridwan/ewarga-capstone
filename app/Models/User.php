<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isPengurus(): bool
    {
        return $this->profilWarga && $this->profilWarga->pengurus()->exists();
    }

    public function instansi(): BelongsToMany
    {
        return $this->belongsToMany(Instansi::class, 'warga_instansi', 'user_id', 'instansi_id');
    }

    public function wargaInstansi(): HasMany
    {
        return $this->HasMany(WargaInstansi::class, 'user_id');
    }

    public function isOwner($instansi_id): bool
    {
        return true;
    }

    public function profilWarga()
    {
        return $this->hasOne(Warga::class, 'user_id');
    }
}
