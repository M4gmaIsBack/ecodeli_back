<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prestataire;
use App\Models\AnnoncePrestataire;

class PrestataireController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'email' => 'required|email|unique:prestataires,email',
            'competences' => 'nullable|string',
            'statut_validation' => 'required|in:en attente,validé,rejeté',
            'id_utilisateur' => 'required|integer|exists:users,id',
            'iban' => 'nullable|string|max:34'
        ]);

        $prestataire = Prestataire::create($request->all());

        return response()->json($prestataire, 201);
    }

    public function index()
    {
        return response()->json(Prestataire::all());
    }
    
    public function update(Request $request, $id)
    {
        $prestataire = Prestataire::findOrFail($id);

        $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'adresse' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email',
            'competences' => 'sometimes|string',
            'statut_validation' => 'sometimes|in:en attente,validé,rejeté',
            'id_utilisateur' => 'sometimes|integer|exists:users,id',
            'iban' => 'sometimes|string|max:34'
        ]);

        $prestataire->update($request->all());

        return response()->json($prestataire, 201);
    }

    public function destroy($id)
    {
        $prestataire = Prestataire::findOrFail($id);
        $prestataire->delete();

        return response()->json(['message' => 'Prestataire supprimer'], 200);
    }
}
