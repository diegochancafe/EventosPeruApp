<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Usuario autenticado
        $user = $request->user();
        // Si el cliente intenta acceder, no se le permite
        if ($user->role === 'client') {
            return response()->json([
                'data' => [],
                'message' => 'MENSAJE DE SEGURIDAD: Los clientes no tienen permiso de ver los servicios',
                'status' => 'error'
            ], 403);
        }

        // Construir la consulta base
        $query = Category::query();

        // Si es provider, filtrar por su propio ID
        if ($user->role === 'provider') {
            $query->where('user_id', $user->id);
        }
        // Obtener los servicios
        $services = $query->get();

        // Obtener todos las categorias
        $data = [
            'data' => $services,
            'message' => 'Lista de categorias obtenidas con éxito',
            'status' => 'success'
        ];
        return response()->json($data, 200);
    }

    public function indexWithServices()
    {
        $categories = Category::with('services')->get();
        $data = [
            'data' => $categories,
            'message' => 'Lista de categorias obtenidas con éxito',
            'status' => 'success'
        ];
        return response()->json($data, 200);
    }

    // Store function
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'description' => 'nullable|string',
            ]);

            $user = $request->user();

            // Create the category
            $category = Category::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'created_at'  => now(),
                'user_id' => $user->id,
            ]);

            // Success response
            return response()->json([
                'data' => $category,
                'message' => 'Categoría creada con éxito',
                'status' => 'success'
            ], 201);
        } catch (ValidationException $e) {
            // Error validation
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'status' => 'error',
            ], 422);
        } catch (\Exception $e) {
            // Other errors
            return response()->json([
                'message' => 'Error al crear la categoría',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Show function
    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json([
                'data' => $category,
                'message' => 'Categoría obtenida con éxito',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Categoría no encontrada',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    // Update function
    public function update(Request $request, $id)
    {
        try {
            // Find the category by ID
            $category = Category::findOrFail($id);

            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
                'description' => 'nullable|string',
            ]);

            // Updated the category
            $category->update([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'updated_at'  => now()
            ]);

            // Success response
            return response()->json([
                'data' => $category,
                'message' => 'Categoría actualizada con éxito',
                'status' => 'success'
            ], 200);
        } catch (ValidationException $e) {
            // Error validation
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'status' => 'error',
            ], 422);
        } catch (\Exception $e) {
            // Other errors
            return response()->json([
                'message' => 'Error al actualizar la categoría',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Find the category by ID
            $category = Category::findOrFail($id);

            // Delete the category
            $category->delete();

            // Success response
            return response()->json([
                'message' => 'Categoría eliminada con éxito',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar la categoría',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
