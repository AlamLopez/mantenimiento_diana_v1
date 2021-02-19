<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Semaforo;
use App\Vehiculo;

use Auth;

class SemaforoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        /**
         * Desplegar el listado de mantenimiento registrados en el sistema con paginacion
         * Se pueden hacer busquedas de datos segun el criterio que el usuario elija
         */

        if(!$request->ajax()) return redirect('/');

        //dd($request->all());

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $diferencia_kms_filtro = $request->diferencia_kms_filtro;
        $diferencia_dias_filtro = $request->diferencia_dias_filtro;
        $distribuidoras = Auth::user()->distribuidoras;
        $distribuidoras = explode(',', $distribuidoras);

        if($buscar == null && $diferencia_kms_filtro == null && $diferencia_dias_filtro == null){
            
            $vehiculos = Vehiculo::whereIn('id_distribuidora', $distribuidoras)->get();
            
            $id_vehiculos = array();

            foreach($vehiculos as $item){
                $id_vehiculos[] = $item->id;
            }

            $semaforos = Semaforo::with('mantenimiento', 'vehiculo.taller')->whereIn('id_vehiculo', $id_vehiculos)->paginate(5);            

        }elseif($buscar != null && $diferencia_kms_filtro == null && $diferencia_dias_filtro == null){

            $vehiculo = Vehiculo::where('placa', $buscar)->whereIn('id_distribuidora', $distribuidoras)->first();

            $semaforos = Semaforo::with(['mantenimiento', 'vehiculo.taller'])
                                ->where('id_vehiculo',  $vehiculo->id)
                                ->paginate(5);

        }elseif($buscar == null && $diferencia_kms_filtro != null && $diferencia_dias_filtro == null){

            $vehiculos = Vehiculo::whereIn('id_distribuidora', $distribuidoras)->get();
            
            $id_vehiculos = array();

            foreach($vehiculos as $item){
                $id_vehiculos[] = $item->id;
            }

            $semaforos = Semaforo::with(['mantenimiento', 'vehiculo.taller'])
                                    ->whereIn('id_vehiculo',  $id_vehiculos)
                                    ->where('diferencia_kms_color', $diferencia_kms_filtro)
                                    ->paginate(5);

            
        }elseif($buscar == null && $diferencia_kms_filtro == null && $diferencia_dias_filtro != null){

            $vehiculos = Vehiculo::whereIn('id_distribuidora', $distribuidoras)->get();
            
            $id_vehiculos = array();

            foreach($vehiculos as $item){
                $id_vehiculos[] = $item->id;
            }

            $semaforos = Semaforo::with(['mantenimiento', 'vehiculo.taller'])
                                    ->whereIn('id_vehiculo',  $id_vehiculos)
                                    ->where('diferencia_dias_color', $diferencia_dias_filtro)
                                    ->paginate(5);

            
        }elseif($buscar == null && $diferencia_kms_filtro != null && $diferencia_dias_filtro != null){

            $vehiculos = Vehiculo::whereIn('id_distribuidora', $distribuidoras)->get();
            
            $id_vehiculos = array();

            foreach($vehiculos as $item){
                $id_vehiculos[] = $item->id;
            }

            $semaforos = Semaforo::with(['mantenimiento', 'vehiculo.taller'])
                                    ->whereIn('id_vehiculo',  $id_vehiculos)
                                    ->where('diferencia_kms_color', $diferencia_kms_filtro)
                                    ->orWhere('diferencia_dias_color', $diferencia_dias_filtro)
                                    ->paginate(5);

        }else{

            $vehiculos = Vehiculo::where('placa', $buscar)->whereIn('id_distribuidora', $distribuidoras)->first();
            
            

            $semaforos = Semaforo::with(['mantenimiento', 'vehiculo.taller'])
                                    ->where('id_vehiculo',  $vehiculos->id)
                                    ->where('diferencia_kms_color', $diferencia_kms_filtro)
                                    ->where('diferencia_dias_color', $diferencia_dias_filtro)
                                    ->paginate(5);

        }

        return [
            
            'pagination' => [
                'total'         => $semaforos->total(),
                'current_page'  => $semaforos->currentPage(),
                'per_page'      => $semaforos->perPage(),
                'last_page'     => $semaforos->lastPage(),
                'from'          => $semaforos->firstItem(),
                'to'            => $semaforos->lastItem(),
            ],

            'semaforos' => $semaforos,
            
            'rol' => Auth::user()->id_rol

        ];

    }

    public function actualizar()
    {
        $distribuidoras = Auth::user()->distribuidoras;
        $distribuidoras = explode(',', $distribuidoras);

        $vehiculos = Vehiculo::whereIn('id_distribuidora', $distribuidoras)->get();
            
        $id_vehiculos = array();

        foreach($vehiculos as $item){
            $id_vehiculos[] = $item->id;
        }

        $semaforos = Semaforo::with(['vehiculo', 'mantenimiento'])->get();

        //dd($semaforos);

        foreach($semaforos as $item){

            if($item->vehiculo->ulto_km != null && $item->vehiculo->km != null && $item->mantenimiento != null){
                
                $diferencia_kms = ($item->mantenimiento->kilometraje - $item->vehiculo->km) + $item->vehiculo->periodo_mtto_kms;
                //if($item-)
                if((strtotime($item->vehiculo->ulto_km) - strtotime($item->mantenimiento->fecha)) != 0 && ($item->vehiculo->km - $item->mantenimiento->kilometraje) != 0){
                    
                    $fecha_estimada = ($diferencia_kms / (($item->vehiculo->km - $item->mantenimiento->kilometraje)/(strtotime($item->vehiculo->ulto_km) - strtotime($item->mantenimiento->fecha)))) + strtotime($item->vehiculo->ulto_km);
                    $fecha_estimada = date('Y-m-d', $fecha_estimada);
                
                }else{
                    $fecha_estimada = null;
                }

                $fecha_nueva = strtotime('+' . $item->vehiculo->periodo_mtto_meses . 'month', strtotime($item->mantenimiento->fecha));
                $fecha_prox_manto_tiempo = date('Y-m-d', $fecha_nueva);

                $fecha_ultimo_mantenimiento = \Carbon\Carbon::createFromFormat('Y-m-d', $item->mantenimiento->fecha);
                $fecha_actual = \Carbon\Carbon::createFromFormat('Y-m-d', date('Y-m-d'));
                $diferencia_tiempo = ($fecha_ultimo_mantenimiento->diffInDays($fecha_actual)) * (-1);
                $diferencia_tiempo = $diferencia_tiempo + $fecha_ultimo_mantenimiento->diffInDays($fecha_prox_manto_tiempo) + 1;       

                if($diferencia_kms >= ($item->vehiculo->periodo_mtto_kms * 0.05)) $diferencia_kms_color = 'BLANCO';
                if($diferencia_kms < ($item->vehiculo->periodo_mtto_kms * 0.05 && $diferencia_kms >= 0)) $diferencia_kms_color = 'VERDE';
                if($diferencia_kms > ($item->vehiculo->periodo_mtto_kms * 0.05 * (-1)) && $diferencia_kms < 0) $diferencia_kms_color = 'AMARILLO';
                if($diferencia_kms <= ($item->vehiculo->periodo_mtto_kms * 0.05 * (-1))) $diferencia_kms_color = 'ROJO';

                $diferencia_dias_color = '';

                if($diferencia_tiempo >= ($fecha_ultimo_mantenimiento->diffInDays($fecha_actual) * 0.05)) $diferencia_dias_color = 'BLANCO';
                if($diferencia_tiempo < ($fecha_ultimo_mantenimiento->diffInDays($fecha_actual) * 0.05) && $diferencia_tiempo >= 0) $diferencia_dias_color = 'VERDE';
                if($diferencia_tiempo > ($fecha_ultimo_mantenimiento->diffInDays($fecha_actual) * 0.05 * (-1)) && $diferencia_tiempo < 0) $diferencia_dias_color = 'AMARILLO';
                if($diferencia_tiempo <= ($fecha_ultimo_mantenimiento->diffInDays($fecha_actual) * 0.05 * (-1))) $diferencia_dias_color = 'ROJO';

                $item->diferencia_kms = $diferencia_kms;
                $item->fecha_prox_manto_kms = $fecha_estimada;
                $item->diferencia_dias = $diferencia_tiempo;
                $item->fecha_prox_manto_tiempo = $fecha_prox_manto_tiempo;
                $item->diferencia_kms_color = $diferencia_kms_color;
                $item->diferencia_dias_color = $diferencia_dias_color;

                $item->save();

            }

        }

    }

}
