<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Imports\MantenimientosImport;

use App\Mantenimiento;
use App\Historial;
use App\Vehiculo;
use App\Semaforo;
use App\Rutina;

use Auth;

class MantenimientoController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        /**
         * Desplegar el listado de roles registrados en el sistema con paginacion
         * Se pueden hacer busquedas de datos segun el criterio que el usuario elija
         */

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        $desde = $request->desde;
        $hasta = $request->hasta;
        $desde_km = $request->desde_km;
        $hasta_km = $request->hasta_km;
        $select_rutina = $request->select_rutina;

        $distribuidoras = explode(',', Auth::user()->distribuidoras);

        if($buscar == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_rutina == null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);

        }elseif($buscar != null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_rutina == null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->where($criterio, 'like', '%' . $buscar . '%')
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);

        }elseif($buscar != null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_rutina == null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->where($criterio, 'like', '%' . $buscar . '%')
                                            ->whereBetween('mantenimientos.fecha', array($desde, $hasta))
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);

        }elseif($buscar != null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_rutina == null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->where($criterio, 'like', '%' . $buscar . '%')
                                            ->whereBetween('mantenimientos.fecha', array($desde, $hasta))
                                            ->whereBetween('mantenimientos.kilometraje', array($desde_km, $hasta_km))
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);

        }elseif($buscar != null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_rutina != null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->where($criterio, 'like', '%' . $buscar . '%')
                                            ->whereBetween('mantenimientos.fecha', array($desde, $hasta))
                                            ->where('mantenimientos.nombre_rutina_anterior', $select_rutina)
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);

        }elseif($buscar != null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_rutina != null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->where($criterio, 'like', '%' . $buscar . '%')
                                            ->where('mantenimientos.nombre_rutina_anterior', $select_rutina)
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);

        }elseif($buscar != null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_rutina == null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->where($criterio, 'like', '%' . $buscar . '%')
                                            ->whereBetween('mantenimientos.kilometraje', array($desde_km, $hasta_km))
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);
                                        
        }elseif($buscar != null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_rutina != null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->where($criterio, 'like', '%' . $buscar . '%')
                                            ->whereBetween('mantenimientos.fecha', array($desde, $hasta))
                                            ->where('mantenimientos.nombre_rutina_anterior', $select_rutina)
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);
                                        
        }elseif($buscar == null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_rutina != null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->whereBetween('mantenimientos.fecha', array($desde, $hasta))
                                            ->whereBetween('mantenimientos.kilometraje', array($desde_km, $hasta_km))
                                            ->where('mantenimientos.nombre_rutina_anterior', $select_rutina)
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);
                                        
        }elseif($buscar == null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_rutina != null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->whereBetween('mantenimientos.fecha', array($desde, $hasta))
                                            ->where('mantenimientos.nombre_rutina_anterior', $select_rutina)
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);
                                        
        }elseif($buscar == null && $desde != null && $hasta != null && $desde_km != null && $hasta_km != null && $select_rutina == null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->whereBetween('mantenimientos.fecha', array($desde, $hasta))
                                            ->whereBetween('mantenimientos.kilometraje', array($desde_km, $hasta_km))
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);
                                        
        }elseif($buscar == null && $desde != null && $hasta != null && $desde_km == null && $hasta_km == null && $select_rutina == null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->whereBetween('mantenimientos.fecha', array($desde, $hasta))
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);
                                        
        }elseif($buscar == null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_rutina != null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->whereBetween('mantenimientos.kilometraje', array($desde_km, $hasta_km))
                                            ->where('mantenimientos.nombre_rutina_anterior', $select_rutina)
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);
                                        
        }elseif($buscar == null && $desde == null && $hasta == null && $desde_km != null && $hasta_km != null && $select_rutina == null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->whereBetween('mantenimientos.kilometraje', array($desde_km, $hasta_km))
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);
                                        
        }elseif($buscar == null && $desde == null && $hasta == null && $desde_km == null && $hasta_km == null && $select_rutina != null){

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->where('mantenimientos.nombre_rutina_anterior', $select_rutina)
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);
                                        
        }else{

            $mantenimientos = Mantenimiento::select('mantenimientos.id as id', 'vehiculos.placa as placa', 'mantenimientos.fecha', 'mantenimientos.kilometraje', 'mantenimientos.nombre_rutina_anterior')
                                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->where($criterio, 'like', '%' . $buscar . '%')
                                            ->whereBetween('mantenimientos.fecha', array($desde, $hasta))
                                            ->whereBetween('mantenimientos.kilometraje', array($desde_km, $hasta_km))
                                            ->where('mantenimientos.nombre_rutina_anterior', $select_rutina)
                                            ->orderBy('id', 'desc')
                                            ->paginate(5);
                                        
        }

        return [
            
            'pagination' => [
                'total'         => $mantenimientos->total(),
                'current_page'  => $mantenimientos->currentPage(),
                'per_page'      => $mantenimientos->perPage(),
                'last_page'     => $mantenimientos->lastPage(),
                'from'          => $mantenimientos->firstItem(),
                'to'            => $mantenimientos->lastItem(),
            ],

            'mantenimientos' => $mantenimientos

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
         * Guardar en la BD del sistema el nuevo registro de taller.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $vehiculo = Vehiculo::findOrFail($request->id_vehiculo);

        $mantenimiento = new Mantenimiento();

            $mantenimiento->id_vehiculo = $request->id_vehiculo;
            $mantenimiento->fecha = $request->fecha;
            $mantenimiento->kilometraje = (int) $request->kilometraje;
            $mantenimiento->nombre_rutina_anterior = $request->nombre_rutina_anterior;
            $mantenimiento->nombre_rutina_nueva = $request->nombre_rutina_nueva;

        $mantenimiento->save();

        $semaforo = Semaforo::where('id_vehiculo', $vehiculo->id)->first();

            $semaforo->id_mantenimiento = $mantenimiento->id;
        
        $semaforo->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'AGREGAR MANTENIMIENTO';
            $historial->descripcion = 'AGREGÓ EL MANTENIMIENTO PARA EL VEHÍCULO ' . $vehiculo->placa;

        $historial->save();
        
    }

    public function selectMantenimientoPlaca(Request $request)
    {
        $vehiculo = Vehiculo::where('placa', $request->placa)->first();

        $mantenimiento = Mantenimiento::where('id_vehiculo', $vehiculo->id)->orderBy('id', 'desc')->first();

        if($mantenimiento != null){
           
            $rutina = Rutina::where('nombre', $mantenimiento->nombre_rutina_nueva)->first();

            return [
                'rutina' => $rutina->id,
                'kilometraje' => $mantenimiento->kilometraje
            ];

        }else{

            return [
                'rutina' => 0,
                'kilometraje' => 0
            ];

        }
        
    }


    public function descargarFormato()
    {
        $file = storage_path() . '/app/public/formato-ingreso-mtto.xlsx';
 
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return response()->download($file, 'formato-ingreso-mtto.xlsx', $headers);
    }

    public function importar(Request $request)
    {

        $path = $request->file('import_file');

        $import = new MantenimientosImport();
        
        try{
            $import->import($path);

            $historial = new Historial();

                $historial->id_user = Auth::user()->id;
                $historial->nombre_accion = 'INGRESO DE MANTENIMIENTOS';
                $historial->descripcion = 'INGRESO DE MANTENIMIENTOS EXITOSO. IMPORTACIÓN';

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
                $historial->nombre_accion = 'INGRESO DE MANTENIMIENTOS';
                $historial->descripcion = 'HUBO ERRORES EN ALGUNOS DATOS. NO SE INGRESÓ NADA';

            $historial->save();
            
            return [
                'estado' => 'con errores',
                'errores' => $arregloErrores
            ];
       }

    }

    public function ultimaFechaKilometrajeVehiculo(Request $request)
    {
        
        $registro = Mantenimiento::select('fecha', 'kilometraje')->where('id_vehiculo', $request->id_vehiculo)->orderBy('id', 'desc')->first();

        if($registro == null){
            return ['fecha' => '', 'kilometraje' => ''];
        }else{
            return ['fecha' => $registro->fecha, 'kilometraje' => $registro->kilometraje];
        }
        
    }

    public function ultimoMantenimiento()
    {

        $distribuidoras = explode(',', Auth::user()->distribuidoras);

        $ultimo_mantenimiento = Mantenimiento::join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->orderBy('fecha', 'desc')->first();

        $alerta = 'si';

        if($ultimo_mantenimiento == null){
            $alerta = 'no';
            $fecha = '';
        }else{
            setlocale(LC_ALL, "es_ES");
            $fecha = strtoupper(strftime("%d de %B del %Y", strtotime($ultimo_mantenimiento->fecha)));
        }

        

        return [
            'fecha' => $fecha,
            'alerta' => $alerta
        ];
    }

}
