<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commercant;

class CommercantController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom_entreprise' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'numero_siret' => 'required|string|unique:commercants,numero_siret',
            'email_responsable' => 'required|email|unique:commercants,email_responsable',
            'telephone' => 'required|string|max:20',
            'id_utilisateur' => 'required|integer|exists:users,id',
            'iban' => 'nullable|string|max:34',
            'site_web' => 'nullable|string|max:255',
            'image_url' => 'nullable|string|max:255'
        ]);

        $commercant = Commercant::create($request->all());

        return response()->json($commercant, 201);
    }

    public function index()
    {
        return response()->json(Commercant::all());
    }

    public function update(Request $request, $id)
    {
        $commercant = Commercant::findOrFail($id);

        $request->validate([
            'nom_entreprise' => 'sometimes|string|max:255',
            'adresse' => 'sometimes|string|max:255',
            'numero_siret' => 'sometimes|string',
            'email_responsable' => 'sometimes|email',
            'telephone' => 'sometimes|string|max:20',
            'id_utilisateur' => 'sometimes|integer|exists:users,id',
            'site_web' => 'sometimes|string|max:255',
            'image_url' => 'sometimes|string|max:255',
            'iban' => 'sometimes|string|max:34'
        ]);

        $commercant->update($request->all());

        return response()->json($commercant, 201);
    }

    public function destroy($id)
    {
        $commercant = Commercant::findOrFail($id);
        $commercant->delete();

        return response()->json(['message' => 'Commerçant supprimer'], 200);
    }
}
