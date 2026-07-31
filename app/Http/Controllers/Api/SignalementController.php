<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Signalement;
use App\Models\Departement;
use App\Http\Requests\StoreSignalementRequest;
use App\Http\Resources\SignalementResource;
use App\Services\SignalementAnalyzer;
use Illuminate\Http\Request;

class SignalementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $signalements = Signalement::with(['departement', 'incident', 'user'])->get();
        return SignalementResource::collection($signalements);
    }

    /**
     * Store a newly created resource in storage.
     */
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

                // Department (أسبقية للـ Request، وإلا ياخد ديال الـ AI)
                if (empty($data['departement_id']) && !empty($ai['department'])) {
                    $departement = Departement::firstOrCreate(
                        ['nom' => $ai['department']],
                        ['description' => null]
                    );

                    $data['departement_id'] = $departement->id;
                }

                // Incident Association (أسبقية للـ Request، وإلا ياخد ديال الـ AI)
                if (empty($data['incident_id']) && !empty($ai['incident_id'])) {
                    $data['incident_id'] = $ai['incident_id'];
                }
            }

        } catch (\Throwable $e) {

            // Fallback فـ حالة وقع مشكل فـ الـ AI
            $data['category'] = 'Non classé';
            $data['priority'] = 'medium';
            $data['urgency'] = 3;
            $data['summary'] = substr($data['description'], 0, 100);

            if (empty($data['departement_id'])) {
                $departement = Departement::firstOrCreate(
                    ['nom' => 'Voirie'],
                    ['description' => 'Département par défaut']
                );

                $data['departement_id'] = $departement->id;
            }
        }

        // Sauvegarde
        $signalement = Signalement::create($data);

        return new SignalementResource($signalement);
    }

    /**
     * Display the specified resource.
     */
    public function show(Signalement $signalement)
    {
        $signalement->load(['departement', 'incident', 'user']);
        return new SignalementResource($signalement);
    }

    /**
     * Update status or associate incident.
     */
    public function updateStatus(Request $request, Signalement $signalement)
    {
        $request->validate([
            'status' => 'sometimes|string',
            'incident_id' => 'nullable|exists:incidents,id',
        ]);

        $signalement->update($request->only(['status', 'incident_id']));

        return response()->json([
            'message' => 'Signalement mis à jour avec succès.',
            'data' => new SignalementResource($signalement)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Signalement $signalement)
    {
        $signalement->delete();

        return response()->json([
            'message' => 'Signalement supprimé avec succès.'
        ]);
    }
}