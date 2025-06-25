<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    // Mostrar lista de usuarios
    public function index()
    {
        $usuarios = Usuario::paginate(10);
        // De momento comentamos estas dos líneas si no tienes modelos Sede ni Rol todavía
        // $sedes = Sede::all();
        // $roles = Rol::all();

        // Luego, cuando los tengas, los pasas a la vista
        // return view('pages.usuarios.index', compact('usuarios', 'sedes', 'roles'));

        return view('pages.usuarios.index', compact('usuarios'));
    }

    // Mostrar formulario para crear usuario
    public function create()
    {
        return view('pages.usuarios.create');
    }

    // Guardar nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuario,correo',
            'usuario' => 'required|string|max:255|unique:usuario,usuario',
            'clave' => 'required|string|min:6|confirmed', // Confirmed requiere campo clave_confirmation
            'estado' => 'required|boolean',
            'idsede' => 'required|integer',
            'idrol' => 'required|integer',
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'usuario' => $request->usuario,
            'clave' => bcrypt($request->clave),
            'estado' => $request->estado,
            'idsede' => $request->idsede,
            'idrol' => $request->idrol,
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    // Mostrar formulario para editar usuario
    public function edit($idusuario)
    {
        $usuario = Usuario::findOrFail($idusuario);
        // $usuario = Usuario::with('sede')->findOrFail($id);
        // $roles = Rol::all();
        // $sedes = Sede::all();
        // return view('usuarios.edit', compact('usuario', 'roles', 'sedes'));
        return view('pages.usuarios.edit', compact('usuario'));
    }

    // Actualizar usuario
    public function update(Request $request, $idusuario)
    {
        $usuario = Usuario::findOrFail($idusuario);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|unique:usuario,correo,' . $usuario->idusuario . ',idusuario',
            'usuario' => 'required|string|max:255|unique:usuario,usuario,' . $usuario->idusuario . ',idusuario',
            'clave' => 'nullable|string|min:6|confirmed', // clave puede quedar vacía si no se cambia
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

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // Eliminar usuario
    public function destroy($idusuario)
    {
        $usuario = Usuario::findOrFail($idusuario);
        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
