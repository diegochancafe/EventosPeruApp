<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $data = [
            'data' => $users,
            'message' => 'Lista de usuarios obtenida con éxito',
            'status' => 'success'
        ];
        return response()->json($data, 200);
    }

    // Login function
    public function login(Request $request)
    {
        try {
            // Validate the request data
            $credentials = $request->only('email', 'password');

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Credenciales inválidas',
                    'status' => 'error'
                ], 401);
            }
            // User authenticated
            $user = Auth::user();

            // Create token
            /** @var \App\Models\User $user */
            $token = $user->createToken('auth_token')->plainTextToken;

            // Success response
            return response()->json([
                'data' => $user,
                'token' => $token,
                'status' => 'success',
                'message' => 'Login exitoso',


            ], 200);
        } catch (\Exception $e) {
            // Other errors
            return response()->json([
                'message' => 'Error al crear el usuario',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    // Logout function
    public function logout(Request $request)
    {
        // Revoke the token that was used to authenticate the current request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout exitoso',
            'status' => 'success'
        ], 200);
    }

    // Store function to create a new user
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);
            // Create the user
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);
            // Success response
            return response()->json([
                'data' => $user,
                'message' => 'Usuario creado con éxito',
                'status' => 'success',
            ], 201);
        } catch (ValidationException $e) {
            // Error validation
            return response()->json([
                // 'message' => 'Error de validación',
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
                'status' => 'error',
            ], 422);
        } catch (\Exception $e) {
            // Other errors
            return response()->json([
                'message' => 'Error al crear el usuario',
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
