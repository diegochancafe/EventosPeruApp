<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RatingController extends Controller
{
    // Obtener todas las calificaciones
    public function index()
    {
        $ratings = Rating::with(['event', 'client'])->get();

        return response()->json([
            'data' => $ratings,
            'message' => 'Lista de calificaciones obtenidas con éxito',
            'status' => 'success'
        ], 200);
    }

    // Crear una calificación
    public function store(Request $request)
    {
        try {
            // Obtain the authenticated user
            $user = $request->user();

            $validated = $request->validate([
                'event_id' => ['required', 'exists:events,id'],
                'score' => ['required', 'integer', 'between:1,5'],
                'comment' => ['nullable', 'string', 'max:255'],
            ]);

            $rating = Rating::create([
                'event_id' => $validated['event_id'],
                'client_id' => $user->id,
                'score' => $validated['score'],
                'comment' => $validated['comment'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'rating' => $rating,
                'message' => 'Calificación creada con éxito',
                'status' => 'success'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'status' => 'error',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la calificación',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Mostrar una calificación
    public function show($id)
    {
        try {
            $rating = Rating::with(['event', 'client'])->findOrFail($id);

            return response()->json([
                'data' => $rating,
                'message' => 'Calificación obtenida con éxito',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Calificación no encontrada',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    // Actualizar calificación
    public function update(Request $request, $id)
    {
        try {
            $rating = Rating::findOrFail($id);

            $validated = $request->validate([
                'event_id' => ['required', 'exists:events,id'],
                'score' => ['required', 'integer', 'between:1,5'],
                'comment' => ['nullable', 'string', 'max:255'],
            ]);

            $rating->update($validated);

            return response()->json([
                'data' => $rating,
                'message' => 'Calificación actualizada con éxito',
                'status' => 'success'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'status' => 'error',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar la calificación',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Eliminar calificación
    public function destroy($id)
    {
        try {
            $rating = Rating::findOrFail($id);
            $rating->delete();

            return response()->json([
                'message' => 'Calificación eliminada con éxito',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la calificación',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
