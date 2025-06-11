<?php

namespace App\Http\Controllers;

use App\Models\AnnoncePrestataire;
use App\Models\Prestataire;
use App\Models\Paiement;
use App\Models\Prestation;
use Illuminate\Http\Request;

class AnnoncePrestataireController extends Controller
{
    public function index()
    {
        return response()->json(AnnoncePrestataire::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'status' => 'required|integer',
            'id_prestataire' => 'required|exists:prestataires,id',
            'id_client' => 'nullable|exists:clients,id',
            'id_prestation' => 'nullable|exists:prestations,id',
        ]);

        $annonce = AnnoncePrestataire::create($validated);
        return response()->json($annonce, 201);
    }

    public function show($id)
    {
        $annonce = AnnoncePrestataire::findOrFail($id);
        return response()->json($annonce);
    }

    public function update(Request $request, $id)
    {
        $annonce = AnnoncePrestataire::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'date' => 'sometimes|date',
            'location' => 'sometimes|string|max:255',
            'status' => 'sometimes|integer',
            'id_prestataire' => 'required|exists:prestataires,id',
            'id_client' => 'nullable|exists:clients,id',
            'id_prestation' => 'exists:prestations,id',
        ]);

        $annonce->update($validated);
        return response()->json($annonce);
    }

    public function destroy($id)
    {
        $annonce = AnnoncePrestataire::findOrFail($id);
        $annonce->delete();
        return response()->json(null, 204);
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'id_client');
    }

    public function getPrestationsByClientId($id)
    {
        $prestations = AnnoncePrestataire::where('id_client', $id)->get();
        return response()->json($prestations);
    }

    public function getPrestationsByPrestataireId($id)
    {
        $prestations = AnnoncePrestataire::with('client')
            ->where('id_prestataire', $id)
            ->get();
    
        return response()->json($prestations);
    }

        public function getNonPayeesParPrestataire($id)
    {
        $prestations = AnnoncePrestataire::where('id_prestataire', $id)
            ->where('status', 2) // 2 = terminé
            ->whereNotIn('id', function ($query) {
                $query->select('id_prestation')->from('paiements');
            })
            ->get();

        return response()->json($prestations);
    }
}
