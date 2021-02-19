<?php

namespace App\Imports;

use Auth;

use App\Taller;
use App\Vehiculo;
use App\CostoRutina;
use App\Distribuidora;

use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class VehiculosImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{

    use Importable, SkipsFailures;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //dd($row);
        $distribuidora = Distribuidora::where('nombre', $row['distribuidora'])->first();
        $taller = Taller::where('nombre', $row['taller'])->first();

        $vehiculos_registrados = Vehiculo::where('modelo', $row['modelo'])
                                        ->where('id_distribuidora', $distribuidora->id)
                                        ->first();

                                        //dd($vehiculos_registrados);

        if($vehiculos_registrados == null){

            $costo_rutina = new CostoRutina();
            $costo_rutina->modelo = $row['modelo'];
            $costo_rutina->rutina = 'A';
            $costo_rutina->costo = 0.00;
            $costo_rutina->id_user = Auth::user()->id;
            $costo_rutina->id_distribuidora = $distribuidora->id;
            $costo_rutina->save();

            $costo_rutina = new CostoRutina();
            $costo_rutina->modelo = $row['modelo'];
            $costo_rutina->rutina = 'B';
            $costo_rutina->costo = 0.00;
            $costo_rutina->id_user = Auth::user()->id;
            $costo_rutina->id_distribuidora = $distribuidora->id;
            $costo_rutina->save();

            $costo_rutina = new CostoRutina();
            $costo_rutina->modelo = $row['modelo'];
            $costo_rutina->rutina = 'C';
            $costo_rutina->costo = 0.00;
            $costo_rutina->id_user = Auth::user()->id;
            $costo_rutina->id_distribuidora = $distribuidora->id;
            $costo_rutina->save();

            /*
            $costo_rutina = new CostoRutina();
            $costo_rutina->modelo = $row['modelo'];
            $costo_rutina->rutina = 'D';
            $costo_rutina->costo = 0.00;
            $costo_rutina->id_user = Auth::user()->id;
            $costo_rutina->id_distribuidora = $distribuidora->id;
            $costo_rutina->save();
            */

        }
        
        if($row['placa'] == null){

            return new Vehiculo([
                //
                'codigo_comb' => $row['codigo_comb'],
                'numero_vehiculo' => $row['numero_vehiculo'],
                'marca' => $row['marca'],
                'modelo' => $row['modelo'],
                'anio' => strval($row['anio']),
                'id_distribuidora' => $distribuidora->id,
                'conductor' => $row['conductor'],
                'condicion' => true,
                'id_taller' => $taller->id,
                'periodo_mtto_kms' => $row['periodo_mtto_kms'],
                'periodo_mtto_meses' => $row['periodo_mtto_meses'],
                'ruta' => $row['ruta'],
                'propietario' => $row['propietario']
            ]);

        }else if($row['codigo_comb'] == null){

            return new Vehiculo([
                //
                'placa' => $row['placa'],
                'numero_vehiculo' => $row['numero_vehiculo'],
                'marca' => $row['marca'],
                'modelo' => $row['modelo'],
                'anio' => strval($row['anio']),
                'id_distribuidora' => $distribuidora->id,
                'conductor' => $row['conductor'],
                'condicion' => true,
                'id_taller' => $taller->id,
                'periodo_mtto_kms' => $row['periodo_mtto_kms'],
                'periodo_mtto_meses' => $row['periodo_mtto_meses'],
                'ruta' => $row['ruta'],
                'propietario' => $row['propietario']
            ]);

        }else if($row['marca'] == null){

            return new Vehiculo([
                //
                'codigo_comb' => $row['codigo_comb'],
                'placa' => $row['placa'],
                'numero_vehiculo' => $row['numero_vehiculo'],
                'modelo' => $row['modelo'],
                'anio' => strval($row['anio']),
                'id_distribuidora' => $distribuidora->id,
                'conductor' => $row['conductor'],
                'condicion' => true,
                'id_taller' => $taller->id,
                'periodo_mtto_kms' => $row['periodo_mtto_kms'],
                'periodo_mtto_meses' => $row['periodo_mtto_meses'],
                'ruta' => $row['ruta'],
                'propietario' => $row['propietario']
            ]);

        }else if($row['modelo'] == null){

            return new Vehiculo([
                //
                'codigo_comb' => $row['codigo_comb'],
                'placa' => $row['placa'],
                'numero_vehiculo' => $row['numero_vehiculo'],
                'marca' => $row['marca'],
                'anio' => strval($row['anio']),
                'id_distribuidora' => $distribuidora->id,
                'conductor' => $row['conductor'],
                'condicion' => true,
                'id_taller' => $taller->id,
                'periodo_mtto_kms' => $row['periodo_mtto_kms'],
                'periodo_mtto_meses' => $row['periodo_mtto_meses'],
                'ruta' => $row['ruta'],
                'propietario' => $row['propietario']
            ]);

        }else if($row['anio'] == null){

            return new Vehiculo([
                //
                'codigo_comb' => $row['codigo_comb'],
                'placa' => $row['placa'],
                'numero_vehiculo' => $row['numero_vehiculo'],
                'marca' => $row['marca'],
                'modelo' => $row['modelo'],
                'id_distribuidora' => $distribuidora->id,
                'conductor' => $row['conductor'],
                'condicion' => true,
                'id_taller' => $taller->id,
                'periodo_mtto_kms' => $row['periodo_mtto_kms'],
                'periodo_mtto_meses' => $row['periodo_mtto_meses'],
                'ruta' => $row['ruta'],
                'propietario' => $row['propietario']
            ]);

        }else if($row['conductor'] == null){

            return new Vehiculo([
                //
                'codigo_comb' => $row['codigo_comb'],
                'placa' => $row['placa'],
                'numero_vehiculo' => $row['numero_vehiculo'],
                'modelo' => $row['modelo'],
                'anio' => strval($row['anio']),
                'id_distribuidora' => $distribuidora->id,
                'marca' => $row['marca'],
                'condicion' => true,
                'id_taller' => $taller->id,
                'periodo_mtto_kms' => $row['periodo_mtto_kms'],
                'periodo_mtto_meses' => $row['periodo_mtto_meses'],
                'ruta' => $row['ruta'],
                'propietario' => $row['propietario']
            ]);

        }else if($row['periodo_mtto_kms'] == null){

            return new Vehiculo([
                //
                'codigo_comb' => $row['codigo_comb'],
                'placa' => $row['placa'],
                'numero_vehiculo' => $row['numero_vehiculo'],
                'modelo' => $row['modelo'],
                'anio' => strval($row['anio']),
                'id_distribuidora' => $distribuidora->id,
                'conductor' => $row['conductor'],
                'condicion' => true,
                'id_taller' => $taller->id,
                'marca' => $row['marca'],
                'periodo_mtto_meses' => $row['periodo_mtto_meses'],
                'ruta' => $row['ruta'],
                'propietario' => $row['propietario']
            ]);

        }else if($row['periodo_mtto_meses'] == null){

            return new Vehiculo([
                //
                'codigo_comb' => $row['codigo_comb'],
                'placa' => $row['placa'],
                'numero_vehiculo' => $row['numero_vehiculo'],
                'modelo' => $row['modelo'],
                'anio' => strval($row['anio']),
                'id_distribuidora' => $distribuidora->id,
                'conductor' => $row['conductor'],
                'condicion' => true,
                'id_taller' => $taller->id,
                'periodo_mtto_kms' => $row['periodo_mtto_kms'],
                'marca' => $row['marca'],
                'ruta' => $row['ruta'],
                'propietario' => $row['propietario']
            ]);

        }else if($row['ruta'] == null){

            return new Vehiculo([
                //
                'codigo_comb' => $row['codigo_comb'],
                'placa' => $row['placa'],
                'numero_vehiculo' => $row['numero_vehiculo'],
                'modelo' => $row['modelo'],
                'anio' => strval($row['anio']),
                'id_distribuidora' => $distribuidora->id,
                'conductor' => $row['conductor'],
                'condicion' => true,
                'id_taller' => $taller->id,
                'periodo_mtto_kms' => $row['periodo_mtto_kms'],
                'periodo_mtto_meses' => $row['periodo_mtto_meses'],
                'marca' => $row['marca'],
                'propietario' => $row['propietario']
            ]);

        }else if($row['propietario'] == null){

            return new Vehiculo([
                //
                'codigo_comb' => $row['codigo_comb'],
                'placa' => $row['placa'],
                'numero_vehiculo' => $row['numero_vehiculo'],
                'modelo' => $row['modelo'],
                'anio' => strval($row['anio']),
                'id_distribuidora' => $distribuidora->id,
                'conductor' => $row['conductor'],
                'condicion' => true,
                'id_taller' => $taller->id,
                'periodo_mtto_kms' => $row['periodo_mtto_kms'],
                'periodo_mtto_meses' => $row['periodo_mtto_meses'],
                'marca' => $row['marca'],
                'ruta' => $row['ruta'],
            ]);

        }else{

            return new Vehiculo([
                //
                'codigo_comb' => $row['codigo_comb'],
                'placa' => $row['placa'],
                'numero_vehiculo' => $row['numero_vehiculo'],
                'marca' => $row['marca'],
                'modelo' => $row['modelo'],
                'anio' => strval($row['anio']),
                'id_distribuidora' => $distribuidora->id,
                'conductor' => $row['conductor'],
                'condicion' => true,
                'id_taller' => $taller->id,
                'periodo_mtto_kms' => $row['periodo_mtto_kms'],
                'periodo_mtto_meses' => $row['periodo_mtto_meses'],
                'ruta' => $row['ruta'],
                'propietario' => $row['propietario']
            ]);

        }

        
    }

    public function rules(): array
    {

        $arreglo_talleres = array();
        $arreglo_distribuidoras = array();
        $user = Auth::user()->distribuidoras;
        $user_distri = explode(',', $user);
        $talleres = Taller::all();
        $distribuidoras = Distribuidora::whereIn('id', $user_distri)->get();

        foreach($distribuidoras as $distribuidora){
            $arreglo_distribuidoras[] = $distribuidora->nombre;
        }

        foreach($talleres as $taller){
            $arreglo_talleres[] = $taller->nombre;
        }

        $propietarios = array('ALQUILER', 'DIANA', 'HINORENT', 'PARTICULAR');

        return [
            'codigo_comb' => ['required', 'unique:vehiculos'],
            'placa' => ['required', 'unique:vehiculos'],
            'marca' => ['required'],
            'modelo' => ['required'],
            'anio' => ['required', 'integer'],
            'conductor' => ['required'],
            'periodo_mtto_meses' => ['required', 'integer'],
            'periodo_mtto_kms' => ['required', 'integer'],
            'ruta' => ['required'],
            'distribuidora' => ['required', Rule::in($arreglo_distribuidoras)],
            'taller' => ['required', Rule::in($arreglo_talleres)],
            'propietario' => ['required', Rule::in($propietarios)],
        ];
    }

        /**
     * @return array
     */
    public function customValidationMessages()
    {
        return [
            'codigo_comb.unique' => 'Ya esta registrado en este sistema.',
            'placa.unique' => 'Ya esta registrada en este sistema.',
            'anio.integer' => 'Debe ser un numero entero.',
            'distribuidora.in' => 'Solo se permite ingresar vehiculos de distribuidoras autorizadas al usuario.',
            'taller.in' => 'No existe en los registros del sistema.',
            'propietario.in' => 'Solo se permiten ingresar propietarios validos en el sistema.'
        ];
    }

}
