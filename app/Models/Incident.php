<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incident extends Model
{
    protected $fillable = [
        'titre',
        'description',
    ];

    public function signalements(): HasMany
    {
        return $this->hasMany(Signalement::class);
    }
}