<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Auth;

class GananciasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $anio = now()->year;
        $usuario = Auth::user();

        if ($usuario->sede && $usuario->sede->nombre === 'GENERAL') {
            $usuarios = Usuario::with('sede')->orderBy('usuario')->get();
        } else {
            $usuarios = Usuario::with('sede')
                ->where('idsede', $usuario->idsede)
                ->orderBy('usuario')
                ->get();
        }

        return view('pages.ganancias.index', compact('usuarios', 'anio'));
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
