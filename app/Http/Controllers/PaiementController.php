<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Paiement;

class PaiementController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }

        $paiements = Paiement::where('id_utilisateur', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return response()->json($paiements);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['message' => 'Non authentifié'], 401);
        }
    
        $validated = $request->validate([
            'commandes' => 'required|array|min:1',
            'commandes.*.id' => 'required|integer',
            'commandes.*.type' => 'required|in:prestation,livraison',
        ]);
    
        $paiements = [];
    
        foreach ($validated['commandes'] as $commande) {
            if ($commande['type'] === 'prestation') {
                $prestation = \App\Models\AnnoncePrestataire::find($commande['id']);
                if (!$prestation) continue;
    
                $paiements[] = Paiement::create([
                    'montant' => $prestation->price,
                    'id_prestation' => $prestation->id,
                    'id_livraison' => null,
                    'id_utilisateur' => $user->id,
                    'status' => 0,
                ]);
            }
    
            if ($commande['type'] === 'livraison') {
                $livraison = \App\Models\Livraison::find($commande['id']);
                if (!$livraison) continue;
    
                $paiements[] = Paiement::create([
                    'montant' => $livraison->prix,
                    'id_prestation' => null,
                    'id_livraison' => $livraison->id,
                    'id_utilisateur' => $user->id,
                    'status' => 0,
                ]);
            }
        }
    
        return response()->json($paiements, 201);
    }
    
    

    public function update(Request $request, $id)
    {
        $paiement = Paiement::findOrFail($id);

        $validated = $request->validate([
            'montant' => 'sometimes|numeric',
            'id_prestation' => 'sometimes|integer',
            'id_livraison' => 'sometimes|integer',
            'status' => 'sometimes|integer',
        ]);

        $paiement->update($validated);

        return response()->json($paiement);
    }
}