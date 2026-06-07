<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Guest extends Model
{
    protected $fillable = [
        'pengguna_id', 
        'guestbook_id', 
        'name', 
        'phone', 
        'email', 
        'address', 
        'notes', 
        'photo'
    ];

    public function pengguna(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function guestbook(): BelongsTo
    {
        return $this->belongsTo(Guestbook::class);
    }
}