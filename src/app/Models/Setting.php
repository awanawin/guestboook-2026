<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Setting extends Model
{
    protected $fillable = [
        'pengguna_id',
        'whatsapp_template',
        'custom_kiosk_title',
    ];

    public function pengguna(): BelongsTo
    {
        // Relasi ke model Pengguna (User/Client SaaS kita)
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }
}