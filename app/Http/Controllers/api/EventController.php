<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade\Pdf; // PDF
use Illuminate\Support\Facades\Mail; // Email
use App\Mail\EventPdfMail; // Mailable

class EventController extends Controller
{
    public function index(Request $request)
    {
        // Obtain the authenticated user
        $user = $request->user();

        // Base query
        $query = Event::with(['client', 'services']);

        // Filtrar según el rol
        if ($user->role !== 'admin') {
            // Provider o Client: solo sus eventos
            $query->where('client_id', $user->id);
        }

        // Obtener eventos y mapear estado en texto
        $events = $query->get()->map(function ($event) {
            $statusDescriptions = [
                'pending'   => 'Pendiente',
                'confirmed' => 'Confirmado',
                'finished'  => 'Completado',
                'canceled'  => 'Cancelado',
            ];

            $event->status_description = $statusDescriptions[$event->status] ?? 'Desconocido';
            return $event;
        });

        return response()->json([
            'data' => $events,
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
                'services' => 'array|required',
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

            // Adjuntar los servicios
            if (!empty($validatedData['services'])) {
                $event->services()->attach($validatedData['services']);
            }

            // *** GENERAR PDF ***
            $pdf = PDF::loadView('pdf.event', compact('event'))->output();

            // *** ENVIAR CORREO ***
            Mail::to($event->client->email)->send(new EventPdfMail($event, $pdf));

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

    public function downloadPdf($id)
    {
        $event = Event::with(['client', 'services'])->findOrFail($id);

        $pdf = PDF::loadView('pdf.event', compact('event'));

        // // 1. Generar el PDF
        // $pdf = PDF::loadView('pdf.event', compact('event'));
        // $pdfContent = $pdf->output();

        // // 2. Enviar el correo con el PDF adjunto
        // Mail::to("chancaferonaldo78@gmail.com")->send(
        //     new EventPdfMail($event, $pdfContent)
        // );

        return $pdf->download("evento_{$event->id}.pdf");
    }

    public function update(Request $request, $id)
    {
        try {
            $event = Event::findOrFail($id);
            // Validar los datos de la solicitud
            $validatedData = $request->validate([
                'title'        => 'required|string|max:255',
                'description' => 'required|string',
                'event_date'       => 'required|date',
                'start_time'       => 'required',
                'end_time'       => 'required',
                'event_address'       => 'required|string|max:255',
                'status' => 'required|string|in:pending,confirmed,completed,canceled',
                'services' => 'array|required',
                'services.*' => 'exists:services,id'
            ]);
            // Obtain the authenticated user
            $user = $request->user();
            // Actualizar el evento
            $event->update([
                'title'       => $validatedData['title'],
                'description' => $validatedData['description'],
                'event_date' => $validatedData['event_date'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'event_address' => $validatedData['event_address'],
                'status' => $validatedData['status'],
                'client_id' => $user->id,
                'updated_at'  => now(),
            ]);

            // Sincronizar servicios si se proporcionan
            if (isset($validatedData['services'])) {
                $event->services()->sync($validatedData['services']);
            }

            // Respuesta de éxito
            return response()->json([
                'data' => $event,
                'message' => 'Evento actualizado con éxito',
                'status' => 'success'
            ], 200);
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
                'message' => 'Error al actualizar el evento',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->services()->detach(); // Desasociar servicios
            $event->delete();

            return response()->json([
                'message' => 'Evento eliminado con éxito',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el evento',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
