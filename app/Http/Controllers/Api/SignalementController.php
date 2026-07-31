<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSignalementRequest;
use App\Http\Requests\UpdateSignalementRequest;
use App\Http\Requests\UpdateSignalementStatusRequest;
use App\Http\Resources\SignalementResource;
use App\Models\Departement;
use App\Models\Signalement;
use App\Services\SignalementAnalyzer;
use Illuminate\Support\Facades\Gate;

class SignalementController extends Controller
{
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
        $analyzer = app(SignalementAnalyzer::class);
        $response = $analyzer->analyze($data['description']);

        // Convertir le JSON retourné par l'IA
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
                'haute', 'high'   => 'high',
                'moyenne', 'medium' => 'medium',
                'basse', 'low'    => 'low',
                default           => null,
            };

            // Urgency
            $data['urgency'] = match (strtolower($ai['urgency'] ?? '')) {
                'haute', 'high'   => 3,
                'moyenne', 'medium' => 2,
                'basse', 'low'    => 1,
                default           => null,
            };

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

        // Sauvegarder le signalement
        $signalement = Signalement::create($data);

        return new SignalementResource($signalement);
    }

    public function index()
    {
        $signalements = Signalement::where('user_id', auth()->id())
            ->latest()
            ->get();

        return SignalementResource::collection($signalements);
    }

    public function show(Signalement $signalement)
    {
        if ($signalement->user_id !== auth()->id()) {
            abort(403, "Vous n'êtes pas autorisé à consulter ce signalement.");
        }

        return new SignalementResource($signalement);
    }

    public function update(UpdateSignalementRequest $request, Signalement $signalement)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('signalements', 'public');
        }

        $signalement->update($data);

        return new SignalementResource($signalement);
    }

    public function updateStatus(UpdateSignalementStatusRequest $request, Signalement $signalement)
    {
        Gate::authorize('updateStatus', $signalement);

        $signalement->update([
            'status' => $request->status,
        ]);

        return new SignalementResource($signalement);
    }
}