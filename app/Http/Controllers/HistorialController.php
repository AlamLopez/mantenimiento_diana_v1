<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Historial;

class HistorialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        /**
         * Desplegar el listado de actividades registrados en el sistema con paginacion
         * Se pueden hacer busquedas de datos segun el criterio que el usuario elija
         */

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $desde = $request->desde;
        $hasta = $request->hasta;

        if($desde != null){
            $desde = $desde . ' 00:00:00';
        }

        if($hasta != null){
            $hasta = $hasta . ' 23:59:59';
        }

        if($buscar == '' && $desde == '' && $hasta == ''){
            
            $historiales = Historial::select('users.nombre as usuario', 'historial.nombre_accion', 'historial.descripcion', 'historial.created_at as fecha')
                                    ->join('users', 'users.id', '=', 'historial.id_user')
                                    ->orderBy('fecha', 'desc')
                                    ->paginate(5);

        }else if($desde == '' && $hasta == ''){

            if($criterio == 'usuario'){
                $criterio = 'users.nombre';
            }

            $historiales = Historial::select('users.nombre as usuario', 'historial.nombre_accion', 'historial.descripcion', 'historial.created_at as fecha')
                                    ->join('users', 'users.id', '=', 'historial.id_user')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->orderBy('fecha', 'desc')
                                    ->paginate(5);

        }else{

            if($criterio == 'usuario'){
                $criterio = 'users.nombre';
            }

            $historiales = Historial::select('users.nombre as usuario', 'historial.nombre_accion', 'historial.descripcion', 'historial.created_at as fecha')
                                    ->join('users', 'users.id', '=', 'historial.id_user')
                                    ->where($criterio, 'like', '%' . $buscar . '%')
                                    ->whereBetween('historial.created_at', array($desde, $hasta))
                                    ->orderBy('fecha', 'desc')
                                    ->paginate(5);
        }

        return [
            
            'pagination' => [
                'total'         => $historiales->total(),
                'current_page'  => $historiales->currentPage(),
                'per_page'      => $historiales->perPage(),
                'last_page'     => $historiales->lastPage(),
                'from'          => $historiales->firstItem(),
                'to'            => $historiales->lastItem(),
            ],

            'historiales' => $historiales

        ];
    }
}
