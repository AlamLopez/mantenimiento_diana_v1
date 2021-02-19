<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Distribuidora;
use App\CostoRepuesto;
use App\Historial;

class CostoRepuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
        /**
         * Desplegar el listado de actividades registrados en el sistema con paginacion
         * Se pueden hacer busquedas de datos segun el criterio que el usuario elija
         */

        if(!$request->ajax()) return redirect('/');

        $distribuidoras = explode(',', Auth::user()->distribuidoras);

        $costosrepuestos = collect();
        $correlativo = 1;

        foreach($distribuidoras as $item){
            
            $objetocostos = new \stdClass();

            if(Auth::user()->id_rol != 1){
                $costos = CostoRepuesto::where('id_user', Auth::user()->id)->where('id_distribuidora', $item)->get();
            }else{
                $costos = CostoRepuesto::where('id_distribuidora', $item)->get();
            }
            

            $distribuidora = Distribuidora::where('id', $item)->first();

            $objetocostos->correlativo = $correlativo++;
            $objetocostos->nombre_distribuidora = $distribuidora->nombre;
            $objetocostos->pais_distribuidora = $distribuidora->pais;
            $objetocostos->costos_repuestos = $costos;

            $costosrepuestos->push($objetocostos);
        }

        //dd($costosrepuestos);

        return [
            
            'costosrepuestos' => $costosrepuestos,
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

        //dd($request->all());

        foreach($request->distribuidoras as $item){
            
            $costo_repuesto = new CostoRepuesto();

                $costo_repuesto->modelo = $request->modelo;
                $costo_repuesto->rutinas = implode(',', $request->rutinas);
                $costo_repuesto->material = $request->material;
                $costo_repuesto->repuesto = $request->repuesto;
                $costo_repuesto->descripcion = $request->descripcion;
                $costo_repuesto->cantidad = $request->cantidad;
                $costo_repuesto->costo = $request->costo;
                $costo_repuesto->id_user = Auth::user()->id;
                $costo_repuesto->id_distribuidora = $item['id'];

            $costo_repuesto->save();

            $historial = new Historial();

                $historial->id_user = Auth::user()->id;
                $historial->nombre_accion = 'AGREGAR COSTO DE REPUESTO';
                $historial->descripcion = 'AGREGÓ COSTOS DE REPUESTO PARA DISTRIBUIDORA ' . $item['nombre'];

            $historial->save();

        }

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

        //dd($request->all());

        $costo_repuesto = CostoRepuesto::findOrFail($request->id_costorepuesto);
        $distribuidora = Distribuidora::findOrFail($costo_repuesto->id_distribuidora);

            $costo_repuesto->modelo = $request->modelo;
            $costo_repuesto->rutinas = implode(',', $request->rutinas);
            $costo_repuesto->material = $request->material;
            $costo_repuesto->repuesto = $request->repuesto;
            $costo_repuesto->descripcion = $request->descripcion;
            $costo_repuesto->cantidad = $request->cantidad;
            $costo_repuesto->costo = $request->costo;

        $costo_repuesto->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'EDITAR COSTO DE REPUESTO';
            $historial->descripcion = 'EDITÓ COSTOS DE REPUESTO PARA DISTRIBUIDORA ' . $distribuidora->nombre;

        $historial->save();

    }

    public function delete(Request $request)
    {
        //dd($request->all());
        $costo_repuesto = CostoRepuesto::findOrFail($request->id);

        $distribuidora = Distribuidora::findOrFail($costo_repuesto->id_distribuidora);

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'ELIMINAR COSTO DE REPUESTO';
            $historial->descripcion = 'ELIMINÓ COSTO DE REPUESTO PARA DISTRIBUIDORA ' . $distribuidora->nombre;

        $historial->save();

        $costo_repuesto->delete();

        
    }
}
