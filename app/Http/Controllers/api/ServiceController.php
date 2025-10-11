<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    // List all services (auth required)
    public function index(Request $request)
    {
        // Usuario autenticado
        $user = $request->user();

        // Si el cliente intenta acceder, no se le permite
        if ($user->role === 'client') {
            return response()->json([
                'data' => [],
                'message' => 'Los clientes no pueden ver los servicios',
                'status' => 'error'
            ], 403);
        }

        // Construir la consulta base
        $query = Service::with(['user', 'category']);

        // Si es provider, filtrar por su propio ID
        if ($user->role === 'provider') {
            $query->where('user_id', $user->id);
        }

        // Obtener los servicios
        $services = $query->get();

        return response()->json([
            'data' => $services,
            'message' => 'Lista de servicios obtenida con éxito',
            'status' => 'success'
        ], 200);
    }

    // List all services for an event (no auth required)
    public function listForEvent()
    {
        // Obtener todos los servicios con sus relaciones de usuario y categoría
        $service = Service::with(['user', 'category'])->get();

        return response()->json([
            'data' => $service,
            'message' => 'Lista de servicios obtenida con éxito',
            'status' => 'success'
        ], 200);
    }

    public function show($id)
    {
        try {
            // Buscar el servicio por ID con sus relaciones de usuario y categoría
            $service = Service::with(['user', 'category'])->findOrFail($id);

            return response()->json([
                'data' => $service,
                'message' => 'Servicio obtenido con éxito',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener el servicio',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Create a new service
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'title'        => 'required|string|max:255',
                'description' => 'required|string',
                'price'       => 'required|numeric',
                'category_id' => 'required|exists:categories,id'
            ]);
            // Obtain the authenticated user
            $user = $request->user();
            // Create the service
            $service = Service::create([
                'title'       => $validatedData['title'],
                'description' => $validatedData['description'],
                'price'       => $validatedData['price'],
                'category_id' => $validatedData['category_id'],
                'user_id'     => $user->id, // Usar el ID del usuario autenticado
                'created_at'  => now(),
            ]);
            // Success response
            return response()->json([
                'data' => $service,
                'message' => 'Servicio creado con éxito',
                'status' => 'success',
            ], 201);
        } catch (ValidationException $e) {
            // Error validation
            return response()->json([
                'message' => $e->getMessage(),
                'errors'  => $e->errors(),
                'status'  => 'error',
            ], 422);
        } catch (\Exception $e) {
            // Other errors
            return response()->json([
                'message' => 'Error al crear el servicio',
                'status'  => 'error',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'title'        => 'sometimes|required|string|max:255',
                'description' => 'sometimes|required|string',
                'price'       => 'sometimes|required|numeric',
                'category_id' => 'sometimes|required|exists:categories,id'
            ]);

            // Find the service by ID
            $service = Service::findOrFail($id);
            $user = $request->user();
            // Update the service with validated data
            $service->update([
                'title'       => $validatedData['title'],
                'description' => $validatedData['description'],
                'price'       => $validatedData['price'],
                'category_id' => $validatedData['category_id'],
                'updated_at'  => now(),
            ]);

            return response()->json([
                'data' => $service,
                'message' => 'Servicio actualizado con éxito',
                'status' => 'success',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'status' => 'error',
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el servicio',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Delete a service
    public function destroy($id)
    {
        try {
            $user = Service::findOrFail($id);
            $user->delete();
            return response()->json([
                'message' => 'Servicio eliminado con éxito',
                'status' => 'success',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el servicio',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
