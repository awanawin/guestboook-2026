<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Theme extends Model
{
    protected $fillable = ['name', 'slug', 'primary_color', 'background_gradient'];

    public function guestbooks(): HasMany
    {
        return $this->hasMany(Guestbook::class);
    }
}