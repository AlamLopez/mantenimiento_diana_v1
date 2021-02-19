<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Imports\RendimientoImport;
use App\Exports\RendimientoExport;

use Maatwebsite\Excel\Facades\Excel;

use App\Rendimiento;
use App\Vehiculo;
use App\Historial;
use App\Combustible;

use Auth;

class RendimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        /**
         * Desplegar la base de datos de rendimiento registrados en el sistema con paginacion
         * Se pueden hacer busquedas de datos segun el criterio que el usuario elija.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        //dd($request->all());

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $desde_fecha = $request->desde_fecha;
        $hasta_fecha = $request->hasta_fecha;
        $select_combustible = $request->select_combustible;
        $select_status = $request->select_status;
        $criterio2 = $request->criterio2;
        $desde = $request->desde;
        $hasta = $request->hasta;

        $user_distribuidoras = explode(',', Auth::user()->distribuidoras);

        if($buscar == null && $desde_fecha == null && $hasta_fecha == null && $select_combustible == null && $select_status == null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->orderBy('id', 'asc')
                                                ->get();
            
        }else if($buscar != null && $desde_fecha == null && $hasta_fecha == null && $select_combustible == null && $select_status == null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha != null && $hasta_fecha != null && $select_combustible == null && $select_status == null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha != null && $hasta_fecha != null && $select_combustible != null && $select_status == null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha != null && $hasta_fecha != null && $select_combustible != null && $select_status != null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha != null && $hasta_fecha != null && $select_combustible != null && $select_status != null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha == null && $hasta_fecha == null && $select_combustible != null && $select_status != null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha != null && $hasta_fecha != null && $select_combustible == null && $select_status != null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha == null && $hasta_fecha == null && $select_combustible == null && $select_status != null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha == null && $hasta_fecha == null && $select_combustible != null && $select_status == null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->where('combustibles.id', $select_combustible)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->where('combustibles.id', $select_combustible)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha == null && $hasta_fecha == null && $select_combustible != null && $select_status != null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha == null && $hasta_fecha == null && $select_combustible == null && $select_status == null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha == null && $hasta_fecha == null && $select_combustible != null && $select_status == null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->where('combustibles.id', $select_combustible)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->where('combustibles.id', $select_combustible)
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha == null && $hasta_fecha == null && $select_combustible == null && $select_status != null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar != null && $desde_fecha != null && $hasta_fecha != null && $select_combustible == null && $select_status == null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha != null && $hasta_fecha != null && $select_combustible != null && $select_status != null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha != null && $hasta_fecha != null && $select_combustible != null && $select_status != null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha != null && $hasta_fecha != null && $select_combustible != null && $select_status == null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha != null && $hasta_fecha != null && $select_combustible == null && $select_status != null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha == null && $hasta_fecha == null && $select_combustible != null && $select_status != null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha == null && $hasta_fecha == null && $select_combustible == null && $select_status != null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha == null && $hasta_fecha == null && $select_combustible != null && $select_status == null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('combustibles.id', $select_combustible)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('combustibles.id', $select_combustible)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha == null && $hasta_fecha == null && $select_combustible != null && $select_status != null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha != null && $hasta_fecha != null && $select_combustible == null && $select_status == null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha != null && $hasta_fecha != null && $select_combustible == null && $select_status != null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha == null && $hasta_fecha == null && $select_combustible != null && $select_status != null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha == null && $hasta_fecha == null && $select_combustible != null && $select_status == null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('combustibles.id', $select_combustible)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('combustibles.id', $select_combustible)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha != null && $hasta_fecha != null && $select_combustible == null && $select_status == null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha == null && $hasta_fecha == null && $select_combustible != null && $select_status == null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('combustibles.id', $select_combustible)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('combustibles.id', $select_combustible)
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha == null && $hasta_fecha == null && $select_combustible == null && $select_status != null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where('rendimientos.status', $select_status)
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha == null && $hasta_fecha == null && $select_combustible == null && $select_status == null && $desde != null && $hasta != null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else if($buscar == null && $desde_fecha != null && $hasta_fecha != null && $select_combustible != null && $select_status == null && $desde == null && $hasta == null){

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->orderBy('id', 'asc')
                                                ->get();

        }else{

            $rendimientos = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'desc')
                                                ->paginate(10);
            
            $rendimientos2 = Rendimiento::select('rendimientos.id', 'rendimientos.fecha', 'vehiculos.id as id_vehiculo',
                                                'vehiculos.codigo_comb', 'rendimientos.id_data', 'combustibles.id as id_combustible',
                                                'combustibles.nombre as nombre_combustible', 'rendimientos.kilometraje',
                                                'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                                'rendimientos.rendimiento', 'rendimientos.status')
                                                ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                                ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                                ->where($criterio, 'like', '%' . $buscar . '%')
                                                ->whereBetween('rendimientos.fecha', array($desde_fecha, $hasta_fecha))
                                                ->where('combustibles.id', $select_combustible)
                                                ->where('rendimientos.status', $select_status)
                                                ->whereBetween($criterio2, array($desde, $hasta))
                                                ->orderBy('id', 'asc')
                                                ->get();

        }

        $nombreCombustibles = Combustible::all();

        return [
            
            'pagination' => [
                'total'         => $rendimientos->total(),
                'current_page'  => $rendimientos->currentPage(),
                'per_page'      => $rendimientos->perPage(),
                'last_page'     => $rendimientos->lastPage(),
                'from'          => $rendimientos->firstItem(),
                'to'            => $rendimientos->lastItem(),
            ],

            'rendimientos' => $rendimientos,
            'rendimientos2' => $rendimientos2,
            'nombreCombustibles' => $nombreCombustibles,
            'rol' => Auth::user()->id_rol

        ];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * Guardar en la BD del sistema el nuevo registro.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $rendimiento_anterior = Rendimiento::where('id_vehiculo', $request->id_vehiculo)->orderBy('id', 'desc')->first();

        if($rendimiento_anterior == null){

            $recorrido = 0;
            $rendimiento_campo = 0;
            $status = 'OK - 1';

        }else{

            $recorrido = (int) $request->kilometraje - $rendimiento_anterior->kilometraje;
            if($request->cantidad_galones != 0){
                $rendimiento_campo = round(($recorrido / $request->cantidad_galones), 0, PHP_ROUND_HALF_UP);;
            }else{
                $rendimiento_campo = 0;
            }

            if($rendimiento_campo <= 100 && $rendimiento_campo >= 0){
                $status = 'OK';
            }else{
                $status = 'OK - ILOGICO';
            }

        }

        $rendimiento = new Rendimiento();

        $rendimiento->fecha = $request->fecha;
        $rendimiento->id_vehiculo = (int) $request->id_vehiculo;
        $rendimiento->id_data = $request->id_data;
        $rendimiento->id_combustible = (int) $request->id_combustible;
        $rendimiento->kilometraje = (int) $request->kilometraje;
        $rendimiento->cantidad_galones = (double) $request->cantidad_galones;
        $rendimiento->valor = (double) $request->valor;
        $rendimiento->recorrido = $recorrido;
        $rendimiento->rendimiento = $rendimiento_campo;
        $rendimiento->status = $status;

        $rendimiento->save();

        $vehiculo = Vehiculo::findOrFail($request->id_vehiculo);

        $vehiculo->ulto_km = $request->fecha;
        $vehiculo->km = (int) $request->kilometraje;
        $codigo_placa = $vehiculo->codigo_comb;

        $vehiculo->save();
        

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'AGREGAR REGISTRO BASE DATOS';
            $historial->descripcion = 'AGREGÓ REGISTRO PARA VEHÍCULO CON CÓDIGO ' . $codigo_placa;

        $historial->save();
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        /**
         * Actualizar en la BD del sistema el registro seleccionado
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $rendimiento_actual = Rendimiento::findOrFail($request->rendimiento_id);
        //dd($rendimiento_actual);

        $rendimientos = Rendimiento::where('id', '>=', $request->rendimiento_id)
                                    ->where('id_vehiculo', $request->id_vehiculo)
                                    ->get();

        if(sizeOf($rendimientos) > 1){

            $rendimiento_actual->id_data = $request->id_data;
            $rendimiento_actual->id_combustible = $request->id_combustible;
            $rendimiento_actual->kilometraje = $request->kilometraje;
            $rendimiento_actual->cantidad_galones = $request->cantidad_galones;
            $rendimiento_actual->valor = $request->valor;
            $rendimiento_actual->save();

            foreach($rendimientos as $item){

                if($item->status == 'OK - 1'){
                    
                    $recorrido = 0;
                    $rendimiento_campo = 0;
                    $status = 'OK - 1';

                }else{

                    $rendimiento_anterior = Rendimiento::where('id_vehiculo', $item->id_vehiculo)
                                            ->where('id', '<', $item->id)
                                            ->orderBy('id', 'desc')
                                            ->first();
                                            //dd($rendimiento_anterior);

                    $recorrido = $item->kilometraje - $rendimiento_anterior->kilometraje;

                    if($request->cantidad_galones != 0){
                        $rendimiento_campo = round(($recorrido / $request->cantidad_galones), 0, PHP_ROUND_HALF_UP);
                    }else{
                        $rendimiento_campo = 0;
                    }
                    
                    if($rendimiento_campo <= 100 && $rendimiento_campo >= 0){
                        $status = 'OK';
                    }else{
                        $status = 'OK - ILOGICO';
                    }

                }

                $item->recorrido = $recorrido;
                $item->rendimiento = $rendimiento_campo;
                $item->status = $status;
                $item->save();

            }

        }else{

            $rendimiento_anterior = Rendimiento::where('id_vehiculo', $rendimientos[0]->id_vehiculo)
                                            ->where('id', '<', $rendimientos[0]->id)
                                            ->orderBy('id', 'desc')
                                            ->first();

            $recorrido_nuevo = $request->kilometraje - $rendimiento_anterior->kilometraje;

            if($request->cantidad_galones != 0){
                $rendimiento_nuevo = round(($recorrido_nuevo / $request->cantidad_galones), 0, PHP_ROUND_HALF_UP);
            }else{
                $rendimiento_nuevo = 0;
            }

            if($rendimiento_nuevo <= 100 && $rendimiento_nuevo >= 0){
                $status = 'OK';
            }else{
                $status = 'OK - ILOGICO';
            }

            $rendimientos[0]->id_data = $request->id_data;
            $rendimientos[0]->id_combustible = $request->id_combustible;
            $rendimientos[0]->kilometraje = $request->kilometraje;
            $rendimientos[0]->cantidad_galones = $request->cantidad_galones;
            $rendimientos[0]->valor = $request->valor;
            $rendimientos[0]->recorrido = $recorrido_nuevo;
            $rendimientos[0]->rendimiento = $rendimiento_nuevo;
            $rendimientos[0]->status = $status;
            $rendimientos[0]->save();

        }

        $vehiculo = Vehiculo::where('id', $request->id_vehiculo)->first();
            
            $vehiculo->ulto_km = $rendimientos[count($rendimientos) - 1]->fecha;
            $vehiculo->km = $rendimientos[count($rendimientos) - 1]->kilometraje;
        
        $vehiculo->save();

    }

    public function unico($id_data)
    {
        /**
         * Verifica que el id_data ingresado 
         * cuando se esta creando un registro de
         * usuario no exista en la BD del sistema.
         * Se almacena en el historial. 
         */

        $distribuidoras = explode(',', Auth::user()->distribuidoras);

        $rendimiento = Rendimiento::join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                    ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                    ->where('id_data', $id_data)
                                    ->whereNotIn('id_data', ['N/A'])
                                    ->whereIn('distribuidoras.id', $distribuidoras)
                                    ->get();

        if($rendimiento->isEmpty()){
            return "si";
        }else{
            return "no";
        }

    }

    public function unico2($id_data, $rendimiento_id)
    {
        /**
         * Verifica que el id_data ingresado 
         * cuando se esta actualizando un registro de 
         * usuario no exista en la BD del sistema. 
         * Se almacena en el historial.
         */

        $distribuidoras = explode(',', Auth::user()->distribuidoras);
        
        $rendimiento = Rendimiento::join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                    ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                    ->where('id_data', $id_data)
                                    ->whereNotIn('id_data', ['N/A'])
                                    ->whereIn('distribuidoras.id', $distribuidoras)
                                    ->first();

        if($rendimiento == null){
            return "si";
        }elseif($rendimiento->id == $rendimiento_id){
            return "si";
        }else{
            return "no";
        }

    }

    public function ultimaFechaKilometrajeVehiculo(Request $request)
    {
        
        $registro = Rendimiento::select('fecha', 'kilometraje')->where('id_vehiculo', $request->id_vehiculo)->orderBy('id', 'desc')->first();

        if($registro == null){
            return ['fecha' => '', 'kilometraje' => ''];
        }else{
            return ['fecha' => $registro->fecha, 'kilometraje' => $registro->kilometraje];
        }
        
    }

    public function descargarFormato()
    {
        $file = storage_path() . '/app/public/formato-ingreso-datos.xlsx';
 
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->download($file, 'formato-ingreso-datos.xlsx', $headers);
    }

    public function importar(Request $request)
    {
        $path = $request->file('import_file');

        $import = new RendimientoImport();
        
        try{
            $import->import($path);

            $historial = new Historial();

                $historial->id_user = Auth::user()->id;
                $historial->nombre_accion = 'INGRESO BASE DE DATOS';
                $historial->descripcion = 'INGRESO DE DATOS EXITOSO. IMPORTACIÓN';

            $historial->save();

            return [
                'estado' => 'sin errores'
            ];

        }catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            
            $fallos = $e->failures();

            $arregloErrores = array();
            $id = 0;

            foreach($fallos as $fallo){

                $error = new \stdClass();
                $error->id = $id++;
                $error->fila = $fallo->row();
                $error->atributo = $fallo->attribute();
                $error->descripcion = $fallo->errors();
                $arregloErrores[] = $error;

            }

            $historial = new Historial();

                $historial->id_user = Auth::user()->id;
                $historial->nombre_accion = 'INGRESO BASE DE DATOS';
                $historial->descripcion = 'HUBO ERRORES EN ALGUNOS DATOS. NO SE INGRESÓ NADA';

            $historial->save();
            
            return [
                'estado' => 'con errores',
                'errores' => $arregloErrores
            ];
       }

        
    }

    public function kilometrajeAnterior(Request $request)
    {
        //dd($request->all());
        $rendimiento_anterior = Rendimiento::where('id_vehiculo', $request->id_vehiculo)
                                            ->where('id', '<', $request->rendimiento_id)
                                            ->orderBy('id', 'desc')
                                            ->first();

        if($rendimiento_anterior == null){
            return ['kilometraje' => ''];
        }else{
            return ['kilometraje' => $rendimiento_anterior->kilometraje];
        }
    }

    public function kilometrajePosterior(Request $request)
    {
    
        $rendimiento_posterior = Rendimiento::where('id_vehiculo', $request->id_vehiculo)
                                            ->where('id', '>', $request->rendimiento_id)
                                            ->orderBy('id', 'asc')
                                            ->first();

        if($rendimiento_posterior == null){
            return ['kilometraje' => ''];
        }else{
            return ['kilometraje' => $rendimiento_posterior->kilometraje];
        }
    }

    public function exportarExcel(Request $request)
    {
        Excel::store(new RendimientoExport(), 'ingreso-datos.xlsx', 'public');
    }

    public function rendimientoDescargarExcel()
    {
        $file = storage_path() . '/app/public/ingreso-datos.xlsx';
 
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->download($file, 'ingreso-datos.xlsx', $headers);
    }

    public function ultimoRendimiento()
    {

        $distribuidoras = explode(',', Auth::user()->distribuidoras);

        $ultimo_rendimiento = Rendimiento::join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->orderBy('fecha', 'desc')->first();

        $alerta = 'si';

        if($ultimo_rendimiento == null){
            $alerta = 'no';
            $fecha = '';
        }else{
            setlocale(LC_ALL, "es_ES");
            $fecha = strtoupper(strftime("%d de %B del %Y", strtotime($ultimo_rendimiento->fecha)));
        }

        

        return [
            'fecha' => $fecha,
            'alerta' => $alerta
        ];
    }
}
