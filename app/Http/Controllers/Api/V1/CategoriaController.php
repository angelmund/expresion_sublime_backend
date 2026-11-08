<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoriaController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $categorias = Categoria::select('id', 'nombre', 'descripcion', 'activo', 'slug', 'imagen')->get();
        $categorias->transform(function ($categoria) {
            if ($categoria->imagen) {
                $categoria->imagen = asset('storage/' . $categoria->imagen);
            }
            return $categoria;
        });
        return response()->json($categorias);
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240', // 10MB igual que en artículos
        ]);

        $data = $request->only(['nombre', 'descripcion']);
        $data['activo'] = filter_var($request->activo, FILTER_VALIDATE_BOOLEAN);
        $data['slug'] = Str::slug($request->nombre);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('categorias', 'public');
        }

        $categoria = Categoria::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Categoría creada con éxito',
            'categoria' => $categoria
        ], 201);
    }

    public function show(Categoria $categoria)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        return response()->json(['status' => true, 'categoria' => $categoria], 200);
    }

    public function update(Request $request, Categoria $categoria)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
        ]);

        $data = $request->only(['nombre', 'descripcion']);
        if ($request->has('activo')) $data['activo'] = filter_var($request->activo, FILTER_VALIDATE_BOOLEAN);
        $data['slug'] = Str::slug($request->nombre ?? $categoria->nombre);

        if ($request->hasFile('imagen')) {
            if ($categoria->imagen) {
                Storage::disk('public')->delete(str_replace('storage/', '', $categoria->imagen));
            }
            $data['imagen'] = $request->file('imagen')->store('categorias', 'public');
        }

        $categoria->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Categoría actualizada correctamente',
            'categoria' => $categoria
        ], 200);
    }

    public function destroy(Categoria $categoria)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $categoria->activo = false;
        $categoria->save();

        return response()->json(['status' => true, 'message' => 'Categoría desactivada correctamente'], 200);
    }

    public function activar(Categoria $categoria)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $categoria->activo = true;
        $categoria->save();

        return response()->json(['status' => true, 'message' => 'Categoría activada correctamente'], 200);
    }
}