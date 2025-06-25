<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Ejecuta las consultas que necesitas usando DB facade (query builder)
        // $totalU = DB::table('usuario')->count();
        // $totalA = DB::table('alumno')->count();
        // $totalC = DB::table('curso')->count();
        // $totalP = DB::table('profesor')->count();
        // $totalSa = DB::table('sala')->count();

        $totalU = 0;
        $totalA = 0;
        $totalC = 0;
        $totalP = 0;
        $totalSa = 0;

        // Pasa esas variables a la vista
        return view('dashboard', compact('totalU', 'totalA', 'totalC', 'totalP', 'totalSa'));
    }
}
