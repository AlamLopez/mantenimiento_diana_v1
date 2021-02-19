<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Rol;
use App\User;
use App\Distribuidora;
use App\Historial;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /**
         * Desplegar el listado de usuarios registrados en el sistema con paginacion
         * Se pueden hacer busquedas de datos segun el criterio que el usuario elija.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == ''){

            $users = User::join('roles', 'roles.id', '=', 'users.id_rol')
                        ->select('users.id', 'users.nombre', 'users.nombre_completo', 'users.email', 'users.password', 'users.distribuidoras', 'users.condicion', 'users.id_rol', 'users.ultimo_login', 'roles.nombre as rol')
                        ->orderBy('users.id', 'desc')
                        ->paginate(5);

            foreach($users as $item){
                
                $distribuidoras = explode(',', $item->distribuidoras);

                $arregloDistribuidora = array();
                $nombreDistribuidora = array();
                
                foreach($distribuidoras as $data){
                    $distribuidora = Distribuidora::select('id', 'nombre')->where('id', $data)->first();
                    $arregloDistribuidora[] = $distribuidora;
                    $nombreDistribuidora[] = $distribuidora->nombre;
                }

                $item->distribuidoras = implode(', ', $nombreDistribuidora);
                $item->distribuidoras2 = $arregloDistribuidora;

            }

        }else{

            if($criterio == 'nombre'){
                $criterio = 'users.nombre';
            }

            if($criterio == 'nombre_completo'){
                $criterio = 'users.nombre_completo';
            }

            $users = User::join('roles', 'roles.id', '=', 'users.id_rol')
                        ->select('users.id', 'users.nombre', 'users.nombre_completo', 'users.email', 'users.password', 'users.distribuidoras', 'users.condicion', 'users.id_rol', 'users.ultimo_login', 'roles.nombre as rol')
                        ->where($criterio, 'like', '%' . $buscar . '%')
                        ->orderBy('users.id', 'desc')
                        ->paginate(5);

            foreach($users as $item){
    
                $distribuidoras = explode(',', $item->distribuidoras);

                $arregloDistribuidora = array();
                $nombreDistribuidora = array();
                
                foreach($distribuidoras as $data){
                    $distribuidora = Distribuidora::select('id', 'nombre')->where('id', $data)->first();
                    $arregloDistribuidora[] = $distribuidora;
                    $nombreDistribuidora[] = $distribuidora->nombre;
                }

                $item->distribuidoras = implode(', ', $nombreDistribuidora);
                $item->distribuidoras2 = $arregloDistribuidora;

            }

        }

        return [
            
            'pagination' => [
                'total'         => $users->total(),
                'current_page'  => $users->currentPage(),
                'per_page'      => $users->perPage(),
                'last_page'     => $users->lastPage(),
                'from'          => $users->firstItem(),
                'to'            => $users->lastItem(),
            ],

            'usuarios' => $users

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
         * Guardar en la BD del sistema el nuevo registro de usuario.
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $distribuidoras_id = array();

        foreach($request->distribuidoras as $item){

            $distribuidoras_id[] = $item['id'];

        }

        $user = new User();

            $user->nombre = $request->nombre;
            $user->nombre_completo = $request->nombre_completo;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->condicion = true;
            $user->id_rol = $request->id_rol;
            $user->distribuidoras = implode(',', $distribuidoras_id);

        $user->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'AGREGAR USUARIO';
            $historial->descripcion = 'AGREGÓ AL USUARIO ' . $user->nombre;

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

        $distribuidoras_id = array();

        foreach($request->distribuidoras as $item){

            $distribuidoras_id[] = $item['id'];

        }

        $user = User::findOrFail($request->id);

        $descripcion_historial = 'EDITÓ EL USUARIO ' . $user->nombre;

        if($user->nombre != $request->nombre){
            $descripcion_historial .= ' | nombre_viejo: ' . $user->nombre . ', nombre_nuevo: ' . $request->nombre;
        }

        if($user->nombre_completo != $request->nombre_completo){
            $descripcion_historial .= ' | nombre_completo_viejo: ' . $user->nombre_completo . ', nombre_completo_nuevo: ' . $request->nombre_completo;
        }

        if($user->email != $request->email){
            $descripcion_historial .= ' | email_viejo: ' . $user->email . ', email_nuevo: ' . $request->email;
        }

        if($request->password != null && $user->password != bcrypt($request->password)){
            $descripcion_historial .= ' | CAMBIÓ LA CONTRASEÑA';
        }

        if($user->id_rol != $request->id_rol){
            $descripcion_historial .= ' | rol_viejo: ' . Rol::findOrFail($user->id_rol)->nombre . ', rol_nuevo: ' . Rol::findOrFail($request->id_rol)->nombre;
        }

        if($user->distribuidoras != implode(',', $distribuidoras_id)){
            $descripcion_historial .= ' | CAMBIÓ DISTRIBUIDORAS ';
        }

        $user->nombre = $request->nombre;
        $user->nombre_completo = $request->nombre_completo;
        $user->email = $request->email;
        if($request->password != null) $user->password = bcrypt($request->password);
        $user->condicion = true;
        $user->id_rol = $request->id_rol;
        $user->distribuidoras = implode(',', $distribuidoras_id);

        $user->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'EDITAR USUARIO';
            $historial->descripcion = $descripcion_historial;

        $historial->save();

    }

    public function desactivar(Request $request)
    {
        /**
         * Cambiar el estado de un usuario a Desactivado
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $user = User::findOrFail($request->id);

            $user->condicion = false;

        $user->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'DESACTIVAR USUARIO';
            $historial->descripcion = 'DESACTIVÓ AL USUARIO ' . $user->nombre;

        $historial->save();

    }

    public function activar(Request $request)
    {
        /**
         * Cambiar el estado de un usuario a Activado
         * Se almacena en el historial.
         */

        if(!$request->ajax()) return redirect('/');

        $user = User::findOrFail($request->id);

            $user->condicion = true;

        $user->save();

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'ACTIVAR USUARIO';
            $historial->descripcion = 'ACTIVÓ AL USUARIO ' . $user->nombre;

        $historial->save();

    }

    public function unico($nombre)
    {
        /**
         * Verifica que el nombre de usuario ingresado 
         * cuando se esta creando un registro de
         * usuario no exista en la BD del sistema.
         * Se almacena en el historial. 
         */

        $user = User::where('nombre', $nombre)->get();

        if($user->isEmpty()){
            return "si";
        }else{
            return "no";
        }

    }

    public function unico2($nombre, $user_id)
    {
        /**
         * Verifica que el nombre de usuario ingresado 
         * cuando se esta actualizando un registro de 
         * usuario no exista en la BD del sistema. 
         * Se almacena en el historial.
         */

        $user = User::where('nombre', $nombre)->first();

        if($user == null){
            return "si";
        }elseif($user->id == $user_id){
            return "si";
        }else{
            return "no";
        }

    }

    public function unico3($email)
    {
        /**
         * Verifica que el email del usuario ingresado 
         * cuando se esta creando un registro de 
         * usuario no exista en la BD del sistema. 
         * Se almacena en el historial.
         */

        $user = User::where('email', $email)->get();

        if($user->isEmpty()){
            return "si";
        }else{
            return "no";
        }
        
    }

    public function unico4($email, $user_id)
    {
        /**
         * Verifica que el email de usuario ingresado 
         * cuando se esta actualizando un registro de 
         * usuario no exista en la BD del sistema. 
         * Se almacena en el historial.
         */

        $user = User::where('email', $email)->first();

        if($user == null){
            return "si";
        }elseif($user->id == $user_id){
            return "si";
        }else{
            return "no";
        }

    }
}
