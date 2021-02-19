<?php

namespace App\Rules;

use App\Rendimiento;
use App\Vehiculo;

use Auth;

use Illuminate\Contracts\Validation\Rule;

class FechaIngresoDatos implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $distribuidoras = explode(',', Auth::user()->distribuidoras);
        $rendimiento_anterior = Rendimiento::join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                            ->whereIn('distribuidoras.id', $distribuidoras)
                                            ->orderBy('fecha', 'desc')
                                            ->first();

        if($value == null){

            return true;

        }

        if($rendimiento_anterior != null){
            
            if(is_int($value)){

                $fecha_anterior = $rendimiento_anterior->fecha . ' 18:00:00';
                $fecha_excel = (strtotime($fecha_anterior) / 86400) + 25568;
                
                return $fecha_excel <= $value;

            }else{

                $fecha_anterior = $rendimiento_anterior->fecha;
                return $fecha_anterior <= $value;

            }

        }else{

            return true;

        }
        

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'No debe ser una fecha anterior a la última registrada en el sistema.';
    }
}
