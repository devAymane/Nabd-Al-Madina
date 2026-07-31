<?php

namespace App\Services;

use function Laravel\Ai\agent;

class SignalementAnalyzer
{
    public function analyze(string $description)
    {
        return agent(
            instructions: "Tu es un assistant qui analyse les signalements urbains."
        )->prompt("
Analyse ce signalement :

{$description}

Retourne uniquement un JSON contenant :
- category
- priority
- urgency
- summary
- department
");
    }
}