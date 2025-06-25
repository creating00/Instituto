<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioApiController extends Controller
{
    // Listar usuarios (paginado)
    public function index()
    {
        $usuarios = Usuario::paginate(10);
        return response()->json($usuarios);
    }

    // Mostrar un usuario
    public function show($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        return response()->json($usuario);
    }

    // Crear usuario
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuario,correo',
            'usuario' => 'required|string|max:255|unique:usuario,usuario',
            'clave' => 'required|string|min:6',
            'estado' => 'required|boolean',
            'idsede' => 'required|integer',
            'idrol' => 'required|integer',
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'usuario' => $request->usuario,
            'clave' => bcrypt($request->clave),
            'estado' => $request->estado,
            'idsede' => $request->idsede,
            'idrol' => $request->idrol,
        ]);

        return response()->json($usuario, 201);
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuario,correo,' . $id . ',idusuario',
            'usuario' => 'required|string|max:255|unique:usuario,usuario,' . $id . ',idusuario',
            'clave' => 'nullable|string|min:6',
            'estado' => 'required|boolean',
            'idsede' => 'required|integer',
            'idrol' => 'required|integer',
        ]);

        $usuario->nombre = $request->nombre;
        $usuario->correo = $request->correo;
        $usuario->usuario = $request->usuario;
        $usuario->estado = $request->estado;
        $usuario->idsede = $request->idsede;
        $usuario->idrol = $request->idrol;

        if ($request->filled('clave')) {
            $usuario->clave = bcrypt($request->clave);
        }

        $usuario->save();

        return response()->json($usuario);
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $usuario = Usuario::find($id);
        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        $usuario->delete();
        return response()->json(['message' => 'Usuario eliminado']);
    }
}