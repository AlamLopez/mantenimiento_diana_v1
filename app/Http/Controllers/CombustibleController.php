<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Combustible;
use App\Historial;

use Auth;

class CombustibleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        
        /**
         * Desplegar el listado de combustibles registrados en el sistema con paginacion
         * Se pueden hacer busquedas de datos segun el criterio que el usuario elija.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){
            $combustibles = Combustible::orderBy('nombre', 'asc')->paginate(5);
        }else{
            $combustibles = Combustible::where($criterio, 'like', '%' . $buscar . '%')->orderBy('nombre', 'asc')->paginate(5);
        }

        return [
            
            'pagination' => [
                'total'         => $combustibles->total(),
                'current_page'  => $combustibles->currentPage(),
                'per_page'      => $combustibles->perPage(),
                'last_page'     => $combustibles->lastPage(),
                'from'          => $combustibles->firstItem(),
                'to'            => $combustibles->lastItem(),
            ],

            'combustibles' => $combustibles

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
         * Guardar en la BD del sistema el nuevo registro de combustible.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $combustible = new Combustible();

            $combustible->nombre = $request->nombre;

        $combustible->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'AGREGAR COMBUSTIBLE';
            $historial->descripcion = 'AGREGÓ EL COMBUSTIBLE ' . $combustible->nombre;

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

        $combustible = Combustible::findOrFail($request->id);

            $descripcion_historial = 'EDITÓ EL COMBUSTIBLE ' . $combustible->nombre . ' | nombre_viejo: ' . $combustible->nombre . ', nombre_nuevo: ' . $request->nombre;

            $combustible->nombre = $request->nombre;

        $combustible->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'EDITAR COMBUSTIBLE';
            $historial->descripcion = $descripcion_historial;

        $historial->save();

    }

    public function desactivar(Request $request)
    {
        /**
         * Cambiar el estado de un combustible a Desactivado.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $combustible = Combustible::findOrFail($request->id);

            $combustible->condicion = false;

        $combustible->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'DESACTIVAR COMBUSTIBLE';
            $historial->descripcion = 'DESACTIVÓ AL COMBUSTIBLE ' . $combustible->nombre;

        $historial->save();

    }

    public function activar(Request $request)
    {
        /**
         * Cambiar el estado de un combustible a Activado.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $combustible = Combustible::findOrFail($request->id);

            $combustible->condicion = true;

        $combustible->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'ACTIVAR COMBUSTIBLE';
            $historial->descripcion = 'ACTIVÓ AL COMBUSTIBLE ' . $combustible->nombre;

        $historial->save();

    }

    public function selectCombustible(Request $request)
    {
        /**
         * Devuelve los combustibles registrados con condicion Activo.
         */

        $combustibles = Combustible::select('id', 'nombre')->where('condicion', true)->orderBy('nombre', 'asc')->get();

        return ['combustibles' => $combustibles];
        
    }
}
