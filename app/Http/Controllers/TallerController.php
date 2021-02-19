<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Taller;
use App\Historial;

use Auth;

class TallerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        /**
         * Desplegar el listado de talleres registrados en el sistema con paginacion
         * Se pueden hacer busquedas de datos segun el criterio que el usuario elija.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $talleres = Taller::orderBy('nombre', 'asc')->paginate(5);
        }else{
            $talleres = Taller::where($criterio, 'like', '%' . $buscar . '%')->orderBy('nombre', 'asc')->paginate(5);
        }

        return [
            
            'pagination' => [
                'total'         => $talleres->total(),
                'current_page'  => $talleres->currentPage(),
                'per_page'      => $talleres->perPage(),
                'last_page'     => $talleres->lastPage(),
                'from'          => $talleres->firstItem(),
                'to'            => $talleres->lastItem(),
            ],

            'talleres' => $talleres

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

        $taller = new Taller();

            $taller->nombre = $request->nombre;

        $taller->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'AGREGAR TALLER';
            $historial->descripcion = 'AGREGÓ EL TALLER ' . $taller->nombre;

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
         * Actualizar en la BD del sistema el registro seleccionado.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $taller = Taller::findOrFail($request->id);

            $descripcion_historial = 'EDITÓ EL TALLER ' . $taller->nombre . ' | nombre_viejo: ' . $taller->nombre . ', nombre_nuevo: ' . $request->nombre;

            $taller->nombre = $request->nombre;

        $taller->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'EDITAR TALLER';
            $historial->descripcion = $descripcion_historial;

        $historial->save();

    }

    public function desactivar(Request $request)
    {
        /**
         * Cambiar el estado de un taller a Desactivado.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $taller = Taller::findOrFail($request->id);

            $taller->condicion = false;

        $taller->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'DESACTIVAR TALLER';
            $historial->descripcion = 'DESACTIVÓ AL TALLER ' . $taller->nombre;

        $historial->save();

    }

    public function activar(Request $request)
    {
        /**
         * Cambiar el estado de un taller a Activado.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $taller = Taller::findOrFail($request->id);

            $taller->condicion = true;

        $taller->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'ACTIVAR TALLER';
            $historial->descripcion = 'ACTIVÓ AL TALLER ' . $taller->nombre;

        $historial->save();

    }

    public function selectTaller(Request $request)
    {
        /**
         * Devuelve los talleres registrados con condicion Activo.
         */

        $talleres = Taller::where('condicion', true)->select('id', 'nombre')->orderBy('nombre', 'asc')->get();

        return ['talleres' => $talleres];
        
    }
}
