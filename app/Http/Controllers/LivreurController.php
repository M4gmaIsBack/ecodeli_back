<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livreur;
use App\Models\DocumentJustificatif;
use App\Models\Users;

class LivreurController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'statut_validation' => 'required|in:en attente,validé,rejeté',
            'date_verification' => 'nullable|date',
            'authentification_2FA' => 'boolean',
            'iban' => 'nullable|string|max:34',
            'id_utilisateur' => 'required|integer|exists:users,id',
        ]);

        $livreur = Livreur::create($request->all());

        return response()->json($livreur, 201);
    }

    public function index()
    {
        return response()->json(Livreur::all());
    }
    public function update(Request $request, $id)
    {
        $livreur = Livreur::findOrFail($id);

        $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'adresse' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:20',
            'statut_validation' => 'sometimes|in:en attente,validé,rejeté',
            'date_verification' => 'nullable|date',
            'iban' => 'nullable|string|max:34',
            'authentification_2FA' => 'boolean',
            'id_utilisateur' => 'sometimes|integer|exists:users,id'
        ]);

        $livreur->update($request->all());

        return response()->json($livreur, 201);
    }

    public function destroy($id)
    {
        $livreur = Livreur::findOrFail($id);
        $livreur->delete();

        return response()->json(['message' => 'Livreur supprimer'], 200);
    }

    public function documents($id)
    {
        // 1) On récupère le livreur
        $livreur = Livreur::findOrFail($id);
    
        // 2) On en déduit l'id_utilisateur
        $userId = $livreur->id_utilisateur;
    
        // 3) On va chercher ses documents
        $docs = DocumentJustificatif::where('user_id', $userId)->get();
    
        // 4) On renvoie tout pour debug
        return response()->json([
            'livreur_id'    => $livreur->id,
            'user_id'       => $userId,
            'docs_count'    => $docs->count(),
            'documents_raw' => $docs,
            'documents'     => $docs->map(fn($doc) => [
                'id'         => $doc->id,
                'filename'   => $doc->filename,
                'chemin'     => $doc->chemin,
                'created_at' => $doc->created_at->toDateTimeString(),
            ]),
        ]);
    }
}
