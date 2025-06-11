<?php

namespace App\Http\Controllers;

use App\Models\Livraison;
use Illuminate\Http\Request;
use App\Models\Entrepot;

class LivraisonController extends Controller
{

    public function index()
    {
        return response()->json(Livraison::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_livraison' => 'required|string|max:255',
            'description' => 'required|string',
            'poids' => 'required|numeric',
            'taille' => 'required|string',
            'adresse_depart' => 'required|string',
            'adresse_arrivee' => 'required|string',
            'instructions' => 'nullable|string',
            'status' => 'required|integer',
            'prix' => 'required|numeric',
            'id_client' => 'required|exists:clients,id',
            'id_livreur' => 'nullable|exists:livreurs,id',

        ]);
    
        $livraison = Livraison::create($validated);
        return response()->json($livraison, 201);
    }

    public function update(Livraison $livraison)
    {
        //
    }

public function accept(Request $request, Livraison $livraison)
{
    $request->validate([
        'id_livreur' => 'required|exists:livreurs,id',
        'entrepot_id' => 'nullable|exists:entrepots,id',
    ]);

    $livraison->id_livreur = $request->input('id_livreur');

    if ($request->has('entrepot_id')) {
        $livraison->entrepot_id = $request->input('entrepot_id');
    } else {
        $livraison->entrepot_id = null;
    }

    $livraison->status = 1; // 1 = en cours

    $livraison->save();

    return response()->json($livraison);
}



    public function valider(Livraison $livraison)
    {
        $livraison->status = 2;
        $livraison->save();

        return response()->json([
            'message' => 'Livraison validée avec succès.',
            'livraison' => $livraison
        ]);
    }


    public function validerPartiel(Livraison $livraison)
    {
        if (!$livraison->entrepot_id) {
            return response()->json([
                'error' => 'Aucun entrepôt associé à cette livraison.'
            ], 400);
        }

        $entrepot = Entrepot::find($livraison->entrepot_id);
        if (!$entrepot) {
            return response()->json([
                'error' => 'Entrepôt introuvable.'
            ], 404);
        }

        $livraison->adresse_depart = $entrepot->address;
        $livraison->status = 0; // retour à "en attente"
        $livraison->save();

        return response()->json([
            'message' => 'Livraison partiellement validée. Prête à être reprise depuis l’entrepôt.',
            'livraison' => $livraison
        ]);
    }

    public function destroy(Livraison $livraison)
    {
        $livraison->delete();
        return response()->json(null, 204);
    }

        public function getLivraisonsByClientId($id)
    {
        $annonces = Livraison::where('id_client', $id)->get();
        return response()->json($annonces);
    }

    public function getLivraisonsByLivreurId($id)
    {
        $annonces = Livraison::where('id_livreur', $id)->get();
        return response()->json($annonces);
    }

    public function getNonPayeesParLivreur($id)
    {
        $livraisons = Livraison::where('id_livreur', $id)
            ->where('status', 2) // 2 = terminé
            ->whereNotIn('id', function ($query) {
                $query->select('id_livraison')->from('paiements');
            })
            ->get();

        return response()->json($livraisons);
    }
}
