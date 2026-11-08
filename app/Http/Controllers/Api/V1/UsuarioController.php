<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'user'])) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $usuarios = User::with('roles')->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_admin' => $user->hasRole('admin'),
                'activo' => $user->activo ?? true, // Ajusta si tu campo se llama 'activo'
                'created_at' => $user->created_at,
            ];
        });

        return response()->json($usuarios);
    }

    public function toggleRole($id)
    {
        // Solo un 'admin' puede cambiar roles
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $user = User::findOrFail($id);

        // Medida de seguridad: Evitar que el admin activo se quite sus propios permisos
        if ($user->id === Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'No puedes cambiar tu propio rol por seguridad.'
            ], 403);
        }

        if ($user->hasRole('admin')) {
            $user->syncRoles(['user']); // O 'cliente', según como llames a tu rol base
            $isAdmin = false;
        } else {
            $user->syncRoles(['admin']);
            $isAdmin = true;
        }

        return response()->json([
            'status' => true,
            'message' => 'Rol actualizado correctamente',
            'is_admin' => $isAdmin
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'is_admin' => 'required|boolean',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'activo' => true,
        ]);

        $user->assignRole($request->is_admin ? 'admin' : 'user');

        return response()->json([
            'status' => true,
            'message' => 'Usuario creado con éxito',
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('activo')) {
            $user->activo = filter_var($request->activo, FILTER_VALIDATE_BOOLEAN);
        }

        $user->save();

        if ($request->has('is_admin') && $user->id !== Auth::id()) {
            $user->syncRoles([$request->is_admin ? 'admin' : 'user']);
        }

        return response()->json([
            'status' => true,
            'message' => 'Usuario actualizado correctamente',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy($id)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            return response()->json(['status' => false, 'message' => 'No autorizado'], 403);
        }

        $user = User::findOrFail($id);
        
        if ($user->id === Auth::id()) {
            return response()->json(['status' => false, 'message' => 'No puedes desactivar tu propio usuario'], 403);
        }

        $user->activo = false;
        $user->save();

        return response()->json(['status' => true, 'message' => 'Usuario desactivado correctamente'], 200);
    }
}
