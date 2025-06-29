<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Database\QueryException;
use App\Models\Profesor;
use App\Models\Sede;

class ProfesorController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        try {
            $profesores = Profesor::paginate(10);
            $sedes = Sede::where('estado', 1)->get();
        } catch (QueryException $e) {
            $profesores = new LengthAwarePaginator(
                [],
                0,
                10,
                Paginator::resolveCurrentPage(),
                ['path' => Paginator::resolveCurrentPath()]
            );
            $sedes = collect(); // evita error si la DB falla
        }

        return view('pages.profesores.index', compact('profesores', 'sedes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
