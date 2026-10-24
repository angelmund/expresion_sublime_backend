<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProductos = Articulo::count();
        $totalCategorias = Categoria::count();
        $totalUsuarios = User::count();
        
        // Si no tienes una columna 'stock' real en la BD, puedes cambiar esto por 0
        // o calcularlo en base a otra condición (ej. Articulo::where('activo', false)->count())
        $productosBajoStock = Articulo::where('stock', '<=', 5)->count();

        return response()->json([
            'total_productos' => $totalProductos,
            'total_categorias' => $totalCategorias,
            'total_usuarios' => $totalUsuarios,
            'productos_bajo_stock' => $productosBajoStock,
        ]);
    }
}