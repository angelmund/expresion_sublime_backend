<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ArticuloController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->hasRole('admin')) {
            $articulos = Articulo::with(['categoria', 'medidas'])->get();
            return response()->json($articulos);
        }

        $articulos = Articulo::select(
            'id', 'nombre', 'descripcion', 'precio_base', 'precio_descuento',
            'stock', 'activo', 'imagen', 'created_at', 'categoria_id'
        )
        ->with([
            'categoria:id,nombre',

            'medidas:id,nombre' 
        ])
        ->get();
        
        return response()->json($articulos);
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio_base' => 'required|numeric',
            'categoria' => 'required|exists:categorias,id',
            'imagen_muestra' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['nombre', 'precio_base', 'descripcion']);
        $data['categoria_id'] = $request->categoria;
        $data['activo'] = filter_var($request->activo, FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('imagen_muestra')) {
            $data['imagen'] = $request->file('imagen_muestra')->store('productos', 'public');
        }

        $articulo = Articulo::create($data);

        return response()->json([
            'message' => 'Producto creado con éxito',
            'articulo' => $articulo
        ], 201);
    }

    public function show(Articulo $articulo)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $articulo->load([
            'categoria:id,nombre',

            'medidas:id,articulo_id,nombre'
        ]);
        
        return response()->json($articulo);
    } 

    public function update(Request $request, Articulo $articulo)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'precio_base' => 'sometimes|required|numeric',
            'categoria' => 'sometimes|required|exists:categorias,id',
            'imagen_muestra' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['nombre', 'precio_base', 'descripcion']);
        if ($request->has('categoria')) $data['categoria_id'] = $request->categoria;
        if ($request->has('activo')) $data['activo'] = filter_var($request->activo, FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('imagen_muestra')) {
            if ($articulo->imagen) {
                Storage::disk('public')->delete($articulo->imagen);
            }
            $data['imagen'] = $request->file('imagen_muestra')->store('productos', 'public');
        }

        $articulo->update($data);

        return response()->json([
            'message' => 'Producto actualizado correctamente',
            'articulo' => $articulo
        ]);
    }

    public function destroy(Articulo $articulo)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'No autorizado'], 403);
        }

        // Limpieza extra: Borramos la imagen del disco si eliminamos el artículo
        if ($articulo->imagen) {
            Storage::disk('public')->delete($articulo->imagen);
        }

        $articulo->delete();
        
        return response()->json(null, 204);
    }
}