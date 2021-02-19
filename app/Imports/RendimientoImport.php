<?php

namespace App\Imports;

use App\Vehiculo;
use App\Rendimiento;
use App\Combustible;

use App\Rules\FechaIngresoDatos;
use App\Rules\NumeroUnico;

use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use Auth;

class RendimientoImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        
        $vehiculo = Vehiculo::where('codigo_comb', $row['vehiculo'])->first();
        $combustible = Combustible::where('nombre', $row['combustible'])->first();

        $id_vehiculo = $vehiculo->id;
        $id_combustible = $combustible->id;

        $rendimiento_anterior = Rendimiento::where('id_vehiculo', $id_vehiculo)->orderBy('id', 'desc')->first();

        if($rendimiento_anterior == null){

            $recorrido = 0;
            $rendimiento = 0;
            $status = 'OK - 1';

        }else{

            $recorrido = (int) $row['kilometraje'] - $rendimiento_anterior->kilometraje;

            if($row['cantidad_galones'] != 0){
                $rendimiento = round(($recorrido / $row['cantidad_galones']), 0, PHP_ROUND_HALF_UP);
            }else{
                $rendimiento = 0;
            }

            if($rendimiento <= 100 && $rendimiento >= 0){
                $status = 'OK';
            }else{
                $status = 'OK - ILOGICO';
            }

        }

        if(is_int($row['fecha'])){
            $conversion = ($row['fecha'] - 25568) * 86400;
            $fecha = date('Y-m-d', $conversion);   
        }else{
            $fecha = $row['fecha'];
        }
        
        $vehiculo = Vehiculo::findOrFail($id_vehiculo);

            $vehiculo->ulto_km = $fecha;
            $vehiculo->km = (int) $row['kilometraje'];

        $vehiculo->save();

        return new Rendimiento([
            
            'fecha' => $fecha,
            'id_vehiculo' => $id_vehiculo,
            'id_data' => $row['no_autorizacion'],
            'id_combustible' => $id_combustible,
            'kilometraje' => (int) $row['kilometraje'],
            'cantidad_galones' => (double) $row['cantidad_galones'],
            'valor' => (double) $row['valor'],
            'recorrido' => $recorrido,
            'rendimiento' => $rendimiento,
            'status' => $status
    
        ]);
        
    }

    public function rules(): array
    {

        $distribuidoras = Auth::user()->distribuidoras;
        $distribuidoras = explode(',', $distribuidoras);
        $vehiculos = Vehiculo::where('condicion', true)->whereIn('id_distribuidora', $distribuidoras)->get();
        $combustibles = Combustible::all();

        $arreglo_vehiculos = array();
        $arreglo_combustibles = array();

        foreach($vehiculos as $vehiculo){
            $arreglo_vehiculos[] = $vehiculo->codigo_comb;
        }

        foreach($combustibles as $combustible){
            $arreglo_combustibles[] = $combustible->nombre;
        }

        $rendimiento_anterior = Rendimiento::orderBy('fecha', 'desc')->first();

        return [

            'vehiculo' => ['required', Rule::in($arreglo_vehiculos)],
            'fecha' => ['required', new FechaIngresoDatos],
            'no_autorizacion' => ['required', new NumeroUnico],
            'combustible' => ['required', Rule::in($arreglo_combustibles)],
            'kilometraje' => ['required', 'integer'],
            'cantidad_galones' => ['required', 'numeric'],
            'valor' => ['required', 'numeric']

        ];
    }

        /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'vehiculo.in' => 'No existe en los registros del sistema o está desactivado o no pertenece a la flota de tu distribuidora.',
            'no_autorizacion.unique' => 'Ya está registrado en el sistema',
            'combustible.in' => 'No existe en los registros del sistema',
            'kilometraje.integer' => 'Debe ser un numero entero',
            'cantidad_galones.numeric' => 'Debe ser un valor numérico',
            'valor.numeric' => 'Debe ser un valor numérico'
        ];
    }
}
