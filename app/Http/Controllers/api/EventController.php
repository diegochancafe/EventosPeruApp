<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Service;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        try {
            // Validar los datos de la solicitud
            $validatedData = $request->validate([
                'title'        => 'required|string|max:255',
                'description' => 'required|string',
                'event_date'       => 'required|date',
                'start_time'       => 'required',
                'end_time'       => 'required',
                'event_address'       => 'required|string|max:255',
                'services' => 'array',
                'services.*' => 'exists:services,id'
            ]);
            // Obtain the authenticated user
            $user = $request->user();
            // Crear el evento
            $event = Event::create([
                'title'       => $validatedData['title'],
                'description' => $validatedData['description'],
                'event_date' => $validatedData['event_date'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'event_address' => $validatedData['event_address'],
                'client_id' => $user->id,
                'created_at'  => now(),
            ]);

            // Asociar servicios al evento si se proporcionan
            if (!empty($validatedData['services'])) {
                $event->services()->attach($validatedData['services']);
            }

            // Respuesta de éxito
            return response()->json([
                'data' => $event,
                'message' => 'Evento creado con éxito',
                'status' => 'success'
            ], 201);
        } catch (ValidationException $e) {
            // Error de validación
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            // Otro error
            return response()->json([
                'message' => 'Error al crear el evento',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
