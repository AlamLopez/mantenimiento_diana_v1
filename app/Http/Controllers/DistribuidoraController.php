<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Distribuidora;
use App\Historial;

use Auth;

class DistribuidoraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        /**
         * Desplegar el listado de distribuidoras registrados en el sistema con paginacion
         * Se pueden hacer busquedas de datos segun el criterio que el usuario elija.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $distribuidoras = Distribuidora::orderBy('nombre', 'asc')->paginate(5);
        }else{
            $distribuidoras = Distribuidora::where($criterio, 'like', '%' . $buscar . '%')->orderBy('nombre', 'asc')->paginate(5);
        }

        return [
            
            'pagination' => [
                'total'         => $distribuidoras->total(),
                'current_page'  => $distribuidoras->currentPage(),
                'per_page'      => $distribuidoras->perPage(),
                'last_page'     => $distribuidoras->lastPage(),
                'from'          => $distribuidoras->firstItem(),
                'to'            => $distribuidoras->lastItem(),
            ],

            'distribuidoras' => $distribuidoras

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
         * Guardar en la BD del sistema el nuevo registro de distribuidora.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $distribuidora = new Distribuidora();

            $distribuidora->nombre = $request->nombre;
            $distribuidora->pais = $request->pais;

        $distribuidora->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'AGREGAR DISTRIBUIDORA';
            $historial->descripcion = 'AGREGÓ AL DISTRIBUIDORA ' . $distribuidora->nombre;

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

        $distribuidora = Distribuidora::findOrFail($request->id);

            $descripcion_historial = 'EDITÓ LA DISTRIBUIDORA ' . $distribuidora->nombre;

            if($distribuidora->nombre != $request->nombre){
                $descripcion_historial .= ' | nombre_viejo: ' . $distribuidora->nombre . ', nombre_nuevo: ' . $request->nombre;
            }

            if($distribuidora->pais != $request->pais){
                $descripcion_historial .= ' | pais_viejo: ' . $distribuidora->pais . ', pais_nuevo: ' . $request->pais;
            }

            $distribuidora->nombre = $request->nombre;
            $distribuidora->pais = $request->pais;

        $distribuidora->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'EDITAR DISTRIBUIDORA';
            $historial->descripcion = $descripcion_historial;

        $historial->save();

    }

    public function desactivar(Request $request)
    {
        /**
         * Cambiar el estado de una distribuidora a Desactivado.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $distribuidora = Distribuidora::findOrFail($request->id);

            $distribuidora->condicion = false;

        $distribuidora->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'DESACTIVAR DISTRIBUIDORA';
            $historial->descripcion = 'DESACTIVÓ AL DISTRIBUIDORA ' . $distribuidora->nombre;

        $historial->save();

    }

    public function activar(Request $request)
    {
        /**
         * Cambiar el estado de una distribuidora a Activado.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $distribuidora = Distribuidora::findOrFail($request->id);

            $distribuidora->condicion = true;

        $distribuidora->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'ACTIVAR DISTRIBUIDORA';
            $historial->descripcion = 'ACTIVÓ AL DISTRIBUIDORA ' . $distribuidora->nombre;

        $historial->save();

    }

    public function selectDistribuidora(Request $request)
    {
        /**
         * Devuelve las distribuidoras registradas con condicion Activo y asignados al usuario.
         */

        $user_distribuidoras = explode(',', Auth::user()->distribuidoras);

        $distribuidoras = Distribuidora::select('id', 'nombre')->where('condicion', true)->whereIn('id', $user_distribuidoras)->orderBy('nombre', 'asc')->get();

        return ['distribuidoras' => $distribuidoras];
        
    }

    public function selectDistribuidoraUsuario(Request $request)
    {
        /**
         * Devuelve las distribuidoras registradas con condicion Activo.
         */

        $distribuidoras = Distribuidora::select('id', 'nombre')->where('condicion', true)->orderBy('nombre', 'asc')->get();

        return ['distribuidoras' => $distribuidoras];
        
    }

}
