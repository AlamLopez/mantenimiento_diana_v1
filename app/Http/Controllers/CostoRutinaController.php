<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Distribuidora;
use App\CostoRutina;
use App\Historial;

class CostoRutinaController extends Controller
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

        $distribuidoras = explode(',', Auth::user()->distribuidoras);

        $costosrutinas = collect();
        $correlativo = 1;

        foreach($distribuidoras as $item){
            
            $objetocostos = new \stdClass();

            if(Auth::user()->id_rol != 1){
                $costos = CostoRutina::whereIn('rutina', ['A', 'B', 'C'])->where('id_user', Auth::user()->id)->where('id_distribuidora', $item)->get();
            }else{
                $costos = CostoRutina::whereIn('rutina', ['A', 'B', 'C'])->where('id_distribuidora', $item)->get();
            }

            $distribuidora = Distribuidora::where('id', $item)->first();

            $objetocostos->correlativo = $correlativo++;
            $objetocostos->nombre_distribuidora = $distribuidora->nombre;
            $objetocostos->pais_distribuidora = $distribuidora->pais;
            $objetocostos->costos_rutinas = $costos;

            $costosrutinas->push($objetocostos);
        }

        return [
            
            'costosrutinas' => $costosrutinas,
            'rol' => Auth::user()->id_rol

        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request){
        
        /**
         * Desplegar el listado de actividades registrados en el sistema con paginacion
         * Se pueden hacer busquedas de datos segun el criterio que el usuario elija
         */

        if(!$request->ajax()) return redirect('/');

        $distribuidora = Distribuidora::findOrFail($request->id_distribuidora);

        $costo_rutina = CostoRutina::findOrFail($request->id);
        $costo_rutina->costo = $request->costo;
        $costo_rutina->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'EDITAR COSTO DE RUTINA';
            $historial->descripcion = 'EDITÓ COSTOS DE RUTINA ' . $costo_rutina->rutina . ' PARA EL MODELO ' . $request->modelo . ' EN LA DISTRIBUIDORA ' . $distribuidora->nombre;

        $historial->save();
    }

    
}
