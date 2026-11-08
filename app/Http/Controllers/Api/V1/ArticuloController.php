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
        // 1. Permitimos el acceso a 'admin' y a 'user'
        if (Auth::check() && Auth::user()->hasAnyRole(['admin', 'user'])) {
            $articulos = Articulo::with(['categoria', 'medidas'])->get();
            
            // Transformamos la ruta de la imagen en una URL completa para React
            $articulos->transform(function ($articulo) {
                if ($articulo->imagen) {
                    $articulo->imagen = asset('storage/' . $articulo->imagen);
                }
                return $articulo;
            });
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
        
        $articulos->transform(function ($articulo) {
            if ($articulo->imagen) {
                $articulo->imagen = asset('storage/' . $articulo->imagen);
            }
            return $articulo;
        });
        
        return response()->json($articulos);
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio_base' => 'required|numeric|gt:0',
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
            'status' => true,
            'message' => 'Producto creado con éxito',
            'articulo' => $articulo
        ], 201);
    }

    public function show(Articulo $articulo)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $articulo->load([
            'categoria:id,nombre',

            'medidas:id,articulo_id,nombre'
        ]);
        
        return response()->json(['status' => true, 'articulo' => $articulo], 200);
    } 

    public function update(Request $request, Articulo $articulo)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'precio_base' => 'required|numeric|gt:0',
            'categoria' => 'sometimes|required|exists:categorias,id',
            'imagen_muestra' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->only(['nombre', 'precio_base', 'descripcion']);
        if ($request->has('categoria')) $data['categoria_id'] = $request->categoria;
        if ($request->has('activo')) $data['activo'] = filter_var($request->activo, FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('imagen_muestra')) {
            if ($articulo->imagen) {
                Storage::disk('public')->delete(str_replace('storage/', '', $articulo->imagen));
            }
            $data['imagen'] = $request->file('imagen_muestra')->store('productos', 'public');
        }

        $articulo->update($data);

        // Agregamos 'status' => true y el código 200 explícito
        return response()->json([
            'status' => true,
            'message' => 'Producto actualizado correctamente',
            'articulo' => $articulo
        ], 200);
    }

    public function destroy(Articulo $articulo)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        
        if ($articulo->imagen) {
            Storage::disk('public')->delete($articulo->imagen);
        }
        $articulo->activo = false;
        $articulo->save();
        // $articulo->delete();
        
        return response()->json(['status' => true, 'message' => 'Producto eliminado correctamente'], 200);
    }

    public function activar(Articulo $articulo)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $articulo->activo = true;
        $articulo->save();

        return response()->json(['status' => true, 'message' => 'Producto activado correctamente'], 200);
    }
}