public function store(StoreSignalementRequest $request)
{
    $data = $request->validated();

    $data['user_id'] = auth()->id();
    $data['status'] = 'nouveau';

    // Upload photo
    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('signalements', 'public');
    }

    // Analyse IA
    try {

        $analyzer = app(SignalementAnalyzer::class);
        $response = $analyzer->analyze($data['description']);

        $json = str_replace(
            ['```json', '```'],
            '',
            trim($response->text)
        );

        $ai = json_decode($json, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($ai)) {

            // Category
            $data['category'] = $ai['category'] ?? null;

            // Priority
            $data['priority'] = match (strtolower($ai['priority'] ?? '')) {
                'haute', 'high' => 'high',
                'moyenne', 'medium' => 'medium',
                'basse', 'low' => 'low',
                default => null,
            };

            // Urgency
            if (is_numeric($ai['urgency'] ?? null)) {
                $data['urgency'] = (int) $ai['urgency'];
            } else {
                $data['urgency'] = match (strtolower($ai['urgency'] ?? '')) {
                    'haute', 'high' => 5,
                    'moyenne', 'medium' => 3,
                    'basse', 'low' => 1,
                    default => null,
                };
            }

            // Summary
            $data['summary'] = $ai['summary'] ?? null;

            // Department
            if (!empty($ai['department'])) {

                $departement = Departement::firstOrCreate(
                    ['nom' => $ai['department']],
                    ['description' => null]
                );

                $data['departement_id'] = $departement->id;
            }
        }

    } catch (\Throwable $e) {

        // Gestion des erreurs IA (timeout, SSL, mauvaise réponse...)

        $data['category'] = 'Non classé';
        $data['priority'] = 'medium';
        $data['urgency'] = 3;
        $data['summary'] = substr($data['description'], 0, 100);

        $departement = Departement::firstOrCreate(
            ['nom' => 'Voirie'],
            ['description' => 'Département par défaut']
        );

        $data['departement_id'] = $departement->id;
    }

    // Sauvegarde
    $signalement = Signalement::create($data);

    return new SignalementResource($signalement);
}