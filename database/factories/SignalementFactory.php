<?php

namespace Database\Factories;

use App\Models\Departement;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SignalementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),

            'incident_id' => fake()->boolean(50)
                ? Incident::factory()
                : null,

            'departement_id' => Departement::inRandomOrder()->first()->id,

            'description' => fake()->paragraph(),

            'photo' => null,

            'latitude' => fake()->latitude(),

            'longitude' => fake()->longitude(),

            'category' => fake()->randomElement([
                'Voirie',
                'Éclairage',
                'Déchets',
                'Eau',
                'Accessibilité',
            ]),

            'priority' => fake()->randomElement([
                'low',
                'medium',
                'high',
            ]),

            'urgency' => fake()->numberBetween(1, 5),

            'summary' => fake()->sentence(),

            'status' => fake()->randomElement([
                'nouveau',
                'en_cours',
                'resolu',
                'rejete',
            ]),
        ];
    }
}