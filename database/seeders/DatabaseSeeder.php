<?php

namespace Database\Seeders;

use App\Models\Signalement;
use Illuminate\Database\Seeder;

class SignalementSeeder extends Seeder
{
    public function run(): void
    {
        Signalement::factory(30)->create();
    }
}