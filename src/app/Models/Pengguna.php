<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengguna extends Authenticatable
{
    use HasFactory;

    protected $table = 'penggunas';

    protected $fillable = [
        'name',
        'email',
        'password',
        'theme_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi Theme aktif
    public function theme(): BelongsTo
    {
        return $this->belongsTo(Theme::class);
    }

    // Satu pengguna punya banyak guest book
    public function guestBooks(): HasMany
    {
        return $this->hasMany(GuestBook::class, 'pengguna_id');
    }

    // Setting 1:1
    public function setting(): HasOne
    {
        return $this->hasOne(Setting::class, 'pengguna_id');
    }
}
