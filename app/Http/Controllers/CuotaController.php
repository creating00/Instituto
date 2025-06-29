<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Cuota;

class CuotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Suponiendo que hay usuario autenticado
        $usuario = Auth::user();

        // Fecha actual
        $fecha_actual = Carbon::now()->format('Y-m-d');

        // Número de factura (ejemplo: obtener el siguiente correlativo)
        $ultimo = Cuota::orderByDesc('id')->first();
        $numeroFactura = $ultimo ? $ultimo->nroFactura + 1 : 1;

        // Sede y usuario (ejemplo simple, puedes ajustar)
        $sede = $usuario->sede ?? 'Desconocida';
        $id_user = $usuario->id;
        $nombre_user = $usuario->usuario;

        return view('pages.cuotas.index', compact(
            'fecha_actual',
            'numeroFactura',
            'sede',
            'id_user',
            'nombre_user'
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
