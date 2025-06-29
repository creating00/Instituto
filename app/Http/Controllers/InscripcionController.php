<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inscripcion;
use App\Models\Sala;
use App\Models\Alumno;
use Illuminate\Support\Facades\Auth;

class InscripcionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // InscripcionController.php
    public function index()
    {
        $usuario = Auth::user()->usuario;
        $fecha_actual = now()->format('Y-m-d');
        $numeroFactura = 'FACT-' . rand(1000, 9999);
        $sede = Auth::user()->sede->nombre ?? 'GENERAL';
        $alumnos = Alumno::where('estado', 1)->get(); // o tu lógica específica

        // Filtrar solo las inscripciones activas (si aplica)
        $inscripciones = Inscripcion::with(['alumno', 'curso.profesor', 'sede'])
            ->when($sede !== 'GENERAL', fn($q) => $q->whereHas('sede', fn($q) => $q->where('nombre', $sede)))
            ->latest('id')
            ->get();

        // Asegúrate de filtrar salas activas (si usas estado)
        $salas = Sala::where('estado', 1)->get(); // Asumiendo que hay un modelo Sala

        return view('pages.inscripcion.index', compact(
            'usuario',
            'fecha_actual',
            'numeroFactura',
            'sede',
            'inscripciones',
            'salas',
            'alumnos'
        ));
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
