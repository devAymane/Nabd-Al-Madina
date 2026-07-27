<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartementFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nom' => fake()->unique()->randomElement([
                'Voirie',
                'Éclairage',
                'Espaces verts',
                'Eau',
                'Accessibilité',
            ]),
            'description' => fake()->sentence(),
        ];
    }
}