<?php

namespace App\Http\Controllers;

use App\Models\Prestation;
use Illuminate\Http\Request;

class PrestationController extends Controller
{
    public function index()
    {
        return response()->json(Prestation::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'location' => 'required|string|max:255',
            'status' => 'required|integer',
            'id_prestataire' => 'required|exists:prestataires,id',
        ]);

        $prestation = Prestation::create($validated);
        return response()->json($prestation, 201);
    }

    public function show($id)
    {
        $prestation = Prestation::findOrFail($id);
        return response()->json($prestation);
    }

    public function update(Request $request, $id)
    {
        $prestation = Prestation::findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'location' => 'sometimes|string|max:255',
            'status' => 'sometimes|integer',
            'id_prestataire' => 'sometimes|exists:prestataires,id',
        ]);

        $prestation->update($validated);
        return response()->json($prestation);
    }

    public function destroy($id)
    {
        $prestation = Prestation::findOrFail($id);
        $prestation->delete();
        return response()->json(null, 204);
    }
    

    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'id_client');
    }


}
