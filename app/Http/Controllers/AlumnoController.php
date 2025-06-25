<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alumno;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\QueryException;

class AlumnoController extends Controller
{
    public function index()
    {
        try {
            $alumnos = Alumno::paginate(10);
        } catch (QueryException $e) {
            // $alumnosInactivos = Alumno::where('estado', 'Inactivo')->get();
            $alumnosInactivos = collect(); // colección vacía
            $alumnos = new LengthAwarePaginator(
                [],      // items
                0,       // total
                10,      // por página
                Paginator::resolveCurrentPage(), // página actual
                ['path' => Paginator::resolveCurrentPath()]
            );
        }

        return view('pages.alumnos.index', compact('alumnos', 'alumnosInactivos'));
    }

    public function edit($id)
    {
        $alumno = Alumno::findOrFail($id);
        return view('pages.alumnos.edit', compact('alumno'));
    }

    public function create()
    {
        return view('pages.alumnos.create');
    }

    public function store(Request $request)
    {
        // Validar y guardar alumno
    }

    public function update(Request $request, $id)
    {
        // Validar y actualizar alumno
    }

    public function destroy($id)
    {
        // Eliminar alumno
    }
}
