<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
  public function index()
  {
    // Obtener todos los servicios con sus relaciones de usuario y categoría
    $service = Service::with(['user', 'category'])->get();

    return response()->json([
      'data' => $service,
      'message' => 'Lista de usuarios obtenida con éxito',
      'status' => 'success'
    ], 200);
  }
}
