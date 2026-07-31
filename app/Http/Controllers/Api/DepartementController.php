<?php

namespace App\Http\Controllers\Api;

use App\Models\Departement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $departements = Departement::with('signalements')->get();

    return response()->json($departements);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    $request->validate([
        'nom' => 'required|string|max:255|unique:departements,nom',
        'description' => 'nullable|string',
    ]);

    $departement = Departement::create([
        'nom' => $request->nom,
        'description' => $request->description,
    ]);

    return response()->json([
        'message' => 'Département créé avec succès.',
        'data' => $departement,
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function show(Departement $departement)
{
    $departement->load('signalements');

    return response()->json($departement);
}

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Departement $departement)
{
    $request->validate([
        'nom' => 'required|string|max:255|unique:departements,nom,' . $departement->id,
        'description' => 'nullable|string',
    ]);

    $departement->update([
        'nom' => $request->nom,
        'description' => $request->description,
    ]);

    return response()->json([
        'message' => 'Département mis à jour avec succès.',
        'data' => $departement,
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departement $departement)
{
    $departement->delete();

    return response()->json([
        'message' => 'Département supprimé avec succès.'
    ]);
}
}
