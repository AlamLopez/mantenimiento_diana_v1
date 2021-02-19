<?php

namespace App\Imports;

use App\Mantenimiento;
use App\Vehiculo;
use App\Rutina;
use App\Semaforo;

use App\Rules\FechaIngresoMantenimientos;

use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

use Auth;

class MantenimientosImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        //dd($row);

        $vehiculo = Vehiculo::where('placa', $row['placa'])->first();
        //dd($vehiculo);
        $rutina = Rutina::where('nombre', $row['rutina'])->first();

        $id_vehiculo = $vehiculo->id;
        $id_rutina_anterior = $rutina->id;
        $nombre_rutina = $rutina->nombre;

        $mantenimiento_anterior = Mantenimiento::where('id_vehiculo', $id_vehiculo)->orderBy('id', 'desc')->first();
        //dd($mantenimiento_anterior);

        if(is_int($row['ulto_mtto'])){
            $conversion = ($row['ulto_mtto'] - 25568) * 86400;
            $fecha = date('Y-m-d', $conversion);   
        }else{
            $fecha = $row['ulto_mtto'];
        }

        if($id_rutina_anterior < 8){
            $id_rutina_nueva = $id_rutina_anterior + 1;
        }else{
            $id_rutina_nueva = 1;
        }

        $rutina_nueva = Rutina::where('id', $id_rutina_nueva)->first();

            $mantenimiento_nuevo = new Mantenimiento();
            $mantenimiento_nuevo->id_vehiculo = $id_vehiculo;
            $mantenimiento_nuevo->fecha = $fecha;
            $mantenimiento_nuevo->kilometraje = $row['km_ulto_mtto'];
            $mantenimiento_nuevo->nombre_rutina_anterior = $nombre_rutina;
            $mantenimiento_nuevo->nombre_rutina_nueva = $rutina_nueva->nombre;
            $mantenimiento_nuevo->save();
                
            $semaforo = Semaforo::where('id_vehiculo', $id_vehiculo)->first();
            $semaforo->id_mantenimiento = $mantenimiento_nuevo->id;
            $semaforo->save();

            return $mantenimiento_nuevo; 
        

        
    }

    public function rules(): array
    {

        $distribuidoras = Auth::user()->distribuidoras;
        $distribuidoras = explode(',', $distribuidoras);
        $vehiculos = Vehiculo::where('condicion', true)->whereIn('id_distribuidora', $distribuidoras)->get();
        $rutinas = Rutina::all();

        $arreglo_vehiculos = array();
        $arreglo_rutinas = array();

        foreach($vehiculos as $vehiculo){
            $arreglo_vehiculos[] = $vehiculo->placa;
        }

        foreach($rutinas as $rutina){
            $arreglo_rutinas[] = $rutina->nombre;
        }

        //dd($arreglo_rutinas);

        return [

            'placa' => ['required', Rule::in($arreglo_vehiculos)],
            'ulto_mtto' => ['required', new FechaIngresoMantenimientos],
            'km_ulto_mtto' => ['required', 'integer'],
            'rutina' => ['required', Rule::in($arreglo_rutinas)]

        ];
    }

        /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'placa.in' => 'No existe en los registros del sistema o está desactivado o no pertenece a la flota de tu distribuidora.',
            'rutina.in' => 'No existe en los registros del sistema',
            'km_ulto_mtto.integer' => 'Debe ser un numero entero',
            'km_ulto_mtto.required' => 'El campo kilometraje es requerido y debe ser mayor al ultimo kilometraje ingresado para ese vehículo',
            'rutina.required' => 'El campo rutina es requerido y debe seguir el orden establecido a partir del ultimo mantenimiento.'

        ];
    }

}
