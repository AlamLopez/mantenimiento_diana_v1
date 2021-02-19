<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Imports\VehiculosImport;
use App\Exports\RendimientoExport;
use App\Exports\VehiculosExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Taller;
use App\Distribuidora;
use App\Vehiculo;
use App\Historial;
use App\Rendimiento;
use App\Semaforo;
use App\CostoRutina;

use Auth;

class VehiculoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /**
         * Desplegar el listado de vehiculos registrados en el sistema con paginacion
         * Se pueden hacer busquedas de datos segun el criterio que el usuario elija.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $select_distribuidora = $request->select_distribuidora;
        $select_taller = $request->select_taller;
        $desde = $request->desde;
        $hasta = $request->hasta;
        $desde_km = $request->desde_km;
        $hasta_km = $request->hasta_km;
        $select_estado = $request->select_estado;
        $select_propietario = $request->select_propietario;

        $nombresDistribuidoras = array();
        $nombresTalleres = array();

        $user_distribuidoras = explode(',', Auth::user()->distribuidoras);

        if($buscar == null && $select_distribuidora == null && $select_taller == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){

            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora != null && $select_taller == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller == null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){
            //FUNCIONA
            dd('4');
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller == null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora != null && $select_taller != null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora != null && $select_taller == null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora != null && $select_taller == null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){
            
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora != null && $select_taller == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){
            
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){
            
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){
            
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller == null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){
            
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller == null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){
            
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller == null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){
            
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller == null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){
            
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){
            
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){
            
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){
            
            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora != null && $select_taller == null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora != null && $select_taller != null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora != null && $select_taller != null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora != null && $select_taller != null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora != null && $select_taller == null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora != null && $select_taller != null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora != null && $select_taller != null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){

            //FUNCIONA

            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller != null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller == null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller == null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller != null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){

            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller != null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){

            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller != null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){

            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller == null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){

            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller == null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){

            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller == null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){

            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller == null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller != null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller != null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller != null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller != null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller != null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller != null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller != null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller != null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller != null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller != null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller != null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller == null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller == null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller == null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller == null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario == null){

            //FUNCIONA
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario == null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('vehiculos.condicion', $select_estado)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller == null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller == null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario == null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller != null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario == null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller != null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('talleres.id', $select_taller)
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller == null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora == null && $select_taller == null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar == null && $select_distribuidora != null && $select_taller == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado == null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado == null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_estado != null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else if($buscar != null && $select_distribuidora == null && $select_taller != null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_estado != null && $select_propietario != null){

            //FUNCIONA 
            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);

            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->whereIn('distribuidoras.id', $user_distribuidoras)
                                ->where('talleres.id', $select_taller)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }else{

            $vehiculos = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->orderBy('vehiculos.id', 'asc')
                                ->paginate(10);
            
            $vehiculos2 = Vehiculo::select('vehiculos.id as id_vehiculo', 'vehiculos.codigo_comb', 'vehiculos.placa', 
                                        'vehiculos.marca', 'vehiculos.modelo', 'vehiculos.anio', 'distribuidoras.id as id_distribuidora',
                                        'distribuidoras.nombre as nombre_distribuidora', 'vehiculos.conductor', 'vehiculos.ulto_km', 
                                        'vehiculos.km', 'vehiculos.condicion', 'talleres.id as id_taller', 'talleres.nombre as nombre_taller',
                                        'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario', 'vehiculos.numero_vehiculo')
                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                ->join('talleres', 'vehiculos.id_taller', '=', 'talleres.id')
                                ->where($criterio, 'like', '%' . $buscar . '%')
                                ->where('distribuidoras.id', $select_distribuidora)
                                ->where('talleres.id', $select_taller)
                                ->whereBetween('vehiculos.ulto_km', array($desde, $hasta))
                                ->whereBetween('vehiculos.km', array($desde_km, $hasta_km))
                                ->where('vehiculos.condicion', $select_estado)
                                ->where('vehiculos.propietario', 'vehiculos.numero_vehiculo', $select_propietario)
                                ->orderBy('vehiculos.id', 'asc')
                                ->get();

        }

        foreach($user_distribuidoras as $item){
            $nombresDistribuidoras[] = Distribuidora::findOrFail($item);
        }

        $nombresTalleres = Taller::all();        

        return [
            
            'pagination' => [
                'total'         => $vehiculos->total(),
                'current_page'  => $vehiculos->currentPage(),
                'per_page'      => $vehiculos->perPage(),
                'last_page'     => $vehiculos->lastPage(),
                'from'          => $vehiculos->firstItem(),
                'to'            => $vehiculos->lastItem(),
            ],

            'vehiculos' => $vehiculos,

            'vehiculos2' => $vehiculos2,

            'nombresDistribuidoras' => $nombresDistribuidoras,

            'nombresTalleres' => $nombresTalleres,

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
         * Guardar en la BD del sistema el nuevo registro de vehiculo.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $vehiculos_registrados = Vehiculo::where('modelo', $request->modelo)
                                        ->where('id_distribuidora', $request->id_distribuidora)
                                        ->first();

        if($vehiculos_registrados == null){

            $costo_rutina = new CostoRutina();
            $costo_rutina->modelo = $request->modelo;
            $costo_rutina->rutina = 'A';
            $costo_rutina->costo = 0.00;
            $costo_rutina->id_user = Auth::user()->id;
            $costo_rutina->id_distribuidora = $request->id_distribuidora;
            $costo_rutina->save();

            $costo_rutina = new CostoRutina();
            $costo_rutina->modelo = $request->modelo;
            $costo_rutina->rutina = 'B';
            $costo_rutina->costo = 0.00;
            $costo_rutina->id_user = Auth::user()->id;
            $costo_rutina->id_distribuidora = $request->id_distribuidora;
            $costo_rutina->save();

            $costo_rutina = new CostoRutina();
            $costo_rutina->modelo = $request->modelo;
            $costo_rutina->rutina = 'C';
            $costo_rutina->costo = 0.00;
            $costo_rutina->id_user = Auth::user()->id;
            $costo_rutina->id_distribuidora = $request->id_distribuidora;
            $costo_rutina->save();

            /*
            $costo_rutina = new CostoRutina();
            $costo_rutina->modelo = $request->modelo;
            $costo_rutina->rutina = 'D';
            $costo_rutina->costo = 0.00;
            $costo_rutina->id_user = Auth::user()->id;
            $costo_rutina->id_distribuidora = $request->id_distribuidora;
            $costo_rutina->save();
            */

            $historial = new Historial();

                $historial->id_user = Auth::user()->id;
                $historial->nombre_accion = 'AGREGAR COSTO DE RUTINA';
                $historial->descripcion = 'AGREGÓ COSTOS DE RUTINAS A,B,C,D PARA EL MODELO ' . $request->modelo;

            $historial->save();

        }

        $vehiculo = new Vehiculo();

            $vehiculo->codigo_comb = $request->codigo_comb;
            $vehiculo->placa = $request->placa;
            $vehiculo->numero_vehiculo = $request->numero_vehiculo;
            $vehiculo->marca = $request->marca;
            $vehiculo->modelo = $request->modelo;
            $vehiculo->anio = $request->anio;
            $vehiculo->id_distribuidora = (int) $request->id_distribuidora;
            $vehiculo->conductor = $request->conductor;
            $vehiculo->id_taller = (int) $request->id_taller;
            $vehiculo->periodo_mtto_kms = $request->periodo_mtto_kms;
            $vehiculo->periodo_mtto_meses = $request->periodo_mtto_meses;
            $vehiculo->ruta = $request->ruta;
            $vehiculo->propietario = $request->propietario;

        $vehiculo->save();

        $semaforo = new Semaforo();

            $semaforo->id_vehiculo = $vehiculo->id;

        $semaforo->save();

        $historial_vehiculo = new Historial();

            $historial_vehiculo->id_user = Auth::user()->id;
            $historial_vehiculo->nombre_accion = 'AGREGAR VEHÍCULO';
            $historial_vehiculo->descripcion = 'AGREGÓ EL VEHÍCULO CON PLACA ' . $vehiculo->placa;

        $historial_vehiculo->save();

        $historial_vehiculo = new Historial();

            $historial_vehiculo->id_user = Auth::user()->id;
            $historial_vehiculo->nombre_accion = 'AGREGAR SEMÁFORO';
            $historial_vehiculo->descripcion = 'AGREGÓ EL SEMÁFORO EL VEHÍCULO CON PLACA ' . $vehiculo->placa;

        $historial_vehiculo->save();
        
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

        $vehiculo = Vehiculo::findOrFail($request->id);

        $descripcion_historial = 'EDITÓ EL VEHICULO CON PLACA ' . $vehiculo->placa;

        if($vehiculo->codigo_comb != $request->codigo_comb){
            $descripcion_historial .= ' | codigo_viejo: ' . $vehiculo->codigo_comb . ', codigo_nueva: ' . $request->codigo_comb;
        }

        if($vehiculo->placa != $request->placa){
            $descripcion_historial .= ' | placa_vieja: ' . $vehiculo->placa . ', placa_nueva: ' . $request->placa;
        }

        if($vehiculo->numero_vehiculo != $request->numero_vehiculo){
            $descripcion_historial .= ' | numero_vehiculo_viejo: ' . $vehiculo->numero_vehiculo . ', numero_vehiculo_nuevo: ' . $request->numero_vehiculo;
        }

        if($vehiculo->marca != $request->marca){
            $descripcion_historial .= ' | marca_vieja: ' . $vehiculo->marca . ', marca_nueva: ' . $request->marca;
        }

        if($vehiculo->modelo != $request->modelo){
            $descripcion_historial .= ' | modelo_viejo: ' . $vehiculo->modelo . ', modelo_nuevo: ' . $request->modelo;
        }

        if($vehiculo->anio != $request->anio){
            $descripcion_historial .= ' | año_viejo: ' . $vehiculo->anio . ', año_nuevo: ' . $request->anio;
        }

        if($vehiculo->id_distribuidora != $request->id_distribuidora){
            $descripcion_historial .= ' | distribuidora_vieja: ' . Distribuidora::findOrFail($vehiculo->id_distribuidora)->nombre . ', distribuidora_nuevo: ' . Distribuidora::findOrFail($request->id_distribuidora)->nombre;
        }

        if($vehiculo->conductor != $request->conductor){
            $descripcion_historial .= ' | conductor_viejo: ' . $vehiculo->conductor . ', conductor_nuevo: ' . $request->conductor;
        }

        if($vehiculo->id_taller != $request->id_taller){
            $descripcion_historial .= ' | taller_viejo: ' . Taller::findOrFail($vehiculo->id_taller)->nombre . ', taller_nuevo: ' . Taller::findOrFail($request->id_taller)->nombre;
        }

        if($vehiculo->periodo_mtto_kms != $request->periodo_mtto_kms){
            $descripcion_historial .= ' | mtt_kms_viejo: ' . $vehiculo->periodo_mtto_kms . ', mtto_kms_nuevo: ' . $request->periodo_mtto_kms;
        }

        if($vehiculo->periodo_mtto_meses != $request->periodo_mtto_meses){
            $descripcion_historial .= ' | mtto_meses_viejo: ' . $vehiculo->periodo_mtto_meses . ', mtto_meses_nuevo: ' . $request->periodo_mtto_meses;
        }

        if($vehiculo->ruta != $request->ruta){
            $descripcion_historial .= ' | ruta_vieja: ' . $vehiculo->ruta . ', ruta_nueva: ' . $request->ruta;
        }

        if($vehiculo->propietario != $request->propietario){
            $descripcion_historial .= ' | propietario_viejo: ' . $vehiculo->propietario . ', propietario_nuevo: ' . $request->propietario;
        }

        $vehiculo->codigo_comb = $request->codigo_comb;
        $vehiculo->placa = $request->placa;
        $vehiculo->numero_vehiculo = $request->numero_vehiculo;
        $vehiculo->marca = $request->marca;
        $vehiculo->modelo = $request->modelo;
        $vehiculo->anio = $request->anio;
        $vehiculo->id_distribuidora = (int) $request->id_distribuidora;
        $vehiculo->conductor = $request->conductor;
        $vehiculo->id_taller = (int) $request->id_taller;
        $vehiculo->periodo_mtto_kms = $request->periodo_mtto_kms;
        $vehiculo->periodo_mtto_meses = $request->periodo_mtto_meses;
        $vehiculo->ruta = $request->ruta;
        $vehiculo->propietario = $request->propietario;

        $vehiculo->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'EDITAR VEHÍCULO';
            $historial->descripcion = $descripcion_historial;

        $historial->save();

    }

    public function desactivar(Request $request)
    {
        /**
         * Cambiar el estado de un vehiculo a Desactivado
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $vehiculo = Vehiculo::findOrFail($request->id);

            $vehiculo->condicion = false;

        $vehiculo->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'DESACTIVAR VEHÍCULO';
            $historial->descripcion = 'DESACTIVÓ AL VEHÍCULO CON PLACA ' . $vehiculo->placa;

        $historial->save();

    }

    public function activar(Request $request)
    {
        /**
         * Cambiar el estado de un vehiculo a Activado
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $vehiculo = Vehiculo::findOrFail($request->id);

            $vehiculo->condicion = true;

        $vehiculo->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'ACTIVAR VEHÍCULO';
            $historial->descripcion = 'ACTIVÓ AL VEHÍCULO CON PLACA ' . $vehiculo->placa;

        $historial->save();

    }

    public function unico($placa)
    {
        /**
         * Verifica que la placa del vehiculo ingresado 
         * cuando se esta creando un registro de
         * usuario no exista en la BD del sistema.
         * Se almacena en el historial. 
         */

        $vehiculo = Vehiculo::where('placa', $placa)->get();

        if($vehiculo->isEmpty()){
            return "si";
        }else{
            return "no";
        }

    }

    public function unico2($placa, $vehiculo_id)
    {
        /**
         * Verifica que la placa del vehiculo ingresado 
         * cuando se esta actualizando un registro de 
         * usuario no exista en la BD del sistema. 
         * Se almacena en el historial.
         */

        $vehiculo = Vehiculo::where('placa', $placa)->first();

        if($vehiculo == null){
            return "si";
        }elseif($vehiculo->id == $vehiculo_id){
            return "si";
        }else{
            return "no";
        }

    }

    public function selectVehiculo(Request $request)
    {
        /**
         * Devuelve los vehiculos registrados con condicion Activo y asignados al usuario.
         */

        $distribuidoras = explode(',', Auth::user()->distribuidoras);

        $vehiculos = Vehiculo::select('id', 'codigo_comb')->where('condicion', true)->whereIn('id_distribuidora', $distribuidoras)->orderBy('codigo_comb', 'asc')->get();

        return ['vehiculos' => $vehiculos];
        
    }

    public function selectVehiculoPlaca(Request $request)
    {
        /**
         * Devuelve los vehiculos registrados con condicion Activo y asignados al usuario.
         */

        $distribuidoras = explode(',', Auth::user()->distribuidoras);

        $vehiculos = Vehiculo::select('id', 'placa')->where('condicion', true)->whereIn('id_distribuidora', $distribuidoras)->orderBy('placa', 'asc')->get();

        return ['vehiculos' => $vehiculos];
        
    }

    public function descargarFormato()
    {
        $file = storage_path() . '/app/public/formato-vehiculos.xlsx';
 
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->download($file, 'formato-vehiculos.xlsx', $headers);
    }

    public function importar(Request $request)
    {

        $path = $request->file('import_file');

        $import = new VehiculosImport();
        
        $import->import($path);

        $vehiculos_importados = Vehiculo::all();

        foreach($vehiculos_importados as $item){
            
            if($item->semaforo == null){

                $semaforo = new Semaforo();

                    $semaforo->id_vehiculo = $item->id;

                $semaforo->save();

            }
        }
        
        $fallos = $import->failures();

        if($fallos->isEmpty()){

            $historial = new Historial();

                $historial->id_user = Auth::user()->id;
                $historial->nombre_accion = 'IMPORTAR VEHÍCULOS';
                $historial->descripcion = 'SE IMPORTARON VEHÍCULOS DESDE UN ARCHIVO EXCEL';

            $historial->save();

            return [
                'estado' => 'sin errores'
            ];

        }else{

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
                $historial->nombre_accion = 'IMPORTAR VEHÍCULOS';
                $historial->descripcion = 'SE IMPORTARON VEHÍCULOS DESDE UN ARCHIVO EXCEL. HUBO ERRORES EN ALGUNOS DATOS';

            $historial->save();

            return [
                'estado' => 'con errores',
                'errores' => $arregloErrores
            ];
        }

    }

    public function exportarExcel(Request $request)
    {

        Excel::store(new VehiculosExport(), 'inventario-flota.xlsx', 'public');

    }

    public function vehiculoDescargarExcel()
    {
        $file = storage_path() . '/app/public/inventario-flota.xlsx';
 
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->download($file, 'inventario-flota.xlsx', $headers);
    }

    public function selectModelo()
    {
        $distribuidoras = explode(',', Auth::user()->distribuidoras);

        $modelos = Vehiculo::select('modelo')->whereIn('id_distribuidora', $distribuidoras)->distinct('modelo')->get();

        $array_modelos = array();
        $i = 1;

        foreach($modelos as $item){
            $objeto = new \stdClass();
            $objeto->correlativo = $i++;
            $objeto->modelo = $item->modelo;
            $array_modelos[] = $objeto;
        }

        //dd($array_modelos);

        return[
            'modelos' => $array_modelos
        ];
    }

    public function selectModeloGrafico(Request $request)
    {
        //dd($request->all());

        $lista_distribuidoras = array();
        $distribuidoras = $request->distribuidoras;
        //dd($distribuidoras);

        foreach($distribuidoras as $item){
            $lista_distribuidoras[] = $item["id"];
        }

        $modelos = Vehiculo::select('modelo')->whereIn('id_distribuidora', $lista_distribuidoras)->distinct('modelo')->get();

        $array_modelos = array();
        $i = 1;

        foreach($modelos as $item){
            $objeto = new \stdClass();
            $objeto->correlativo = $i++;
            $objeto->modelo = $item->modelo;
            $array_modelos[] = $objeto;
        }

        return[
            'modelos' => $array_modelos
        ];

    }

    public function selectVehiculoGrafico(Request $request)
    {
        //dd($request->all());

        $lista_distribuidoras = array();
        $lista_modelos = array();
        $distribuidoras = $request->distribuidoras;
        $modelos = $request->modelos;
        //dd($distribuidoras);

        foreach($distribuidoras as $item){
            $lista_distribuidoras[] = $item["id"];
        }

        foreach($modelos as $item){
            $lista_modelos[] = $item["modelo"];
        }

        $vehiculos = Vehiculo::select('id', 'codigo_comb')->whereIn('id_distribuidora', $lista_distribuidoras)->whereIn('modelo', $lista_modelos)->get();

        return[
            'vehiculos' => $vehiculos
        ];
    }

}
