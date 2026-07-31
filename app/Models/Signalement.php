<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
        use HasFactory;
    protected $fillable = [
        'user_id',
        'incident_id',
        'departement_id',
        'description',
        'photo',
        'latitude',
        'longitude',
        'category',
        'priority',
        'urgency',
        'summary',
        'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function incident(): BelongsTo
    {
        return $this->belongsTo(Incident::class);
    }

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class);
    }
}