<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\QueryException;

class CursoController extends Controller
{
    public function index()
    {
        try {
            $cursos = Curso::paginate(10);
        } catch (QueryException $e) {
            $cursos = new LengthAwarePaginator(
                [],      // items
                0,       // total
                10,      // por página
                Paginator::resolveCurrentPage(), // página actual
                ['path' => Paginator::resolveCurrentPath()]
            );
        }

        return view('pages.cursos.index', compact('cursos'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
