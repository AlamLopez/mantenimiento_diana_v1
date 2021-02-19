<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Rol;

class RolController extends Controller
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

        if($buscar == ''){
            $roles = Rol::orderBy('nombre', 'asc')->paginate(5);
        }else{
            $roles = Rol::where($criterio, 'like', '%' . $buscar . '%')->orderBy('nombre', 'asc')->paginate(5);
        }

        return [
            
            'pagination' => [
                'total'         => $roles->total(),
                'current_page'  => $roles->currentPage(),
                'per_page'      => $roles->perPage(),
                'last_page'     => $roles->lastPage(),
                'from'          => $roles->firstItem(),
                'to'            => $roles->lastItem(),
            ],

            'roles' => $roles

        ];
    }

    public function selectRol(Request $request)
    {
        /**
         * Devuelve los roles registrados con condicion Activo
         */

        $roles = Rol::where('condicion', true)->select('id', 'nombre')->orderBy('nombre', 'asc')->get();

        return ['roles' => $roles];
        
    }
}
