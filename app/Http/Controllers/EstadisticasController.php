<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EstadisticasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuario = Auth::user();
        // Obtenemos datos de sesión
        $nombre = $usuario->usuario;  // o $usuario->name si así se llama en tu tabla
        $id_user = $usuario->id;
        $sede = $usuario->sede->nombre;

        // Fecha actual (formato: día/mes/año)
        $fecha_actual = Carbon::now()->format('d/m/Y');

        // Retornamos la vista con los datos necesarios
        return view('pages.estadisticas.index', [
            'fecha_actual' => $fecha_actual,
            'nombre' => $nombre,
            'id_user' => $id_user,
            'sede' => $sede
        ]);
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
