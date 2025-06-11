<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\ReponseTicket;

class ReponseTicketController extends Controller
{
    public function index()
    {
        return response()->json(ReponseTicket::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'ticket_id' => 'required|exists:tickets,id',
            'utilisateur_id' => 'required|exists:users,id',
            'administrateur_id' => 'nullable|exists:administrateurs,id',
        ]);

        $reponseTicket = ReponseTicket::create($validated);
        return response()->json($reponseTicket, 201);
    }

    public function update(Request $request, $id)
    {
        $reponseTicket = ReponseTicket::findOrFail($id);

        $validated = $request->validate([
            'message' => 'sometimes|string',
            'ticket_id' => 'sometimes|exists:tickets,id',
            'utilisateur_id' => 'sometimes|exists:users,id',
            'administrateur_id' => 'sometimes|exists:administrateurs,id',
        ]);

        $reponseTicket->update($validated);
        return response()->json($reponseTicket);
    }

    public function destroy($id)
    {
        $reponseTicket = ReponseTicket::findOrFail($id);
        $reponseTicket->delete();
        return response()->json(null, 204);
    }

    public function getReponsesByTicketId($id)
    {
        $reponses = ReponseTicket::where('ticket_id', $id)->get();
        return response()->json($reponses);
    }
}
