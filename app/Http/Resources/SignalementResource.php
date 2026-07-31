<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SignalementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'photo' => $this->photo,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'category' => $this->category,
            'priority' => $this->priority,
            'urgency' => $this->urgency,
            'summary' => $this->summary,
            'status' => $this->status,

            'user_id' => $this->user_id,
            'incident_id' => $this->incident_id,
            'departement_id' => $this->departement_id,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}