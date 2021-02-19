<?php

namespace App\Http\Controllers;

use App\Vehiculo;
use App\Rendimiento;
use App\Mantenimiento;
use App\Combustible;
use App\Taller;
use App\Distribuidora;
use App\User;

use Auth;

use Illuminate\Http\Request;

class PrincipalController extends Controller
{
    public function index(Request $request)
    {
        /**
         * Cuenta los registros en algunas tablas para mostrar al usuario 
         * al momento de entrar al sistema 
         */

        $distribuidoras = explode(',', Auth::user()->distribuidoras);

        $cantidadCombustibles = Combustible::count();
        $cantidadTalleres = Taller::count();
        $cantidadDistribuidoras = Distribuidora::count();
        $cantidadRendimientos = Rendimiento::join('vehiculos', 'rendimientos.id_vehiculo', '=', 'vehiculos.id')
                                            ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->count();
        $cantidadUsuarios = User::count();
        $cantidadVehiculos = Vehiculo::join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                    ->whereIn('distribuidoras.id', $distribuidoras)->count();
        $cantidadMantenimientos = Mantenimiento::join('vehiculos', 'mantenimientos.id_vehiculo', '=', 'vehiculos.id')
                                                ->join('distribuidoras', 'vehiculos.id_distribuidora', '=', 'distribuidoras.id')
                                                ->whereIn('distribuidoras.id', $distribuidoras)
                                                ->count();

        return [
            'cantidadCombustibles' => $cantidadCombustibles,
            'cantidadTalleres' => $cantidadTalleres,
            'cantidadDistribuidoras' => $cantidadDistribuidoras,
            'cantidadRendimientos' => $cantidadRendimientos,
            'cantidadUsuarios' => $cantidadUsuarios,
            'cantidadVehiculos' => $cantidadVehiculos,
            'cantidadMantenimientos' => $cantidadMantenimientos,
        ];

    }
}
