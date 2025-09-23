<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
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

            // Create the category
            $category = Category::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
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

            // Update the category
            $category->update($validatedData);

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
