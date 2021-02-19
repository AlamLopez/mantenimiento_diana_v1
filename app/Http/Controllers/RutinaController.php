<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Rutina;

class RutinaController extends Controller
{
    public function selectRutina(Request $request)
    {
        /**
         * Devuelve los roles registrados con condicion Activo
         */

        $rutinas = Rutina::select('id', 'nombre')->orderBy('id', 'asc')->get();

        return ['rutinas' => $rutinas];
        
    }
}
