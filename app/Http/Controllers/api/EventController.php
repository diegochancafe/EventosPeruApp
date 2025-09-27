<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Service;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    public function index()
    {
        // Obtener todos los eventos con sus relaciones de usuario y categoría
        $service = Event::with(['client', 'services'])->get();

        return response()->json([
            'data' => $service,
            'message' => 'Lista de eventos obtenida con éxito',
            'status' => 'success'
        ], 200);
    }

    public function show($id)
    {
        $event = Event::with(['client', 'services'])->find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Evento no encontrado',
                'status' => 'error'
            ], 404);
        }

        return response()->json([
            'data' => $event,
            'message' => 'Información del evento obtenida con éxito',
            'status' => 'success'
        ], 200);
    }
}
