<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrepot;

class EntrepotController extends Controller
{
    public function index()
    {
        return response()->json(Entrepot::all());   
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:entrepot,name',
            'address' => 'nullable|string|max:255',
            'max_capacity' => 'required|integer|min:0',
            'current_capacity' => 'required|integer|min:0',
        ]);

        $entrepot = Entrepot::create($validated);
        return response()->json($entrepot, 201);
    }

    public function update(Request $request, Entrepot $entrepot)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:entrepot,name,' . $entrepot->id,
            'address' => 'nullable|string|max:255',
            'max_capacity' => 'sometimes|required|integer|min:0',
            'current_capacity' => 'sometimes|required|integer|min:0',
        ]);

        $entrepot->update($validated);
        return response()->json($entrepot);
    }

    public function delete(Entrepot $entrepot)
    {
        $entrepot->delete();
        return response()->json(null, 204);
    }
}
