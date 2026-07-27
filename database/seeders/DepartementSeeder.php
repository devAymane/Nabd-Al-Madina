<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    public function run(): void
    {
        $departements = [
            [
                'nom' => 'Voirie',
                'description' => 'Gestion des routes et infrastructures routières.',
            ],
            [
                'nom' => 'Éclairage',
                'description' => 'Gestion de l’éclairage public.',
            ],
            [
                'nom' => 'Espaces verts',
                'description' => 'Entretien des parcs et jardins.',
            ],
            [
                'nom' => 'Eau',
                'description' => 'Gestion des fuites et réseaux d’eau.',
            ],
            [
                'nom' => 'Accessibilité',
                'description' => 'Aménagement des espaces accessibles.',
            ],
        ];

        foreach ($departements as $departement) {
            Departement::create($departement);
        }
    }
}