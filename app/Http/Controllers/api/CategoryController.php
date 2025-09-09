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
}