<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Log;

class ClientController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'id_utilisateur' => 'required|integer|exists:users,id'
        ]);

        $client = Client::create($request->all());

        return response()->json($client, 201);
    }

    public function index()
    {
        return response()->json(Client::all());
    }

    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'nom' => 'sometimes|string|max:255',
            'prenom' => 'sometimes|string|max:255',
            'adresse' => 'sometimes|string|max:255',
            'telephone' => 'sometimes|string|max:20',
            'id_utilisateur' => 'sometimes|integer|exists:users,id'
        ]);
    
        $client->update($request->all());
    
        return response()->json($client, 201);
    }
    
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();
    
        return response()->json(['message' => 'Client supprimer'], 200);
    }

    public function getByUserId($id_utilisateur)
    {
        $client = Client::where('id_utilisateur', $id_utilisateur)->first();

        if (!$client) {
            return response()->json(['message' => 'Client non trouvé'], 404);
        }

        return response()->json($client);
    }

}
