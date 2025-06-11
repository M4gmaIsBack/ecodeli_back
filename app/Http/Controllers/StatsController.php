<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;
use App\Models\Client;
use App\Models\Commercant;
use App\Models\Livreur;
use App\Models\Prestataire;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function userStats(Request $request)
    {
        $days = $request->query('days', 7);
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $users = Users::where('created_at', '>=', $startDate)
            ->select(DB::raw("DATE(created_at) as date"), DB::raw("COUNT(*) as count"))
            ->groupBy(DB::raw("DATE(created_at)"))
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($users);
    }

    public function distribution()
    {
        return response()->json([
            ['type' => 'Clients', 'value' => Client::count()],
            ['type' => 'Users', 'value' => Users::count()],
            ['type' => 'Commercants', 'value' => Commercant::count()],
            ['type' => 'Livreurs', 'value' => Livreur::count()],
            ['type' => 'Prestataires', 'value' => Prestataire::count()],
        ]);
    }
}
