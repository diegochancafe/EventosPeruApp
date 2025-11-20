<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Validation\ValidationException;

class HomeController extends Controller
{
    public function getServicesUsage(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'client') {
            return response()->json([
                'data' => [],
                'message' => 'Los clientes no pueden ver estadísticas',
                'status' => 'error'
            ], 403);
        }

        $query = Service::select('id', 'title') // <-- agrega title
            ->withCount('events')
            ->has('events')
            ->orderByDesc('events_count');

        if ($user->role === 'provider') {
            $query->where('user_id', $user->id);
        }

        $services = $query->get();

        return response()->json([
            'data' => [
                'labels' => $services->pluck('title'),
                'series' => $services->pluck('events_count')
            ],
            'message' => 'Estadísticas obtenidas con éxito',
            'status' => 'success'
        ], 200);
    }

    public function getEventsTotalByStatus(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'client') {
            return response()->json([
                'data' => [],
                'message' => 'Los clientes no pueden ver estadísticas',
                'status' => 'error'
            ], 403);
        }

        $states = ['pending', 'confirmed', 'canceled', 'finished'];

        $events = Event::with('services:id,price')
            ->select('id', 'status')
            ->when($user->role === 'provider', function ($query) use ($user) {
                $query->whereHas('services', function ($q) use ($user) {
                    $q->where('services.user_id', $user->id);
                });
            })
            ->get();

        // Totales por estado
        $totals = [];
        foreach ($states as $state) {
            $totals[$state] = 0;
        }

        foreach ($events as $event) {
            $totals[$event->status] += $event->services->sum('price');
        }

        // Calcular porcentajes
        $totalGeneral = array_sum($totals);
        $percentages = [];

        foreach ($states as $state) {
            $percentages[$state] = $totalGeneral > 0
                ? round(($totals[$state] / $totalGeneral) * 100, 2)
                : 0;
        }

        return response()->json([
            'data' => $totals,
            'percentages' => $percentages,
            'message' => 'Totales obtenidos con éxito',
            'status' => 'success'
        ], 200);
    }
}
