<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incident extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
    ];

    public function signalements(): HasMany
    {
        return $this->hasMany(Signalement::class);
    }
}