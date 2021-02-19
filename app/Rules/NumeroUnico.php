<?php

namespace App\Rules;

use Auth;
use App\Rendimiento;

use Illuminate\Contracts\Validation\Rule;

class NumeroUnico implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

        $rendimiento = Rendimiento::join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                                    ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                    ->where('id_data', $value)
                                    ->whereNotIn('id_data', ['N/A'])
                                    ->whereIn('distribuidoras.id', $distribuidoras)
                                    ->get();

        if($rendimiento->isEmpty()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'El número de autorización ya está registrado para esta distribuidora.';
    }
}
