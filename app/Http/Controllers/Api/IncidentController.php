<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Incident;
use Illuminate\Http\Request;



class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $incidents=Incident::with('signalements')->get();
        return response()->json($incidents);   }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre'=>'required|string|max:255',
            'description'=>'nullable|string',
        ]);
         $incident = Incident::create([
        'titre' => $request->titre,
        'description' => $request->description,
    ]);
 return response()->json([
        'message' => 'Incident créé avec succès.',
        'data' => $incident,
    ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Incident $incident)
    {
          $incident->load('signalements');

    return response()->json($incident);
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Incident $incident)
{
    $request->validate([
        'titre' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    $incident->update([
        'titre' => $request->titre,
        'description' => $request->description,
    ]);

    return response()->json([
        'message' => 'Incident mis a jour avec succès',
        'data' => $incident,
    ]);
}
    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Incident $incident)
{
    if ($incident->signalements()->exists()) {
        return response()->json([
            'message' => 'Impossible de supprimer un incident contenant des signalements'
        ], 403);
    }

    $incident->delete();

    return response()->json([
        'message' => 'Incident supprimee avec succès'
    ]);
}
}
