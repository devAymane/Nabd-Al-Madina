<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Departement extends Model
{

    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
    ];

    public function signalements(): HasMany
    {
        return $this->hasMany(Signalement::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}