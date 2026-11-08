<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\EstatusVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstatusVentaController extends Controller
{
    public function index()
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $estatus = EstatusVenta::select('id', 'nombre', 'color', 'activo')->get();
        return response()->json($estatus);
    }

    public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'color'  => 'required|string|max:50',
        ]);

        $data = $request->only(['nombre', 'color']);
        $data['activo'] = filter_var($request->activo, FILTER_VALIDATE_BOOLEAN);

        $estatus = EstatusVenta::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Estatus creado con éxito',
            'estatus' => $estatus
        ], 201);
    }

    public function show(EstatusVenta $estatusVenta)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        return response()->json(['status' => true, 'estatus' => $estatusVenta], 200);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $estatusVenta = EstatusVenta::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'color'  => 'sometimes|required|string|max:50',
        ]);

        $data = $request->only(['nombre', 'color']);
        if ($request->has('activo')) $data['activo'] = filter_var($request->activo, FILTER_VALIDATE_BOOLEAN);

        $estatusVenta->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Estatus actualizado correctamente',
            'estatus' => $estatusVenta
        ], 200);
    }

    public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $estatusVenta = EstatusVenta::findOrFail($id);
        $estatusVenta->activo = false;
        $estatusVenta->save();

        return response()->json(['status' => true, 'message' => 'Estatus desactivado correctamente'], 200);
    }

    public function activar($id)
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $estatusVenta = EstatusVenta::findOrFail($id);
        $estatusVenta->activo = true;
        $estatusVenta->save();

        return response()->json(['status' => true, 'message' => 'Estatus activado correctamente'], 200);
    }
}