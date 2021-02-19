<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;

use App\Exports\ReporteRecorridoExport;
use App\Exports\ReporteMensualExport;
use App\Exports\ReporteAnualExport;
use App\Exports\ReporteCumplimientosExport;
use App\Exports\PresupuestoExport;

use Maatwebsite\Excel\Facades\Excel;

use App\Historial;
use App\Vehiculo;
use App\Distribuidora;
use App\Rendimiento;
use App\Mantenimiento;
use App\Semaforo;
use App\CostoRutina;
use App\CostoRepuesto;
use App\Taller;

use Auth;
use DateTime;

use Barryvdh\DomPDF\Facade as PDF;

class ReporteriaController extends Controller
{
    //
    public function inventarioFlota(Request $request)
    {

        $data = $request->data;
     
        $data = PDF::loadView('reportes.inventario-flota', compact('data'))
                    ->setPaper('letter', 'landscape')
                    ->save(storage_path('app/public/') . 'inventario-flota.pdf');
        
    }

    public function inventarioFlotaDescargar()
    {
        $file = storage_path() . '/app/public/inventario-flota.pdf';
 
        $headers = [
            'Content-Type' => 'application/pdf',
        ];

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'DESCARGAR REPORTE';
            $historial->descripcion = 'DESCARGÓ REPORTE DE INVENTARIO DE FLOTA';

        $historial->save();

        return response()->download($file, 'inventario-flota.pdf', $headers);
    }

    //
    public function ingresoDatos(Request $request)
    {

        $data = $request->data;
     
        $data = PDF::loadView('reportes.ingreso-datos', compact('data'))
                    ->setPaper('letter', 'landscape')
                    ->save(storage_path('app/public/') . 'ingreso-datos.pdf');
        
    }

    public function ingresoDatosDescargar()
    {
        $file = storage_path() . '/app/public/ingreso-datos.pdf';
 
        $headers = [
            'Content-Type' => 'application/pdf',
        ];

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'DESCARGAR REPORTE';
            $historial->descripcion = 'DESCARGÓ REPORTE DE INGRESO DE DATOS';

        $historial->save();

        return response()->download($file, 'ingreso-datos.pdf', $headers);
    }

    public function reporteMensual(Request $request)
    {
        
        $fecha_busqueda = $request->anio . '-' . $request->mes . '-';
        $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));
        
        setlocale(LC_TIME, 'es_ES');
        $fecha = \DateTime::createFromFormat('!m', $request->mes);
        $nombremes = strtoupper(strftime("%B", $fecha->getTimestamp()));

        $vehiculos_rendimiento = collect();
        $i = 0;

        $vehiculos = Vehiculo::with(['rendimientos' => function($q) use ($fecha_busqueda) {
            $q->where('fecha', 'like', $fecha_busqueda . '%');
        }])->where('id_distribuidora', $request->id_distribuidora)->get();

        $total_validos = 0;
        $total_invalidos = 0;

        foreach($vehiculos as $item){

            $reporte_mensual = new \stdClass();

            $recorrido = 0;
            $galones = 0;
            $costo = 0;
            $status = '';
            $validos = 0;
            $invalidos = 0;

            if($request->mes != '01'){

                $rendimiento_mes_anterior = Rendimiento::where('id_vehiculo', $item->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                if($rendimiento_mes_anterior != null){

                    $fecha1 = $rendimiento_mes_anterior->fecha;
                    $km1 = $rendimiento_mes_anterior->kilometraje;
                    $recorrido_mes_anterior = $rendimiento_mes_anterior->recorrido;
                    $galones_mes_anterior = $rendimiento_mes_anterior->cantidad_galones;
                    $costo_mes_anterior = $rendimiento_mes_anterior->valor;
                    $recorrido = $recorrido + $recorrido_mes_anterior;
                    $galones = $galones + $galones_mes_anterior;
                    $costo = $costo + $costo_mes_anterior;

                }else{
                    
                    $fecha1 = '----';
                    $km1 = 0;
                    $recorrido_mes_anterior = 0;
                    $galones_mes_anterior = 0;
                    $costo_mes_anterior = 0;

                }
                
            }else {

                if(!$item->rendimientos->isEmpty()){

                    $fecha1 = $item->rendimientos[0]->fecha;
                    $km1 = $item->rendimientos[0]->kilometraje;

                }else{

                    $fecha1 = '----';
                    $km1 = 0;
                    $recorrido_mes_anterior = 0;
                    $galones_mes_anterior = 0;
                    $costo_mes_anterior = 0;

                }

            }

            if(!$item->rendimientos->isEmpty()){

                $ultimo_rendimiento = $item->rendimientos->last();
                
                $fecha2 = $ultimo_rendimiento->fecha;
                $km2 = $ultimo_rendimiento->kilometraje;

                $contador_status = 0;
                $contador_status2 = 0;

                foreach($item->rendimientos as $data){

                    $recorrido = $recorrido + $data->recorrido;
                    $galones = $galones + $data->cantidad_galones;
                    $costo = $costo + $data->valor;

                    if($data->status == 'OK' || $data->status == 'OK - 1'){
                        
                        $contador_status++;
                        $validos++;

                    }elseif($data->status == 'OK - ILOGICO' || $data->status == 'SIN LECTURA'){
                        
                        $contador_status2++;
                        $invalidos++;

                    }
    
                }
    
                $rendimiento = $recorrido / $galones;

                if($recorrido == 0){
                    $costo_por_km = 0;
                }else{
                    $costo_por_km = $costo / $recorrido;
                }

                if(count($item->rendimientos) == $contador_status){
                    
                    $status = 'OK';
                    $total_validos++;

                }elseif(count($item->rendimientos) == $contador_status2){
                    
                    $status = 'DATA ERROR';
                    $total_invalidos++;

                }else{
                    
                    $status = 'ERROR DATA';
                    $total_invalidos++;

                }

            }else{

                $fecha2 = '----';
                $km2 = 0;
                $rendimiento = 0;
                $costo_por_km = 0;
                $status = 'SIN INF';
                $total_validos++;

            }

            //dd($rendimiento);
            
            $reporte_mensual->correlativo = $i++;
            $reporte_mensual->placa = $item->placa;
            $reporte_mensual->marca = $item->marca;
            $reporte_mensual->modelo = $item->modelo;
            $reporte_mensual->anio = $item->anio;
            $reporte_mensual->conductor = $item->conductor;
            $reporte_mensual->ruta = $item->ruta;
            $reporte_mensual->fecha1 = $fecha1;
            $reporte_mensual->fecha2 = $fecha2;
            $reporte_mensual->km1 = $km1;
            $reporte_mensual->km2 = $km2;
            $reporte_mensual->rec = round($recorrido, 0, PHP_ROUND_HALF_UP);
            $reporte_mensual->gal = round($galones, 2, PHP_ROUND_HALF_UP);
            $reporte_mensual->costo = round($costo, 2, PHP_ROUND_HALF_UP);
            $reporte_mensual->rend = round($rendimiento, 2, PHP_ROUND_HALF_UP);
            $reporte_mensual->costoxkm = round($costo_por_km, 2 , PHP_ROUND_HALF_UP);
            $reporte_mensual->validos = $validos;
            $reporte_mensual->invalidos = $invalidos;
            $reporte_mensual->status = $status;

            $vehiculos_rendimiento->push($reporte_mensual);
            
        }

        $distribuidora = Distribuidora::findOrFail($request->id_distribuidora)->nombre;
        $porcentaje = round((($total_validos * 100) / count($vehiculos_rendimiento)), 2, PHP_ROUND_HALF_UP);

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'GENERAR REPORTE';
            $historial->descripcion = 'GENERÓ UN REPORTE MENSUAL: ' . $distribuidora . '-' . $nombremes . '-' . $request->anio;

        $historial->save();

        return [

            'reportemensual' => $vehiculos_rendimiento,
            'distribuidora' => $distribuidora,
            'nombremes' => $nombremes,
            'anio' => $request->anio,
            'datos' => count($vehiculos_rendimiento),
            'validos' => $total_validos,
            'invalidos' => $total_invalidos,
            'porcentaje' => $porcentaje,
            'total_recorridos' => round($vehiculos_rendimiento->sum('rec'), 0, PHP_ROUND_HALF_UP),
            'total_galones' => round($vehiculos_rendimiento->sum('gal'), 2, PHP_ROUND_HALF_UP),
            'total_costo' => round($vehiculos_rendimiento->sum('costo'), 2, PHP_ROUND_HALF_UP),
            'total_rend' => round($vehiculos_rendimiento->sum('rend'), 2, PHP_ROUND_HALF_UP),
            'total_costoxkm' => round($vehiculos_rendimiento->sum('costoxkm'), 2, PHP_ROUND_HALF_UP)

        ];
    }

    public function reporteMensualExportarExcel(Request $request)
    {
        $nombre_reporte = 'reporte-mensual-' . $request->distribuidora_nombre . '-' . $request->mes . '-' . $request->anio . '.xlsx';

        Excel::store(new ReporteMensualExport($request->arrayReporteMensual, $request->distribuidora_nombre, $request->mes, $request->anio, $request->total_recorridos, $request->total_galones, $request->total_costo, $request->total_costoxkm, $request->total_rend), $nombre_reporte, 'public');
    }

    public function reporteMensualDescargar(Request $request)
    {
        $nombre_reporte = 'reporte-mensual-' . $request->distribuidora_nombre . '-' . $request->mes . '-' . $request->anio . '.xlsx';
        $file = storage_path() . '/app/public/' . $nombre_reporte;
 
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'DESCARGAR REPORTE';
            $historial->descripcion = 'DESCARGÓ UN REPORTE MENSUAL: ' . $request->distribuidora_nombre . '-' . $request->mes . '-' . $request->anio;

        $historial->save();

        return response()->download($file, $nombre_reporte, $headers);
    }

    public function reporteAnual(Request $request)
    {
        $fecha_busqueda = $request->anio;

        $modelos = Vehiculo::select('modelo')->where('id_distribuidora', $request->id_distribuidora)->distinct()->get();

        $arreglo_modelos = array();

        foreach($modelos as $item){
            $arreglo_modelos[] = $item->modelo;
        }

        $rendimientos_modelo = collect();

        foreach($arreglo_modelos as $item){

            $datos_modelo = new \stdClass();
            
            $vehiculos = Vehiculo::with(['rendimientos' => function($q) use ($fecha_busqueda) {
                $q->where('fecha', 'like', $fecha_busqueda . '%');
            }])->where('modelo', $item)->where('id_distribuidora', $request->id_distribuidora)->get();

            $suma_rendimientos = 0;
            $i = 0;

            foreach($vehiculos as $data){

                if(!$data->rendimientos->isEmpty()){
                    
                    foreach($data->rendimientos as $option){
                        $suma_rendimientos = $suma_rendimientos + $option->rendimiento;
                        $i=$i+1;
                    }
                }

            }

            if($i==0){
                $promedio = 0;
            }else{
                $promedio = round(($suma_rendimientos / $i), 2, PHP_ROUND_HALF_UP); 
            }

            $datos_modelo->nombre = $item;
            $datos_modelo->promedio = $promedio;

            if($request->anio == '2018'){
                foreach($vehiculos as $data){

                    if($data->edit_usuario_meta_2018 == false){
                        $data->rendimiento_meta_2018 = $promedio;
                        $data->save();
                    }

                }

            }elseif($request->anio == '2019'){
                foreach($vehiculos as $data){

                    if($data->edit_usuario_meta_2019 == false){
                        $data->rendimiento_meta_2019 = $promedio;
                        $data->save();
                    }

                }

            }elseif($request->anio == '2020'){
                //dd('hola2');
                foreach($vehiculos as $data){

                    if($data->edit_usuario_meta_2020 == false){
                        $data->rendimiento_meta_2020 = $promedio;
                        $data->save();
                    }

                }
                

            }elseif($request->anio == '2021'){
                foreach($vehiculos as $data){

                    if($data->edit_usuario_meta_2021 == false){
                        $data->rendimiento_meta_2021 = $promedio;
                        $data->save();
                    }

                }
                
            }elseif($request->anio == '2022'){
                foreach($vehiculos as $data){

                    if($data->edit_usuario_meta_2022 == false){
                        $data->rendimiento_meta_2022 = $promedio;
                        $data->save();
                    }

                }
                
            }elseif($request->anio == '2023'){
                foreach($vehiculos as $data){

                    if($data->edit_usuario_meta_2023 == false){
                        $data->rendimiento_meta_2023 = $promedio;
                        $data->save();
                    }

                }
                
            }elseif($request->anio == '2024'){
                foreach($vehiculos as $data){

                    if($data->edit_usuario_meta_2024 == false){
                        $data->rendimiento_meta_2024 = $promedio;
                        $data->save();
                    }

                }
            }elseif($request->anio == '2025'){
                foreach($vehiculos as $data){

                    if($data->edit_usuario_meta_2025 == false){
                        $data->rendimiento_meta_2025 = $promedio;
                        $data->save();
                    }

                }
            }elseif($request->anio == '2026'){
                foreach($vehiculos as $data){

                    if($data->edit_usuario_meta_2026 == false){
                        $data->rendimiento_meta_2026 = $promedio;
                        $data->save();
                    }

                }
            }
        }
        
        //dd($arreglo_modelos);




        //////////---------------------------------------------------------------------------//////////////
        

        $vehiculos_rendimiento = collect();

        $vehiculos = Vehiculo::with(['rendimientos' => function($q) use ($fecha_busqueda) {
            $q->where('fecha', 'like', $fecha_busqueda . '%');
        }])->where('id_distribuidora', $request->id_distribuidora)->get();

        $i = 0;

        foreach($vehiculos as $item){

            if($request->anio == '2018'){
                $rendimiento_meta = $item->rendimiento_meta_2018;
            }elseif($request->anio == '2019'){
                $rendimiento_meta = $item->rendimiento_meta_2019;
            }elseif($request->anio == '2020'){
                $rendimiento_meta = $item->rendimiento_meta_2020;
            }elseif($request->anio == '2021'){
                $rendimiento_meta = $item->rendimiento_meta_2021;
            }elseif($request->anio == '2022'){
                $rendimiento_meta = $item->rendimiento_meta_2022;
            }elseif($request->anio == '2023'){
                $rendimiento_meta = $item->rendimiento_meta_2023;
            }elseif($request->anio == '2024'){
                $rendimiento_meta = $item->rendimiento_meta_2024;
            }elseif($request->anio == '2025'){
                $rendimiento_meta = $item->rendimiento_meta_2025;
            }elseif($request->anio == '2026'){
                $rendimiento_meta = $item->rendimiento_meta_2026;
            }else{
                $rendimiento_meta = 0;
            }

            $rec_enero = $costo_enero = $suma_rend_enero = $costoxkm_enero = $rend_enero = $cant_enero = $gal_enero = 0;
            $rec_febrero = $costoxkm_febrero = $costo_febrero = $rend_febrero = $suma_rend_febrero = $cant_febrero = $gal_febrero = 0;
            $rec_marzo = $costo_marzo = $costoxkm_marzo = $rend_marzo = $suma_rend_marzo = $cant_marzo = $gal_marzo = 0;
            $rec_abril = $costo_abril = $costoxkm_abril = $rend_abril = $suma_rend_abril = $cant_abril = $gal_abril = 0;
            $rec_mayo = $costo_mayo = $costoxkm_mayo = $rend_mayo = $suma_rend_mayo = $cant_mayo = $gal_mayo = 0;
            $rec_junio = $costo_junio = $costoxkm_junio = $rend_junio = $suma_rend_junio = $cant_junio = $gal_junio = 0;
            $rec_julio = $costo_julio = $costoxkm_julio = $rend_julio = $suma_rend_julio = $cant_julio = $gal_julio = 0;
            $rec_agosto = $costo_agosto = $costoxkm_agosto = $rend_agosto = $suma_rend_agosto = $cant_agosto = $gal_agosto = 0;
            $rec_septiembre = $costo_septiembre = $costoxkm_septiembre = $rend_septiembre = $suma_rend_septiembre = $cant_septiembre = $gal_septiembre = 0;
            $rec_octubre = $costo_octubre = $costoxkm_octubre = $rend_octubre = $suma_rend_octubre = $cant_octubre = $gal_octubre = 0;
            $rec_noviembre = $costo_noviembre = $costoxkm_noviembre = $rend_noviembre = $suma_rend_noviembre = $cant_noviembre = $gal_noviembre = 0;
            $rec_diciembre = $costo_diciembre = $costoxkm_diciembre = $rend_diciembre = $suma_rend_diciembre = $cant_diciembre = $gal_diciembre = 0;

            $reporte_anual = new \stdClass();

            if(!$item->rendimientos->isEmpty()){

                $recorrido_mes_anterior_febrero = $recorrido_mes_anterior_marzo = $recorrido_mes_anterior_abril = $recorrido_mes_anterior_mayo = $recorrido_mes_anterior_junio = $recorrido_mes_anterior_julio = $recorrido_mes_anterior_agosto = $recorrido_mes_anterior_septiembre = $recorrido_mes_anterior_octubre = $recorrido_mes_anterior_noviembre = $recorrido_mes_anterior_diciembre = 0;
                $galones_mes_anterior_febrero = $galones_mes_anterior_marzo = $galones_mes_anterior_abril = $galones_mes_anterior_mayo = $galones_mes_anterior_junio = $galones_mes_anterior_julio = $galones_mes_anterior_agosto = $galones_mes_anterior_septiembre = $galones_mes_anterior_octubre = $galones_mes_anterior_noviembre = $galones_mes_anterior_diciembre = 0;
                $costo_mes_anterior_febrero = $costo_mes_anterior_marzo = $costo_mes_anterior_abril = $costo_mes_anterior_mayo = $costo_mes_anterior_junio = $costo_mes_anterior_julio = $costo_mes_anterior_agosto = $costo_mes_anterior_septiembre = $costo_mes_anterior_octubre = $costo_mes_anterior_noviembre = $costo_mes_anterior_diciembre = 0;
                $rend_mes_anterior_febrero = $rend_mes_anterior_marzo = $rend_mes_anterior_abril = $rend_mes_anterior_mayo = $rend_mes_anterior_junio = $rend_mes_anterior_julio = $rend_mes_anterior_agosto = $rend_mes_anterior_septiembre = $rend_mes_anterior_octubre = $rend_mes_anterior_noviembre = $rend_mes_anterior_diciembre = 0;
                $costoxkm_mes_anterior_febrero = $costoxkm_mes_anterior_marzo = $costoxkm_mes_anterior_abril = $costoxkm_mes_anterior_mayo = $costoxkm_mes_anterior_junio = $costoxkm_mes_anterior_julio = $costoxkm_mes_anterior_agosto = $costoxkm_mes_anterior_septiembre = $costoxkm_mes_anterior_octubre = $costoxkm_mes_anterior_noviembre = $costoxkm_mes_anterior_diciembre = 0;

                foreach($item->rendimientos as $data){

                    $fecha_entero = strtotime($data->fecha);
                    $mes_fecha = date('m', $fecha_entero);

                    if($mes_fecha == "01"){
                        $rec_enero = $rec_enero + $data->recorrido;
                        $gal_enero = $gal_enero + $data->cantidad_galones;
                        $costo_enero = $costo_enero + $data->valor;
                        $suma_rend_enero = $suma_rend_enero + $data->rendimiento;
                        $cant_enero++;
                    }elseif($mes_fecha == "02"){
                        $rec_febrero = $rec_febrero + $data->recorrido;
                        $gal_febrero = $gal_febrero + $data->cantidad_galones;
                        $costo_febrero = $costo_febrero + $data->valor;
                        $suma_rend_febrero = $suma_rend_febrero + $data->rendimiento;
                        $cant_febrero++;

                        $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                            
                        $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                        $rendimiento_mes_anterior_febrero = Rendimiento::where('id_vehiculo', $item->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                        if($rendimiento_mes_anterior_febrero != null){

                            $recorrido_mes_anterior_febrero = $rendimiento_mes_anterior_febrero->recorrido;
                            $galones_mes_anterior_febrero = $rendimiento_mes_anterior_febrero->cantidad_galones;
                            $costo_mes_anterior_febrero = $rendimiento_mes_anterior_febrero->valor;
                            $rend_mes_anterior_febrero = $rendimiento_mes_anterior_febrero->rendimiento;

                        }else{
                            $recorrido_mes_anterior_febrero = $galones_mes_anterior_febrero = $costo_mes_anterior_febrero = $rend_mes_anterior_febrero = 0;
                        }

                    }
                    if($mes_fecha == "03"){
                        $rec_marzo = $rec_marzo + $data->recorrido;
                        $gal_marzo = $gal_marzo + $data->cantidad_galones;
                        $costo_marzo = $costo_marzo + $data->valor;
                        $suma_rend_marzo = $suma_rend_marzo + $data->rendimiento;
                        $cant_marzo++;

                        $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                        $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                        $rendimiento_mes_anterior_marzo = Rendimiento::where('id_vehiculo', $item->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                        if($rendimiento_mes_anterior_marzo != null){

                            $recorrido_mes_anterior_marzo = $rendimiento_mes_anterior_marzo->recorrido;
                            $galones_mes_anterior_marzo = $rendimiento_mes_anterior_marzo->cantidad_galones;
                            $costo_mes_anterior_marzo = $rendimiento_mes_anterior_marzo->valor;
                            $rend_mes_anterior_marzo = $rendimiento_mes_anterior_marzo->rendimiento;

                        }else{
                            $recorrido_mes_anterior_marzo = $galones_mes_anterior_marzo = $costo_mes_anterior_marzo = $rend_mes_anterior_marzo = 0;
                        }
                    }elseif($mes_fecha == "04"){
                        $rec_abril = $rec_abril + $data->recorrido;
                        $gal_abril = $gal_abril + $data->cantidad_galones;
                        $costo_abril = $costo_abril + $data->valor;
                        $suma_rend_abril = $suma_rend_abril + $data->rendimiento;
                        $cant_abril++;

                        $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                        $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                        $rendimiento_mes_anterior_abril = Rendimiento::where('id_vehiculo', $item->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                        if($rendimiento_mes_anterior_abril != null){

                            $recorrido_mes_anterior_abril = $rendimiento_mes_anterior_abril->recorrido;
                            $galones_mes_anterior_abril = $rendimiento_mes_anterior_abril->cantidad_galones;
                            $costo_mes_anterior_abril = $rendimiento_mes_anterior_abril->valor;
                            $rend_mes_anterior_abril = $rendimiento_mes_anterior_abril->rendimiento;

                        }else{
                            $recorrido_mes_anterior_abril = $galones_mes_anterior_abril = $costo_mes_anterior_abril = $rend_mes_anterior_abril = 0;
                        }
                    }
                    if($mes_fecha == "05"){
                        $rec_mayo = $rec_mayo + $data->recorrido;
                        $gal_mayo = $gal_mayo + $data->cantidad_galones;
                        $costo_mayo = $costo_mayo + $data->valor;
                        $suma_rend_mayo = $suma_rend_mayo + $data->rendimiento;
                        $cant_mayo++;

                        $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                        $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                        $rendimiento_mes_anterior_mayo = Rendimiento::where('id_vehiculo', $item->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                        if($rendimiento_mes_anterior_mayo != null){

                            $recorrido_mes_anterior_mayo = $rendimiento_mes_anterior_mayo->recorrido;
                            $galones_mes_anterior_mayo = $rendimiento_mes_anterior_mayo->cantidad_galones;
                            $costo_mes_anterior_mayo = $rendimiento_mes_anterior_mayo->valor;
                            $rend_mes_anterior_mayo = $rendimiento_mes_anterior_mayo->rendimiento;

                        }else{
                            $recorrido_mes_anterior_mayo = $galones_mes_anterior_mayo = $costo_mes_anterior_mayo = $rend_mes_anterior_mayo = 0;
                        }
                    }elseif($mes_fecha == "06"){
                        $rec_junio = $rec_junio + $data->recorrido;
                        $gal_junio = $gal_junio  + $data->cantidad_galones;
                        $costo_junio = $costo_junio + $data->valor;
                        $suma_rend_junio = $suma_rend_junio + $data->rendimiento;
                        $cant_junio++;

                        $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                        $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                        $rendimiento_mes_anterior_junio = Rendimiento::where('id_vehiculo', $item->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                        if($rendimiento_mes_anterior_junio != null){

                            $recorrido_mes_anterior_junio = $rendimiento_mes_anterior_junio->recorrido;
                            $galones_mes_anterior_junio = $rendimiento_mes_anterior_junio->cantidad_galones;
                            $costo_mes_anterior_junio = $rendimiento_mes_anterior_junio->valor;
                            $rend_mes_anterior_junio = $rendimiento_mes_anterior_junio->rendimiento;

                        }else{
                            $recorrido_mes_anterior_junio = $galones_mes_anterior_junio = $costo_mes_anterior_junio = $rend_mes_anterior_junio = 0;
                        }
                    }
                    if($mes_fecha == "07"){
                        $rec_julio = $rec_julio + $data->recorrido;
                        $gal_julio = $gal_julio + $data->cantidad_galones;
                        $costo_julio = $costo_julio + $data->valor;
                        $suma_rend_julio = $suma_rend_julio + $data->rendimiento;
                        $cant_julio++;

                        $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                        $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                        $rendimiento_mes_anterior_julio = Rendimiento::where('id_vehiculo', $item->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                        if($rendimiento_mes_anterior_julio != null){

                            $recorrido_mes_anterior_julio = $rendimiento_mes_anterior_julio->recorrido;
                            $galones_mes_anterior_julio = $rendimiento_mes_anterior_julio->cantidad_galones;
                            $costo_mes_anterior_julio = $rendimiento_mes_anterior_julio->valor;
                            $rend_mes_anterior_julio = $rendimiento_mes_anterior_julio->rendimiento;

                        }else{
                            $recorrido_mes_anterior_julio = $galones_mes_anterior_julio = $costo_mes_anterior_julio =  $rend_mes_anterior_julio = 0;
                        }
                    }elseif($mes_fecha == "08"){
                        $rec_agosto = $rec_agosto + $data->recorrido;
                        $gal_agosto = $gal_agosto + $data->cantidad_galones;
                        $costo_agosto = $costo_agosto + $data->valor;
                        $suma_rend_agosto = $suma_rend_agosto + $data->rendimiento;
                        $cant_agosto++;

                        $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                        $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                        $rendimiento_mes_anterior_agosto = Rendimiento::where('id_vehiculo', $item->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                        if($rendimiento_mes_anterior_agosto != null){

                            $recorrido_mes_anterior_agosto = $rendimiento_mes_anterior_agosto->recorrido;
                            $galones_mes_anterior_agosto = $rendimiento_mes_anterior_agosto->cantidad_galones;
                            $costo_mes_anterior_agosto = $rendimiento_mes_anterior_agosto->valor;
                            $rend_mes_anterior_agosto = $rendimiento_mes_anterior_agosto->rendimiento;

                        }else{
                            $recorrido_mes_anterior_agosto = $galones_mes_anterior_agosto = $costo_mes_anterior_agosto = $rend_mes_anterior_agosto = 0;
                        }
                    }
                    if($mes_fecha == "09"){
                        $rec_septiembre = $rec_septiembre + $data->recorrido;
                        $gal_septiembre = $gal_septiembre + $data->cantidad_galones;
                        $costo_septiembre = $costo_septiembre + $data->valor;
                        $suma_rend_septiembre = $suma_rend_septiembre + $data->rendimiento;
                        $cant_septiembre++;

                        $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                        $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                        $rendimiento_mes_anterior_septiembre = Rendimiento::where('id_vehiculo', $item->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                        if($rendimiento_mes_anterior_septiembre != null){

                            $recorrido_mes_anterior_septiembre = $rendimiento_mes_anterior_septiembre->recorrido;
                            $galones_mes_anterior_septiembre = $rendimiento_mes_anterior_septiembre->cantidad_galones;
                            $costo_mes_anterior_septiembre = $rendimiento_mes_anterior_septiembre->valor;
                            $rend_mes_anterior_septiembre = $rendimiento_mes_anterior_septiembre->rendimiento;

                        }else{
                            $recorrido_mes_anterior_septiembre = $galones_mes_anterior_septiembre = $costo_mes_anterior_septiembre = $rend_mes_anterior_septiembre = 0;
                        }
                    }elseif($mes_fecha == "10"){
                        $rec_octubre = $rec_octubre + $data->recorrido;
                        $gal_octubre = $gal_octubre + $data->cantidad_galones;
                        $costo_octubre = $costo_octubre + $data->valor;
                        $suma_rend_octubre = $suma_rend_octubre + $data->rendimiento;
                        $cant_octubre++;

                        $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                        $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                        $rendimiento_mes_anterior_octubre = Rendimiento::where('id_vehiculo', $data->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                        if($rendimiento_mes_anterior_octubre != null){

                            $recorrido_mes_anterior_octubre = $rendimiento_mes_anterior_octubre->recorrido;
                            $galones_mes_anterior_octubre = $rendimiento_mes_anterior_octubre->cantidad_galones;
                            $costo_mes_anterior_octubre = $rendimiento_mes_anterior_octubre->valor;
                            $rend_mes_anterior_octubre = $rendimiento_mes_anterior_octubre->rendimiento;

                        }else{
                            $recorrido_mes_anterior_octubre = $galones_mes_anterior_octubre = $costo_mes_anterior_octubre = $rend_mes_anterior_octubre = 0;
                        }
                    }
                    if($mes_fecha == "11"){
                        $rec_noviembre = $rec_noviembre + $data->recorrido;
                        $gal_noviembre = $gal_noviembre + $data->cantidad_galones;
                        $costo_noviembre = $costo_noviembre + $data->valor;
                        $suma_rend_noviembre = $suma_rend_noviembre + $data->rendimiento;
                        $cant_noviembre++;

                        $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                        $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                        $rendimiento_mes_anterior_noviembre = Rendimiento::where('id_vehiculo', $item->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                        if($rendimiento_mes_anterior_noviembre != null){

                            $recorrido_mes_anterior_noviembre = $rendimiento_mes_anterior_noviembre->recorrido;
                            $galones_mes_anterior_noviembre = $rendimiento_mes_anterior_noviembre->cantidad_galones;
                            $costo_mes_anterior_noviembre = $rendimiento_mes_anterior_noviembre->valor;
                            $rend_mes_anterior_noviembre = $rendimiento_mes_anterior_octubre->rendimiento;

                        }else{
                            $recorrido_mes_anterior_noviembre = $galones_mes_anterior_noviembre = $costo_mes_anterior_noviembre = $rend_mes_anterior_noviembre = 0;
                        }
                    }elseif($mes_fecha == "12"){
                        $rec_diciembre = $rec_diciembre + $data->recorrido;
                        $gal_diciembre = $gal_diciembre + $data->cantidad_galones;
                        $costo_diciembre = $costo_diciembre + $data->valor;
                        $suma_rend_diciembre = $suma_rend_diciembre + $data->rendimiento;
                        $cant_diciembre++;

                        $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                        $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                        $rendimiento_mes_anterior_diciembre = Rendimiento::where('id_vehiculo', $item->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                        if($rendimiento_mes_anterior_diciembre != null){

                            $recorrido_mes_anterior_diciembre = $rendimiento_mes_anterior_diciembre->recorrido;
                            $galones_mes_anterior_diciembre = $rendimiento_mes_anterior_diciembre->cantidad_galones;
                            $costo_mes_anterior_diciembre = $rendimiento_mes_anterior_diciembre->valor;
                            $rend_mes_anterior_diciembre = $rendimiento_mes_anterior_diciembre->rendimiento;

                        }else{
                            $recorrido_mes_anterior_diciembre = $galones_mes_anterior_diciembre = $costo_mes_anterior_diciembre = $rend_mes_anterior_diciembre = 0;
                        }
                    }

                }

                $rec_febrero = $rec_febrero + $recorrido_mes_anterior_febrero;
                $gal_febrero = $gal_febrero + $galones_mes_anterior_febrero;
                $costo_febrero = $costo_febrero + $costo_mes_anterior_febrero; 
                $rec_marzo = $rec_marzo + $recorrido_mes_anterior_marzo;
                $gal_marzo = $gal_marzo + $galones_mes_anterior_marzo;
                $costo_marzo = $costo_marzo + $costo_mes_anterior_marzo;
                $rec_abril = $rec_abril + $recorrido_mes_anterior_abril;
                $gal_abril = $gal_abril + $galones_mes_anterior_abril;
                $costo_abril = $costo_abril + $costo_mes_anterior_abril;
                $rec_mayo = $rec_mayo + $recorrido_mes_anterior_mayo;
                $gal_mayo = $gal_mayo + $galones_mes_anterior_mayo;
                $costo_mayo = $costo_mayo + $costo_mes_anterior_mayo;
                $rec_junio = $rec_junio + $recorrido_mes_anterior_junio;
                $gal_junio = $gal_junio + $galones_mes_anterior_junio;
                $costo_junio = $costo_junio + $costo_mes_anterior_junio;
                $rec_julio = $rec_julio + $recorrido_mes_anterior_julio;
                $gal_julio = $gal_julio + $galones_mes_anterior_julio;
                $costo_julio = $costo_julio + $costo_mes_anterior_julio;
                $rec_agosto = $rec_agosto + $recorrido_mes_anterior_agosto;
                $gal_agosto = $gal_agosto + $galones_mes_anterior_agosto;
                $costo_agosto = $costo_agosto + $costo_mes_anterior_agosto;
                $rec_septiembre = $rec_septiembre + $recorrido_mes_anterior_septiembre;
                $gal_septiembre = $gal_septiembre + $galones_mes_anterior_septiembre;
                $costo_septiembre = $costo_septiembre + $costo_mes_anterior_septiembre;
                $rec_octubre = $rec_octubre + $recorrido_mes_anterior_octubre;
                $gal_octubre = $gal_octubre + $galones_mes_anterior_octubre;
                $costo_octubre = $costo_octubre + $costo_mes_anterior_octubre;
                $rec_noviembre = $rec_noviembre + $recorrido_mes_anterior_noviembre;
                $gal_noviembre = $gal_noviembre + $galones_mes_anterior_noviembre;
                $costo_noviembre = $costo_noviembre + $costo_mes_anterior_noviembre;
                $rec_diciembre = $rec_diciembre + $recorrido_mes_anterior_diciembre;
                $gal_diciembre = $gal_diciembre + $galones_mes_anterior_diciembre;
                $costo_diciembre = $costo_diciembre + $costo_mes_anterior_diciembre;

                

                if(!$rec_enero == 0) $costoxkm_enero = round(($costo_enero / $rec_enero), 2, PHP_ROUND_HALF_UP);
                if(!$gal_enero == 0) $rend_enero = round(($rec_enero / $gal_enero), 2, PHP_ROUND_HALF_UP); 
                if(!$rec_febrero == 0) $costoxkm_febrero = round(($costo_febrero / $rec_febrero), 2, PHP_ROUND_HALF_UP);
                if(!$gal_febrero == 0) $rend_febrero = round(($rec_febrero / $gal_febrero), 2, PHP_ROUND_HALF_UP); 
                if(!$rec_marzo == 0) $costoxkm_marzo = round(($costo_marzo / $rec_marzo), 2, PHP_ROUND_HALF_UP);
                if(!$gal_marzo == 0) $rend_marzo = round(($rec_marzo / $gal_marzo), 2, PHP_ROUND_HALF_UP); 
                if(!$rec_abril == 0) $costoxkm_abril = round(($costo_abril / $rec_abril), 2, PHP_ROUND_HALF_UP);
                if(!$gal_abril == 0) $rend_abril = round(($rec_abril / $gal_abril), 2, PHP_ROUND_HALF_UP); 
                if(!$rec_mayo == 0) $costoxkm_mayo = round(($costo_mayo / $rec_mayo), 2, PHP_ROUND_HALF_UP);
                if(!$gal_mayo == 0) $rend_mayo = round(($rec_mayo / $gal_mayo), 2, PHP_ROUND_HALF_UP); 
                if(!$rec_junio == 0) $costoxkm_junio = round(($costo_junio / $rec_junio), 2, PHP_ROUND_HALF_UP);
                if(!$gal_junio == 0) $rend_junio = round(($rec_junio / $gal_junio), 2, PHP_ROUND_HALF_UP); 
                if(!$rec_julio == 0) $costoxkm_julio = round(($costo_julio / $rec_julio), 2, PHP_ROUND_HALF_UP);
                if(!$rec_julio == 0) $rend_julio = round(($rec_julio / $gal_julio), 2, PHP_ROUND_HALF_UP); 
                if(!$rec_agosto == 0) $costoxkm_agosto = round(($costo_agosto / $rec_agosto), 2, PHP_ROUND_HALF_UP);
                if(!$gal_agosto == 0) $rend_agosot = round(($rec_agosto / $gal_agosto), 2, PHP_ROUND_HALF_UP); 
                if(!$rec_septiembre == 0) $costoxkm_septiembre = round(($costo_septiembre / $rec_septiembre), 2, PHP_ROUND_HALF_UP);
                if(!$gal_septiembre == 0) $rend_septiembre = round(($rec_septiembre / $gal_septiembre), 2, PHP_ROUND_HALF_UP); 
                if(!$rec_octubre == 0) $costoxkm_octubre = round(($costo_octubre / $rec_octubre), 2, PHP_ROUND_HALF_UP);
                if(!$gal_octubre == 0) $rend_octubre = round(($rec_octubre / $gal_octubre), 2, PHP_ROUND_HALF_UP); 
                if(!$rec_noviembre == 0) $costoxkm_noviembre = round(($costo_noviembre / $rec_noviembre), 2, PHP_ROUND_HALF_UP);
                if(!$gal_noviembre == 0) $rend_noviembre = round(($rec_noviembre / $gal_noviembre), 2, PHP_ROUND_HALF_UP); 
                if(!$rec_diciembre == 0) $costoxkm_diciembre = round(($costo_diciembre / $rec_diciembre), 2, PHP_ROUND_HALF_UP);
                if(!$gal_diciembre == 0) $rend_diciembre = round(($rec_diciembre / $gal_diciembre), 2, PHP_ROUND_HALF_UP); 

            }

            $reporte_anual->correlativo = $i++;
            $reporte_anual->placa = $item->placa;
            $reporte_anual->modelo = $item->modelo;
            $reporte_anual->anio = $item->anio;
            $reporte_anual->km = $item->km;
            $reporte_anual->conductor = $item->conductor;
            $reporte_anual->ruta = $item->ruta;
            $reporte_anual->rend_meta = $rendimiento_meta;
            $reporte_anual->rec_enero = $rec_enero;
            $reporte_anual->costoxkm_enero = $costoxkm_enero;
            $reporte_anual->rend_enero = $rend_enero;
            $reporte_anual->rec_febrero = $rec_febrero;
            $reporte_anual->costoxkm_febrero = $costoxkm_febrero;
            $reporte_anual->rend_febrero = $rend_febrero;
            $reporte_anual->rec_marzo = $rec_marzo;
            $reporte_anual->costoxkm_marzo = $costoxkm_marzo;
            $reporte_anual->rend_marzo = $rend_marzo;
            $reporte_anual->rec_abril = $rec_abril;
            $reporte_anual->costoxkm_abril = $costoxkm_abril;
            $reporte_anual->rend_abril = $rend_abril;
            $reporte_anual->rec_mayo = $rec_mayo;
            $reporte_anual->costoxkm_mayo = $costoxkm_mayo;
            $reporte_anual->rend_mayo = $rend_mayo;
            $reporte_anual->rec_junio = $rec_junio;
            $reporte_anual->costoxkm_junio = $costoxkm_junio;
            $reporte_anual->rend_junio = $rend_junio;
            $reporte_anual->rec_julio = $rec_julio;
            $reporte_anual->costoxkm_julio = $costoxkm_julio;
            $reporte_anual->rend_julio = $rend_julio;
            $reporte_anual->rec_agosto = $rec_agosto;
            $reporte_anual->costoxkm_agosto = $costoxkm_agosto;
            $reporte_anual->rend_agosto = $rend_agosto;
            $reporte_anual->rec_septiembre = $rec_septiembre;
            $reporte_anual->costoxkm_septiembre = $costoxkm_septiembre;
            $reporte_anual->rend_septiembre = $rend_septiembre;
            $reporte_anual->rec_octubre = $rec_octubre;
            $reporte_anual->costoxkm_octubre = $costoxkm_octubre;
            $reporte_anual->rend_octubre = $rend_octubre;
            $reporte_anual->rec_noviembre = $rec_noviembre;
            $reporte_anual->costoxkm_noviembre = $costoxkm_noviembre;
            $reporte_anual->rend_noviembre = $rend_noviembre;
            $reporte_anual->rec_diciembre = $rec_diciembre;
            $reporte_anual->costoxkm_diciembre = $costoxkm_diciembre;
            $reporte_anual->rend_diciembre = $rend_diciembre;

            $vehiculos_rendimiento->push($reporte_anual);

        }

        $distribuidora = Distribuidora::findOrFail($request->id_distribuidora)->nombre;

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'GENERAR REPORTE';
            $historial->descripcion = 'GENERÓ UN REPORTE ANUAL: ' . $distribuidora . '-' . $request->anio;

        $historial->save();

        return [

            'reporteanual' => $vehiculos_rendimiento,
            'distribuidora_nombre' => $distribuidora,
            'nombre_anio' => $request->anio

        ];
    }

    public function reporteAnualRendimientoMeta(Request $request)
    {
        //dd($request->all());

        $vehiculo = Vehiculo::where('placa', $request->placa)->first();

        if($request->anio == '2018'){
            $vehiculo->rendimiento_meta_2018 = $request->rendimiento_meta;
            $vehiculo->edit_usuario_meta_2018 = true;
            $vehiculo->save();
        }elseif($request->anio == '2019'){
            $vehiculo->rendimiento_meta_2019 = $request->rendimiento_meta;
            $vehiculo->edit_usuario_meta_2019 = true;
            $vehiculo->save();
        }elseif($request->anio == '2020'){
            $vehiculo->rendimiento_meta_2020 = $request->rendimiento_meta;
            $vehiculo->edit_usuario_meta_2020 = true;
            $vehiculo->save();
        }elseif($request->anio == '2021'){
            $vehiculo->rendimiento_meta_2021 = $request->rendimiento_meta;
            $vehiculo->edit_usuario_meta_2021 = true;
            $vehiculo->save();
        }elseif($request->anio == '2022'){
            $vehiculo->rendimiento_meta_2022 = $request->rendimiento_meta;
            $vehiculo->edit_usuario_meta_2022 = true;
            $vehiculo->save();
        }elseif($request->anio == '2023'){
            $vehiculo->rendimiento_meta_2023 = $request->rendimiento_meta;
            $vehiculo->edit_usuario_meta_2023 = true;
            $vehiculo->save();
        }elseif($request->anio == '2024'){
            $vehiculo->rendimiento_meta_2024 = $request->rendimiento_meta;
            $vehiculo->edit_usuario_meta_2024 = true;
            $vehiculo->save();
        }elseif($request->anio == '2025'){
            $vehiculo->rendimiento_meta_2025 = $request->rendimiento_meta;
            $vehiculo->edit_usuario_meta_2025 = true;
            $vehiculo->save();
        }elseif($request->anio == '2026'){
            $vehiculo->rendimiento_meta_2026 = $request->rendimiento_meta;
            $vehiculo->edit_usuario_meta_2026 = true;
            $vehiculo->save();
        }else{
            $nada = '';
        }

        return [
            'id_distribuidora' => $vehiculo->id_distribuidora,
            'anio' => $request->anio
        ];
    }

    public function reporteAnualExportarExcel(Request $request)
    {
        $nombre_reporte = 'reporte-anual-' . $request->distribuidora_nombre . '-' . $request->anio . '.xlsx';

        Excel::store(new ReporteAnualExport($request->arrayReporteAnual, $request->distribuidora_nombre, $request->anio), $nombre_reporte, 'public');
    }

    public function reporteAnualDescargar(Request $request)
    {
        $nombre_reporte = 'reporte-anual-' . $request->distribuidora_nombre . '-' . $request->anio . '.xlsx';
        $file = storage_path() . '/app/public/' . $nombre_reporte;
 
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'DESCARGAR REPORTE';
            $historial->descripcion = 'DESCARGÓ UN REPORTE ANUAL: ' . $request->distribuidora_nombre . '-' . $request->anio;

        $historial->save();

        return response()->download($file, $nombre_reporte, $headers);
    }

    public function reporteRecorrido(Request $request)
    {
        $fecha_busqueda = $request->anio;

        $user_distribuidoras = explode(',', Auth::user()->distribuidoras);

        $vehiculos_recorrido = collect();

        $vehiculos = Vehiculo::with(['distribuidora', 'rendimientos' => function($q) use ($fecha_busqueda) {
            $q->where('fecha', 'like', $fecha_busqueda . '%');
        }])->whereIn('id_distribuidora', $user_distribuidoras)->get();

        $i = 0;

        foreach($vehiculos as $item){

            $rec_enero = $rec_febrero = $rec_marzo = $rec_abril = $rec_mayo = $rec_junio = $rec_julio = $rec_agosto = $rec_septiembre = $rec_octubre = $rec_noviembre = $rec_diciembre = 0;

            $reporte_recorrido = new \stdClass();

            if(!$item->rendimientos->isEmpty()){

                foreach($item->rendimientos as $data){

                    $fecha_entero = strtotime($data->fecha);
                    $mes_fecha = date('m', $fecha_entero);

                    if($mes_fecha == "01"){
                        $rec_enero = $rec_enero + $data->recorrido;
                    }elseif($mes_fecha == "02"){
                        $rec_febrero = $rec_febrero + $data->recorrido;
                    }elseif($mes_fecha == "03"){
                        $rec_marzo = $rec_marzo + $data->recorrido;
                    }elseif($mes_fecha == "04"){
                        $rec_abril = $rec_abril + $data->recorrido;
                    }elseif($mes_fecha == "05"){
                        $rec_mayo = $rec_mayo + $data->recorrido;
                    }elseif($mes_fecha == "06"){
                        $rec_junio = $rec_junio + $data->recorrido;
                    }elseif($mes_fecha == "07"){
                        $rec_julio = $rec_julio + $data->recorrido;
                    }elseif($mes_fecha == "08"){
                        $rec_agosto = $rec_agosto + $data->recorrido;
                    }elseif($mes_fecha == "09"){
                        $rec_septiembre = $rec_septiembre + $data->recorrido;
                    }elseif($mes_fecha == "10"){
                        $rec_octubre = $rec_octubre + $data->recorrido;
                    }elseif($mes_fecha == "11"){
                        $rec_noviembre = $rec_noviembre + $data->recorrido;
                    }elseif($mes_fecha == "12"){
                        $rec_diciembre = $rec_diciembre + $data->recorrido;
                    }

                }

                $reporte_recorrido->correlativo = $i++;
                $reporte_recorrido->placa = $item->placa;
                $reporte_recorrido->marca = $item->marca;
                $reporte_recorrido->modelo = $item->modelo;
                $reporte_recorrido->anio = $item->anio;
                $reporte_recorrido->km = $item->km;
                $reporte_recorrido->distribuidora_nombre = $item->distribuidora->nombre;
                $reporte_recorrido->rec_enero = $rec_enero;
                $reporte_recorrido->rec_febrero = $rec_febrero;
                $reporte_recorrido->rec_marzo = $rec_marzo;
                $reporte_recorrido->rec_abril = $rec_abril;
                $reporte_recorrido->rec_mayo = $rec_mayo;
                $reporte_recorrido->rec_junio = $rec_junio;
                $reporte_recorrido->rec_julio = $rec_julio;
                $reporte_recorrido->rec_agosto = $rec_agosto;
                $reporte_recorrido->rec_septiembre = $rec_septiembre;
                $reporte_recorrido->rec_octubre = $rec_octubre;
                $reporte_recorrido->rec_noviembre = $rec_noviembre;
                $reporte_recorrido->rec_diciembre = $rec_diciembre;

                $vehiculos_recorrido->push($reporte_recorrido);

            }
        }

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'GENERAR REPORTE';
            $historial->descripcion = 'GENERÓ UN REPORTE DE RECORRIDO: ' . $request->anio;

        $historial->save();

        return [

            'reporterecorrido' => $vehiculos_recorrido,
            'nombre_anio' => $request->anio

        ];
    }

    public function reporteRecorridoExportarExcel(Request $request)
    {
        $nombre_reporte = 'reporte-recorrido-' . $request->anio . '.xlsx';

        Excel::store(new ReporteRecorridoExport($request->arrayReporteRecorrido, $request->anio), $nombre_reporte, 'public');
    }

    public function reporteRecorridoDescargar(Request $request)
    {
        $nombre_reporte = 'reporte-recorrido-' . $request->anio . '.xlsx';
        $file = storage_path() . '/app/public/' . $nombre_reporte;
 
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'DESCARGAR REPORTE';
            $historial->descripcion = 'DESCARGÓ UN REPORTE DE RECORRIDOS: ' . $request->anio;

        $historial->save();

        return response()->download($file, $nombre_reporte, $headers);
    }

    public function indicadorGlobal(Request $request)
    {

        $distribuidoras = Auth::user()->distribuidoras;
        $distribuidoras = explode(',', $distribuidoras);
        $distribuidoras = Distribuidora::whereIn('id', $distribuidoras)->get();
        
        $indicadores_globales = collect();
        $i = 0;

        foreach($distribuidoras as $item){

            $fecha_busqueda = $request->anio;

            $vehiculos = Vehiculo::with(['rendimientos' => function($q) use ($fecha_busqueda) {
                $q->where('fecha', 'like', $fecha_busqueda . '%');
            }])->where('id_distribuidora', $item->id)->get();

            $indicador_distribuidora = new \stdClass();

            $rec_enero = $rec_febrero = $rec_marzo = $rec_abril = $rec_mayo = $rec_junio = $rec_julio = $rec_agosto = $rec_septiembre = $rec_octubre = $rec_noviembre = $rec_diciembre = 0;
            $gal_enero = $gal_febrero = $gal_marzo = $gal_abril = $gal_mayo = $gal_junio = $gal_julio = $gal_agosto = $gal_septiembre = $gal_octubre = $gal_noviembre = $gal_diciembre = 0;
            $costo_enero = $costo_febrero = $costo_marzo = $costo_abril = $costo_mayo = $costo_junio = $costo_julio = $costo_agosto = $costo_septiembre = $costo_octubre = $costo_noviembre = $costo_diciembre = 0;
            $rend_enero = $rend_febrero = $rend_marzo = $rend_abril = $rend_mayo = $rend_junio = $rend_julio = $rend_agosto = $rend_septiembre = $rend_octubre = $rend_noviembre = $rend_diciembre = 0;
            $costoxkm_enero = $costoxkm_febrero = $costoxkm_marzo = $costoxkm_abril = $costoxkm_mayo = $costoxkm_junio = $costoxkm_julio = $costoxkm_agosto = $costoxkm_septiembre = $costoxkm_octubre = $costoxkm_noviembre = $costoxkm_diciembre = 0;
            $cant_enero = $cant_febrero = $cant_marzo = $cant_abril = $cant_mayo = $cant_junio = $cant_julio = $cant_agosto = $cant_septiembre = $cant_octubre = $cant_noviembre = $cant_diciembre = 0;

            foreach($vehiculos as $data){    

                if(!$data->rendimientos->isEmpty()){

                    $recorrido_mes_anterior_febrero = $recorrido_mes_anterior_marzo = $recorrido_mes_anterior_abril = $recorrido_mes_anterior_mayo = $recorrido_mes_anterior_junio = $recorrido_mes_anterior_julio = $recorrido_mes_anterior_agosto = $recorrido_mes_anterior_septiembre = $recorrido_mes_anterior_octubre = $recorrido_mes_anterior_noviembre = $recorrido_mes_anterior_diciembre = 0;
                    $galones_mes_anterior_febrero = $galones_mes_anterior_marzo = $galones_mes_anterior_abril = $galones_mes_anterior_mayo = $galones_mes_anterior_junio = $galones_mes_anterior_julio = $galones_mes_anterior_agosto = $galones_mes_anterior_septiembre = $galones_mes_anterior_octubre = $galones_mes_anterior_noviembre = $galones_mes_anterior_diciembre = 0;
                    $costo_mes_anterior_febrero = $costo_mes_anterior_marzo = $costo_mes_anterior_abril = $costo_mes_anterior_mayo = $costo_mes_anterior_junio = $costo_mes_anterior_julio = $costo_mes_anterior_agosto = $costo_mes_anterior_septiembre = $costo_mes_anterior_octubre = $costo_mes_anterior_noviembre = $costo_mes_anterior_diciembre = 0;
                    $rend_mes_anterior_febrero = $rend_mes_anterior_marzo = $rend_mes_anterior_abril = $rend_mes_anterior_mayo = $rend_mes_anterior_junio = $rend_mes_anterior_julio = $rend_mes_anterior_agosto = $rend_mes_anterior_septiembre = $rend_mes_anterior_octubre = $rend_mes_anterior_noviembre = $rend_mes_anterior_diciembre = 0;
                    $costoxkm_mes_anterior_febrero = $costoxkm_mes_anterior_marzo = $costoxkm_mes_anterior_abril = $costoxkm_mes_anterior_mayo = $costoxkm_mes_anterior_junio = $costoxkm_mes_anterior_julio = $costoxkm_mes_anterior_agosto = $costoxkm_mes_anterior_septiembre = $costoxkm_mes_anterior_octubre = $costoxkm_mes_anterior_noviembre = $costoxkm_mes_anterior_diciembre = 0;

                    foreach($data->rendimientos as $option){

                        $fecha_entero = strtotime($option->fecha);
                        $mes_fecha = date('m', $fecha_entero);

                        if($mes_fecha == "01"){

                            $rec_enero = $rec_enero + $option->recorrido;
                            $gal_enero = $gal_enero + $option->cantidad_galones;
                            $costo_enero = $costo_enero + $option->valor;
                            $rend_enero = $rend_enero + $option->rendimiento;
                            $cant_enero++;

                        }elseif($mes_fecha == "02"){

                            $rec_febrero = $rec_febrero + $option->recorrido;
                            $gal_febrero = $gal_febrero + $option->cantidad_galones;
                            $costo_febrero = $costo_febrero + $option->valor;
                            $rend_febrero = $rend_febrero + $option->rendimiento;
                            $cant_febrero++;
                            
                            $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                            
                            $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                            $rendimiento_mes_anterior_febrero = Rendimiento::where('id_vehiculo', $data->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                            if($rendimiento_mes_anterior_febrero != null){

                                $recorrido_mes_anterior_febrero = $rendimiento_mes_anterior_febrero->recorrido;
                                $galones_mes_anterior_febrero = $rendimiento_mes_anterior_febrero->cantidad_galones;
                                $costo_mes_anterior_febrero = $rendimiento_mes_anterior_febrero->valor;
                                $rend_mes_anterior_febrero = $rendimiento_mes_anterior_febrero->rendimiento;

                            }else{
                                $recorrido_mes_anterior_febrero = $galones_mes_anterior_febrero = $costo_mes_anterior_febrero = $rend_mes_anterior_febrero = 0;
                            }

                        }elseif($mes_fecha == "03"){

                            $rec_marzo = $rec_marzo + $option->recorrido;
                            $gal_marzo = $gal_marzo + $option->cantidad_galones;
                            $costo_marzo = $costo_marzo + $option->valor;
                            $rend_marzo = $rend_marzo + $option->rendimiento;
                            $cant_marzo++;
                            
                            $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                            $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                            $rendimiento_mes_anterior_marzo = Rendimiento::where('id_vehiculo', $data->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                            if($rendimiento_mes_anterior_marzo != null){

                                $recorrido_mes_anterior_marzo = $rendimiento_mes_anterior_marzo->recorrido;
                                $galones_mes_anterior_marzo = $rendimiento_mes_anterior_marzo->cantidad_galones;
                                $costo_mes_anterior_marzo = $rendimiento_mes_anterior_marzo->valor;
                                $rend_mes_anterior_marzo = $rendimiento_mes_anterior_marzo->rendimiento;

                            }else{
                                $recorrido_mes_anterior_marzo = $galones_mes_anterior_marzo = $costo_mes_anterior_marzo = $rend_mes_anterior_marzo = 0;
                            }

                        }elseif($mes_fecha == "04"){

                            $rec_abril = $rec_abril + $option->recorrido;
                            $gal_abril = $gal_abril + $option->cantidad_galones;
                            $costo_abril = $costo_abril + $option->valor;
                            $rend_abril = $rend_abril + $option->rendimiento;
                            $cant_abril++;
                            
                            $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                            $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                            $rendimiento_mes_anterior_abril = Rendimiento::where('id_vehiculo', $data->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                            if($rendimiento_mes_anterior_abril != null){

                                $recorrido_mes_anterior_abril = $rendimiento_mes_anterior_abril->recorrido;
                                $galones_mes_anterior_abril = $rendimiento_mes_anterior_abril->cantidad_galones;
                                $costo_mes_anterior_abril = $rendimiento_mes_anterior_abril->valor;
                                $rend_mes_anterior_abril = $rendimiento_mes_anterior_abril->rendimiento;

                            }else{
                                $recorrido_mes_anterior_abril = $galones_mes_anterior_abril = $costo_mes_anterior_abril = $rend_mes_anterior_abril = 0;
                            }
                            
                        }elseif($mes_fecha == "05"){

                            $rec_mayo = $rec_mayo + $option->recorrido;
                            $gal_mayo = $gal_mayo + $option->cantidad_galones;
                            $costo_mayo = $costo_mayo + $option->valor;
                            $rend_mayo = $rend_mayo + $option->rendimiento;
                            $cant_mayo++;
                            
                            $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                            $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                            $rendimiento_mes_anterior_mayo = Rendimiento::where('id_vehiculo', $data->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                            if($rendimiento_mes_anterior_mayo != null){

                                $recorrido_mes_anterior_mayo = $rendimiento_mes_anterior_mayo->recorrido;
                                $galones_mes_anterior_mayo = $rendimiento_mes_anterior_mayo->cantidad_galones;
                                $costo_mes_anterior_mayo = $rendimiento_mes_anterior_mayo->valor;
                                $rend_mes_anterior_mayo = $rendimiento_mes_anterior_mayo->rendimiento;

                            }else{
                                $recorrido_mes_anterior_mayo = $galones_mes_anterior_mayo = $costo_mes_anterior_mayo = $rend_mes_anterior_mayo = 0;
                            }

                        }elseif($mes_fecha == "06"){

                            $rec_junio = $rec_junio + $option->recorrido;
                            $gal_junio = $gal_junio + $option->cantidad_galones;
                            $costo_junio = $costo_junio + $option->valor;
                            $rend_junio = $rend_junio + $option->rendimiento;
                            $cant_junio++;
                            
                            $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                            $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                            $rendimiento_mes_anterior_junio = Rendimiento::where('id_vehiculo', $data->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                            if($rendimiento_mes_anterior_junio != null){

                                $recorrido_mes_anterior_junio = $rendimiento_mes_anterior_junio->recorrido;
                                $galones_mes_anterior_junio = $rendimiento_mes_anterior_junio->cantidad_galones;
                                $costo_mes_anterior_junio = $rendimiento_mes_anterior_junio->valor;
                                $rend_mes_anterior_junio = $rendimiento_mes_anterior_junio->rendimiento;

                            }else{
                                $recorrido_mes_anterior_junio = $galones_mes_anterior_junio = $costo_mes_anterior_junio = $rend_mes_anterior_junio = 0;
                            }

                        }elseif($mes_fecha == "07"){

                            $rec_julio = $rec_julio + $option->recorrido;
                            $gal_julio = $gal_julio + $option->cantidad_galones;
                            $costo_julio = $costo_julio + $option->valor;
                            $rend_julio = $rend_julio + $option->rendimiento;
                            $cant_julio++;
                            
                            $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                            $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                            $rendimiento_mes_anterior_julio = Rendimiento::where('id_vehiculo', $data->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                            if($rendimiento_mes_anterior_julio != null){

                                $recorrido_mes_anterior_julio = $rendimiento_mes_anterior_julio->recorrido;
                                $galones_mes_anterior_julio = $rendimiento_mes_anterior_julio->cantidad_galones;
                                $costo_mes_anterior_julio = $rendimiento_mes_anterior_julio->valor;
                                $rend_mes_anterior_julio = $rendimiento_mes_anterior_julio->rendimiento;

                            }else{
                                $recorrido_mes_anterior_julio = $galones_mes_anterior_julio = $costo_mes_anterior_julio =  $rend_mes_anterior_julio = 0;
                            }

                        }elseif($mes_fecha == "08"){

                            $rec_agosto = $rec_agosto + $option->recorrido;
                            $gal_agosto = $gal_agosto + $option->cantidad_galones;
                            $costo_agosto = $costo_agosto + $option->valor;
                            $rend_agosto = $rend_agosto + $option->rendimiento;
                            $cant_agosto++;
                            
                            $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                            $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                            $rendimiento_mes_anterior_agosto = Rendimiento::where('id_vehiculo', $data->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                            if($rendimiento_mes_anterior_agosto != null){

                                $recorrido_mes_anterior_agosto = $rendimiento_mes_anterior_agosto->recorrido;
                                $galones_mes_anterior_agosto = $rendimiento_mes_anterior_agosto->cantidad_galones;
                                $costo_mes_anterior_agosto = $rendimiento_mes_anterior_agosto->valor;
                                $rend_mes_anterior_agosto = $rendimiento_mes_anterior_agosto->rendimiento;

                            }else{
                                $recorrido_mes_anterior_agosto = $galones_mes_anterior_agosto = $costo_mes_anterior_agosto = $rend_mes_anterior_agosto = 0;
                            }

                        }elseif($mes_fecha == "09"){

                            $rec_septiembre = $rec_septiembre + $option->recorrido;
                            $gal_septiembre = $gal_septiembre + $option->cantidad_galones;
                            $costo_septiembre = $costo_septiembre + $option->valor;
                            $rend_septiembre = $rend_septiembre + $option->rendimiento;
                            $cant_septiembre++;
                            
                            $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                            $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                            $rendimiento_mes_anterior_septiembre = Rendimiento::where('id_vehiculo', $data->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                            if($rendimiento_mes_anterior_septiembre != null){

                                $recorrido_mes_anterior_septiembre = $rendimiento_mes_anterior_septiembre->recorrido;
                                $galones_mes_anterior_septiembre = $rendimiento_mes_anterior_septiembre->cantidad_galones;
                                $costo_mes_anterior_septiembre = $rendimiento_mes_anterior_septiembre->valor;
                                $rend_mes_anterior_septiembre = $rendimiento_mes_anterior_septiembre->rendimiento;

                            }else{
                                $recorrido_mes_anterior_septiembre = $galones_mes_anterior_septiembre = $costo_mes_anterior_septiembre = $rend_mes_anterior_septiembre = 0;
                            }

                        }elseif($mes_fecha == "10"){

                            $rec_octubre = $rec_octubre + $option->recorrido;
                            $gal_octubre = $gal_octubre + $option->cantidad_galones;
                            $costo_octubre = $costo_octubre + $option->valor;
                            $rend_octubre = $rend_octubre + $option->rendimiento;
                            $cant_octubre++;
                            
                            $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                            $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                            $rendimiento_mes_anterior_octubre = Rendimiento::where('id_vehiculo', $data->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                            if($rendimiento_mes_anterior_octubre != null){

                                $recorrido_mes_anterior_octubre = $rendimiento_mes_anterior_octubre->recorrido;
                                $galones_mes_anterior_octubre = $rendimiento_mes_anterior_octubre->cantidad_galones;
                                $costo_mes_anterior_octubre = $rendimiento_mes_anterior_octubre->valor;
                                $rend_mes_anterior_octubre = $rendimiento_mes_anterior_octubre->rendimiento;

                            }else{
                                $recorrido_mes_anterior_octubre = $galones_mes_anterior_octubre = $costo_mes_anterior_octubre = $rend_mes_anterior_octubre = 0;
                            }

                        }elseif($mes_fecha == "11"){

                            $rec_noviembre = $rec_noviembre + $option->recorrido;
                            $gal_noviembre = $gal_noviembre + $option->cantidad_galones;
                            $costo_noviembre = $costo_noviembre + $option->valor;
                            $rend_noviembre = $rend_noviembre + $option->rendimiento;
                            $cant_noviembre++;
                            
                            $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                            $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                            $rendimiento_mes_anterior_noviembre = Rendimiento::where('id_vehiculo', $data->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                            if($rendimiento_mes_anterior_noviembre != null){

                                $recorrido_mes_anterior_noviembre = $rendimiento_mes_anterior_noviembre->recorrido;
                                $galones_mes_anterior_noviembre = $rendimiento_mes_anterior_noviembre->cantidad_galones;
                                $costo_mes_anterior_noviembre = $rendimiento_mes_anterior_noviembre->valor;
                                $rend_mes_anterior_noviembre = $rendimiento_mes_anterior_octubre->rendimiento;

                            }else{
                                $recorrido_mes_anterior_noviembre = $galones_mes_anterior_noviembre = $costo_mes_anterior_noviembre = $rend_mes_anterior_noviembre = 0;
                            }

                        }elseif($mes_fecha == "12"){

                            $rec_diciembre = $rec_diciembre + $option->recorrido;
                            $gal_diciembre = $gal_diciembre + $option->cantidad_galones;
                            $costo_diciembre = $costo_diciembre + $option->valor;
                            $rend_diciembre = $rend_diciembre + $option->rendimiento;
                            $cant_diciembre++;
                            
                            $fecha_busqueda = $request->anio . '-' . $mes_fecha . '-';
                            $mesAnterior = date('m', strtotime('-1 month', strtotime($fecha_busqueda . '01')));

                            $rendimiento_mes_anterior_diciembre = Rendimiento::where('id_vehiculo', $data->id)->whereMonth('fecha', $mesAnterior)->orderBy('id', 'desc')->first();

                            if($rendimiento_mes_anterior_diciembre != null){

                                $recorrido_mes_anterior_diciembre = $rendimiento_mes_anterior_diciembre->recorrido;
                                $galones_mes_anterior_diciembre = $rendimiento_mes_anterior_diciembre->cantidad_galones;
                                $costo_mes_anterior_diciembre = $rendimiento_mes_anterior_diciembre->valor;
                                $rend_mes_anterior_diciembre = $rendimiento_mes_anterior_diciembre->rendimiento;

                            }else{
                                $recorrido_mes_anterior_diciembre = $galones_mes_anterior_diciembre = $costo_mes_anterior_diciembre = $rend_mes_anterior_diciembre = 0;
                            }

                        }

                    }

                    $rec_febrero = $rec_febrero + $recorrido_mes_anterior_febrero;
                    $rec_marzo = $rec_marzo + $recorrido_mes_anterior_marzo;
                    $rec_abril = $rec_abril + $recorrido_mes_anterior_abril;
                    $rec_mayo = $rec_mayo + $recorrido_mes_anterior_mayo;
                    $rec_junio = $rec_junio + $recorrido_mes_anterior_junio;
                    $rec_julio = $rec_julio + $recorrido_mes_anterior_julio;
                    $rec_agosto = $rec_agosto + $recorrido_mes_anterior_agosto;
                    $rec_septiembre = $rec_septiembre + $recorrido_mes_anterior_septiembre;
                    $rec_octubre = $rec_octubre + $recorrido_mes_anterior_octubre;
                    $rec_noviembre = $rec_noviembre + $recorrido_mes_anterior_noviembre;
                    $rec_diciembre = $rec_diciembre + $recorrido_mes_anterior_diciembre; 

                    $gal_febrero = $gal_febrero + $galones_mes_anterior_febrero;
                    $gal_marzo = $gal_marzo + $galones_mes_anterior_marzo;
                    $gal_abril = $gal_abril + $galones_mes_anterior_abril;
                    $gal_mayo = $gal_mayo + $galones_mes_anterior_mayo;
                    $gal_junio = $gal_junio + $galones_mes_anterior_junio;
                    $gal_julio = $gal_julio + $galones_mes_anterior_julio;
                    $gal_agosto = $gal_agosto + $galones_mes_anterior_agosto;
                    $gal_septiembre = $gal_septiembre + $galones_mes_anterior_septiembre;
                    $gal_octubre = $gal_octubre + $galones_mes_anterior_octubre;
                    $gal_noviembre = $gal_noviembre + $galones_mes_anterior_noviembre;
                    $gal_diciembre = $gal_diciembre + $galones_mes_anterior_diciembre;

                    $costo_febrero = $costo_febrero + $costo_mes_anterior_febrero;
                    $costo_marzo = $costo_marzo + $costo_mes_anterior_marzo;
                    $costo_abril = $costo_abril + $costo_mes_anterior_abril;
                    $costo_mayo = $costo_mayo + $costo_mes_anterior_mayo;
                    $costo_junio = $costo_junio + $costo_mes_anterior_junio;
                    $costo_julio = $costo_julio + $costo_mes_anterior_julio;
                    $costo_agosto = $costo_agosto + $costo_mes_anterior_agosto;
                    $costo_septiembre = $costo_septiembre + $costo_mes_anterior_septiembre;
                    $costo_octubre = $costo_octubre + $costo_mes_anterior_octubre;
                    $costo_noviembre = $costo_noviembre + $costo_mes_anterior_noviembre;
                    $costo_diciembre = $costo_diciembre + $costo_mes_anterior_diciembre;

                    $rend_febrero = $rend_febrero + $rend_mes_anterior_febrero;
                    $rend_marzo = $rend_marzo + $rend_mes_anterior_marzo;
                    $rend_abril = $rend_abril + $rend_mes_anterior_abril;
                    $rend_mayo = $rend_mayo + $rend_mes_anterior_mayo;
                    $rend_junio = $rend_junio + $rend_mes_anterior_junio;
                    $rend_julio = $rend_julio + $rend_mes_anterior_julio;
                    $rend_agosto = $rend_agosto + $rend_mes_anterior_agosto;
                    $rend_septiembre = $rend_septiembre + $rend_mes_anterior_septiembre;
                    $rend_octubre = $rend_octubre + $rend_mes_anterior_octubre;
                    $rend_noviembre = $rend_noviembre + $rend_mes_anterior_noviembre;
                    $rend_diciembre = $rend_diciembre + $rend_mes_anterior_diciembre;

                }

            }

            if($rec_enero!=0) $costoxkm_enero = round(($costo_enero/ $rec_enero), 2, PHP_ROUND_HALF_UP);
            if($gal_enero!=0) $rend_enero = round(($rec_enero/ $gal_enero), 2, PHP_ROUND_HALF_UP);
            if($rec_febrero!=0) $costoxkm_febrero = round(($costo_febrero/ $rec_febrero), 2, PHP_ROUND_HALF_UP); 
            if($gal_febrero!=0) $rend_febrero = round(($rend_febrero/ $gal_febrero), 2, PHP_ROUND_HALF_UP);
            if($rec_marzo!=0) $costoxkm_marzo = round(($costo_marzo/ $rec_marzo), 2, PHP_ROUND_HALF_UP); 
            if($gal_marzo!=0) $rend_marzo = round(($rend_marzo/ $gal_marzo), 2, PHP_ROUND_HALF_UP);
            if($rec_abril!=0) $costoxkm_abril = round(($costo_abril/ $rec_abril), 2, PHP_ROUND_HALF_UP);
            if($gal_abril!=0) $rend_abril = round(($rend_abril/ $gal_abril), 2, PHP_ROUND_HALF_UP);
            if($rec_mayo!=0) $costoxkm_mayo = round(($costo_mayo/ $rec_mayo), 2, PHP_ROUND_HALF_UP);
            if($gal_mayo!=0) $rend_mayo = round(($rend_mayo/ $gal_mayo), 2, PHP_ROUND_HALF_UP);
            if($rec_junio!=0) $costoxkm_junio = round(($costo_junio/ $rec_junio), 2, PHP_ROUND_HALF_UP);
            if($gal_junio!=0) $rend_junio = round(($rend_junio/ $gal_junio), 2, PHP_ROUND_HALF_UP);
            if($rec_julio!=0) $costoxkm_julio = round(($costo_julio/ $rec_julio), 2, PHP_ROUND_HALF_UP); 
            if($gal_julio!=0) $rend_julio = round(($rend_julio/ $gal_julio), 2, PHP_ROUND_HALF_UP);
            if($rec_agosto!=0) $costoxkm_agosto = round(($costo_agosto/ $rec_agosto), 2, PHP_ROUND_HALF_UP); 
            if($gal_agosto!=0) $rend_agosto = round(($rend_agosto/ $gal_agosto), 2, PHP_ROUND_HALF_UP);
            if($rec_septiembre!=0) $costoxkm_septiembre = round(($costo_septiembre/ $rec_septiembre), 2, PHP_ROUND_HALF_UP); 
            if($gal_septiembre!=0) $rend_septiembre = round(($rend_septiembre/ $gal_septiembre), 2, PHP_ROUND_HALF_UP);
            if($rec_octubre!=0) $costoxkm_octubre = round(($costo_octubre/ $rec_octubre), 2, PHP_ROUND_HALF_UP);
            if($gal_octubre!=0) $rend_octubre = round(($rend_octubre/ $gal_octubre), 2, PHP_ROUND_HALF_UP);
            if($rec_noviembre!=0) $costoxkm_noviembre = round(($costo_noviembre/ $rec_noviembre), 2, PHP_ROUND_HALF_UP); 
            if($gal_noviembre!=0) $rend_noviembre = round(($rend_noviembre/ $gal_noviembre), 2, PHP_ROUND_HALF_UP);
            if($rec_diciembre!=0) $costoxkm_diciembre = round(($costo_diciembre/ $rec_diciembre), 2, PHP_ROUND_HALF_UP); 
            if($gal_diciembre!=0) $rend_diciembre = round(($rend_diciembre/ $gal_diciembre), 2, PHP_ROUND_HALF_UP);

            $indicador_distribuidora->correlativo = $i++;
            $indicador_distribuidora->nombre_distribuidora = $item->nombre;
            $indicador_distribuidora->rec_enero = $rec_enero;
            $indicador_distribuidora->gal_enero = round($gal_enero, 3, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->costo_enero = round($costo_enero, 2, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->rend_enero = $rend_enero;
            $indicador_distribuidora->costoxkm_enero = $costoxkm_enero;
            $indicador_distribuidora->rec_febrero = $rec_febrero;
            $indicador_distribuidora->gal_febrero = round($gal_febrero, 3, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->costo_febrero = round($costo_febrero, 2, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->rend_febrero = $rend_febrero;
            $indicador_distribuidora->costoxkm_febrero = $costoxkm_febrero;
            $indicador_distribuidora->rec_marzo = $rec_marzo;
            $indicador_distribuidora->gal_marzo = round($gal_marzo, 3, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->costo_marzo = round($costo_marzo, 2, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->rend_marzo = $rend_marzo;
            $indicador_distribuidora->costoxkm_marzo = $costoxkm_marzo;
            $indicador_distribuidora->rec_abril = $rec_abril;
            $indicador_distribuidora->gal_abril = round($gal_abril, 3, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->costo_abril = round($costo_abril, 2, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->rend_abril = $rend_abril;
            $indicador_distribuidora->costoxkm_abril = $costoxkm_abril;
            $indicador_distribuidora->rec_mayo = $rec_mayo;
            $indicador_distribuidora->gal_mayo = round($gal_mayo, 3, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->costo_mayo = round($costo_mayo, 2, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->rend_mayo = $rend_mayo;
            $indicador_distribuidora->costoxkm_mayo = $costoxkm_mayo;
            $indicador_distribuidora->rec_junio = $rec_junio;
            $indicador_distribuidora->gal_junio = round($gal_junio, 3, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->costo_junio = round($costo_junio, 2, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->rend_junio = $rend_junio;
            $indicador_distribuidora->costoxkm_junio = $costoxkm_junio;
            $indicador_distribuidora->rec_julio = $rec_julio;
            $indicador_distribuidora->gal_julio = round($gal_julio, 3, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->costo_julio = round($costo_julio, 2, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->rend_julio = $rend_julio;
            $indicador_distribuidora->costoxkm_julio = $costoxkm_julio;
            $indicador_distribuidora->rec_agosto = $rec_agosto;
            $indicador_distribuidora->gal_agosto = round($gal_agosto, 3, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->costo_agosto = round($costo_agosto, 2, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->rend_agosto = $rend_agosto;
            $indicador_distribuidora->costoxkm_agosto = $costoxkm_agosto;
            $indicador_distribuidora->rec_septiembre = $rec_septiembre;
            $indicador_distribuidora->gal_septiembre = round($gal_septiembre, 3, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->costo_septiembre = round($costo_septiembre, 2, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->rend_septiembre = $rend_septiembre;
            $indicador_distribuidora->costoxkm_septiembre = $costoxkm_septiembre;
            $indicador_distribuidora->rec_octubre = $rec_octubre;
            $indicador_distribuidora->gal_octubre = round($gal_octubre, 3, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->costo_octubre = round($costo_octubre, 2, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->rend_octubre = $rend_octubre;
            $indicador_distribuidora->costoxkm_octubre = $costoxkm_octubre;
            $indicador_distribuidora->rec_noviembre = $rec_noviembre;
            $indicador_distribuidora->gal_noviembre = round($gal_noviembre, 3, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->costo_noviembre = round($costo_noviembre, 2, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->rend_noviembre = $rend_noviembre;
            $indicador_distribuidora->costoxkm_noviembre = $costoxkm_noviembre;
            $indicador_distribuidora->rec_diciembre = $rec_diciembre;
            $indicador_distribuidora->gal_diciembre = round($gal_diciembre, 3, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->costo_diciembre = round($costo_diciembre, 2, PHP_ROUND_HALF_UP);
            $indicador_distribuidora->rend_diciembre = $rend_diciembre;
            $indicador_distribuidora->costoxkm_diciembre = $costoxkm_diciembre;

            $indicadores_globales->push($indicador_distribuidora);
        }

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'GENERAR REPORTE';
            $historial->descripcion = 'GENERÓ UN REPORTE INDICADORES GLOBALES: ' . $request->anio;

        $historial->save();
        
        return [

            'indicadoresglobales' => $indicadores_globales,
            'nombre_anio' => $request->anio

        ];

    }

    public function reporteCumplimientos(Request $request)
    {
        
        $fecha_busqueda = $request->anio . '-' . $request->mes . '-';
        $fecha_busqueda2 = $request->anio . '-' . $request->mes . '-01';
        $mes_siguiente = date('m', strtotime('+1 month', strtotime($fecha_busqueda2)));

        if($mes_siguiente == 1){
            $anio_nuevo = $request->anio + 1;
        }else{
            $anio_nuevo = $request->anio;
        }
        
        $fecha_busqueda2 = $anio_nuevo . '-' . $mes_siguiente . '-01';
        $id_distribuidora = $request->id_distribuidora;

        setlocale(LC_TIME, 'es_ES');
        $fecha = \DateTime::createFromFormat('!m', $request->mes);
        $nombremes = strtoupper(strftime("%B", $fecha->getTimestamp()));

        $mantenimientos_realizados = Mantenimiento::select('mantenimientos.id', 'vehiculos.numero_vehiculo', 'vehiculos.placa', 'mantenimientos.fecha', 'mantenimientos.nombre_rutina_anterior')
                                                    ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                                                    ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                                    ->where('distribuidoras.id', $id_distribuidora)
                                                    ->where('mantenimientos.fecha', 'like', $fecha_busqueda . '%')
                                                    ->get();

        $semaforos = Semaforo::join('mantenimientos', 'mantenimientos.id', '=', 'semaforos.id_mantenimiento')
                            ->join('vehiculos', 'vehiculos.id', '=', 'mantenimientos.id_vehiculo')
                            ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                            ->where('distribuidoras.id', $id_distribuidora)
                            ->where(function($query) use ($fecha_busqueda2) {
                                $query->where('fecha_prox_manto_kms', '<', $fecha_busqueda2)
                                ->orWhere('fecha_prox_manto_tiempo', '<', $fecha_busqueda2);
                            })
                            ->get();

        $mantenimientos_proyectados = collect();
        $mantenimientos_atrasados = collect();

        $i = 1;
        $j = 1;

        foreach($semaforos as $item){

            if($item->fecha_prox_manto_kms <= $item->fecha_prox_manto_tiempo){
                $banc = true;
                $fechaf = $item->fecha_prox_manto_kms;
            }

            if($item->fecha_prox_manto_tiempo < $item->fecha_prox_manto_kms){
                $banc = false;
                $fechaf = $item->fecha_prox_manto_tiempo;
            }

            $fecha_valida = strtotime($fechaf);
            $mes_valido = date('m', $fecha_valida);
            $anio_valido = date('Y', $fecha_valida);

            $fecha_valida2 = strtotime(date('Y-m-d'));
            $mes_valido2 = date('m', $fecha_valida2);
            $anio_valido2 = date('Y', $fecha_valida2);

            if($mes_valido == $request->mes && $anio_valido == $request->anio && $item->vehiculo != null){

                $proyectados = new \stdClass();
            
                $proyectados->correlativo = $i++;
                $proyectados->numero_vehiculo = $item->vehiculo->numero_vehiculo;
                $proyectados->placa = $item->vehiculo->placa;
                $proyectados->conductor = $item->vehiculo->conductor;
                $proyectados->taller = $item->vehiculo->taller->nombre;
                $proyectados->rutina = $item->mantenimiento->nombre_rutina_nueva;
                
                $proyectados->fecha_mtto = $item->mantenimiento->fecha;
                $proyectados->km_mtto = $item->mantenimiento->kilometraje;
                $proyectados->km_actual = $item->vehiculo->km;
                
                if($banc){
                    $criterio_cumpl = 'KILOMETRAJE';
                    if($item->diferencia_kms <= (-1) * ($item->vehiculo->periodo_mtto_kms * 0.05)) $proyectados->criticidad = 'ALTA';
                    if($item->diferencia_kms > (-1) * ($item->vehiculo->periodo_mtto_kms * 0.05) && $item->diferencia_kms <= 0) $proyectados->criticidad = 'MEDIA';
                    if($item->diferencia_kms > 0 && $item->diferencia_kms < ($item->vehiculo->periodo_mtto_kms * 0.05)) $proyectados->criticidad = 'BAJA';
                    if($item->diferencia_kms >= ($item->vehiculo->periodo_mtto_kms * 0.05)) $proyectados->criticidad = 'PLANIFICADA';
                }else{
                    $criterio_cumpl = 'TIEMPO';
                    if($item->diferencia_dias <= (-1) * ($item->vehiculo->periodo_mtto_meses * 30 * 0.05)) $proyectados->criticidad = 'ALTA';
                    if($item->diferencia_dias > (-1) * ($item->vehiculo->periodo_mtto_meses * 30 * 0.05) && $item->diferencia_dias <= 0) $proyectados->criticidad = 'MEDIA';
                    if($item->diferencia_dias > 0 && $item->diferencia_dias < ($item->vehiculo->periodo_mtto_meses * 30 * 0.05)) $proyectados->criticidad = 'BAJA';
                    if($item->diferencia_dias >= ($item->vehiculo->periodo_mtto_meses * 30 * 0.05)) $proyectados->criticidad = 'PLANIFICADA';
                }
                    
                $proyectados->criterio_cumpl = $criterio_cumpl;
                $proyectados->fecha_prox_manto_kms = $item->fecha_prox_manto_kms;
                $proyectados->fecha_prox_manto_tiempo = $item->fecha_prox_manto_tiempo;
                
                $mantenimientos_proyectados->push($proyectados);
            }

            if($mes_valido < $request->mes && $anio_valido <= $request->anio && (($item->diferencia_kms >= ($item->vehiculo->periodo_mtto_kms * 0.05)) || ($item->diferencia_dias >= ($item->vehiculo->periodo_mtto_meses * 30 * 0.05)))){

                $proyectados = new \stdClass();
            
                $proyectados->correlativo = $i++;
                $proyectados->numero_vehiculo = $item->vehiculo->numero_vehiculo;
                $proyectados->placa = $item->vehiculo->placa;
                $proyectados->conductor = $item->vehiculo->conductor;
                $proyectados->taller = $item->vehiculo->taller->nombre;
                $proyectados->rutina = $item->mantenimiento->nombre_rutina_nueva;
                
                $proyectados->fecha_mtto = $item->mantenimiento->fecha;
                $proyectados->km_mtto = $item->mantenimiento->kilometraje;
                $proyectados->km_actual = $item->vehiculo->km;
                
                if($banc){
                    $criterio_cumpl = 'KILOMETRAJE';
                    if($item->diferencia_kms <= (-1) * ($item->vehiculo->periodo_mtto_kms * 0.05)) $proyectados->criticidad = 'ALTA';
                    if($item->diferencia_kms > (-1) * ($item->vehiculo->periodo_mtto_kms * 0.05) && $item->diferencia_kms <= 0) $proyectados->criticidad = 'MEDIA';
                    if($item->diferencia_kms > 0 && $item->diferencia_kms < ($item->vehiculo->periodo_mtto_kms * 0.05)) $proyectados->criticidad = 'BAJA';
                    if($item->diferencia_kms >= ($item->vehiculo->periodo_mtto_kms * 0.05)) $proyectados->criticidad = 'PLANIFICADA';
                }else{
                    $criterio_cumpl = 'TIEMPO';
                    if($item->diferencia_dias <= (-1) * ($item->vehiculo->periodo_mtto_meses * 30 * 0.05)) $proyectados->criticidad = 'ALTA';
                    if($item->diferencia_dias > (-1) * ($item->vehiculo->periodo_mtto_meses * 30 * 0.05) && $item->diferencia_dias <= 0) $proyectados->criticidad = 'MEDIA';
                    if($item->diferencia_dias > 0 && $item->diferencia_dias < ($item->vehiculo->periodo_mtto_meses * 30 * 0.05)) $proyectados->criticidad = 'BAJA';
                    if($item->diferencia_dias >= ($item->vehiculo->periodo_mtto_meses * 30 * 0.05)) $proyectados->criticidad = 'PLANIFICADA';
                }
                    
                $proyectados->criterio_cumpl = $criterio_cumpl;
                $proyectados->fecha_prox_manto_kms = $item->fecha_prox_manto_kms;
                $proyectados->fecha_prox_manto_tiempo = $item->fecha_prox_manto_tiempo;
                
                $mantenimientos_proyectados->push($proyectados);
            }

            if($mes_valido < $request->mes && $anio_valido <= $request->anio && !(($item->diferencia_kms >= ($item->vehiculo->periodo_mtto_kms * 0.05)) || ($item->diferencia_dias >= ($item->vehiculo->periodo_mtto_meses * 30 * 0.05)))){
            
                $atrasados = new \stdClass();

                $atrasados->correlativo2 = $j++;
                $atrasados->numero_vehiculo = $item->vehiculo->numero_vehiculo;
                $atrasados->placa = $item->vehiculo->placa;
                $atrasados->conductor = $item->vehiculo->conductor;
                $atrasados->taller = $item->vehiculo->taller->nombre;
                $atrasados->proyeccion = $fechaf;
                $atrasados->rutina = $item->mantenimiento->nombre_rutina_nueva;
                $atrasados->fecha_mtto = $item->mantenimiento->fecha;
                $atrasados->km_mtto = $item->mantenimiento->kilometraje;
                $atrasados->km_actual = $item->vehiculo->km;

                if($banc){
                    $criterio_cumpl = 'KILOMETRAJE';
                    if($item->diferencia_kms <= (-1) * ($item->vehiculo->periodo_mtto_kms * 0.05)) $atrasados->criticidad = 'ALTA';
                    if($item->diferencia_kms > (-1) * ($item->vehiculo->periodo_mtto_kms * 0.05) && $item->diferencia_kms <= 0) $atrasados->criticidad = 'MEDIA';
                    if($item->diferencia_kms > 0 && $item->diferencia_kms < ($item->vehiculo->periodo_mtto_kms * 0.05)) $atrasados->criticidad = 'BAJA';
                    if($item->diferencia_kms >= ($item->vehiculo->periodo_mtto_kms * 0.05)) $atrasados->criticidad = 'PLANIFICADA';
                }else{
                    $criterio_cumpl = 'TIEMPO';
                    if($item->diferencia_dias <= (-1) * ($item->vehiculo->periodo_mtto_meses * 30 * 0.05)) $atrasados->criticidad = 'ALTA';
                    if($item->diferencia_dias > (-1) * ($item->vehiculo->periodo_mtto_meses * 30 * 0.05) && $item->diferencia_dias <= 0) $atrasados->criticidad = 'MEDIA';
                    if($item->diferencia_dias > 0 && $item->diferencia_dias < ($item->vehiculo->periodo_mtto_meses * 30 * 0.05)) $atrasados->criticidad = 'BAJA';
                    if($item->diferencia_dias >= ($item->vehiculo->periodo_mtto_meses * 30 * 0.05)) $atrasados->criticidad = 'PLANIFICADA';
                }

                $atrasados->criterio_cumpl = $criterio_cumpl;

                $mantenimientos_atrasados->push($atrasados);

            }

        }
        
        $distribuidora = Distribuidora::findOrFail($request->id_distribuidora)->nombre;

        $total_mantenimientos = count($mantenimientos_realizados) + count($mantenimientos_proyectados->whereNotIn('criticidad', ['PLANIFICADA'])) + count($mantenimientos_atrasados->whereNotIn('criticidad', ['PLANIFICADA']));

        if($total_mantenimientos > 0){
            $indicador_cumplimiento = round((count($mantenimientos_realizados) * 100 / $total_mantenimientos), 2, PHP_ROUND_HALF_UP);
        }else{
            $indicador_cumplimiento = 0;
        }

        return [

            'mantenimientos_realizados' => $mantenimientos_realizados,
            'cant_mtto_realizados' => count($mantenimientos_realizados),
            'mantenimientos_proyectados' => $mantenimientos_proyectados,
            'cant_mtto_proyectados' => count($mantenimientos_proyectados),
            'mantenimientos_atrasados' => $mantenimientos_atrasados,
            'cant_mtto_atrasados' => count($mantenimientos_atrasados),
            'nombre_mes' => $nombremes,
            'nombre_anio' => $request->anio,
            'distribuidora_nombre' => $distribuidora,
            'indicador_cumplimiento' => $indicador_cumplimiento

        ];
        


    }

    public function reporteCumplimientoExportarExcel(Request $request)
    {
        $nombre_reporte = 'reporte-cumplimiento-' . $request->distribuidora_nombre . '-' . $request->mes . '-' . $request->anio . '.xlsx';
        
        Excel::store(new ReporteCumplimientosExport($request->mantenimientosrealizados, $request->mantenimientosproyectados, $request->mantenimientosatrasados, $request->distribuidora_nombre, $request->mes, $request->anio, $request->indicador_cumplimiento, $request->cant_mtto_realizados, $request->cant_mtto_proyectados, $request->cant_mtto_atrasados), $nombre_reporte, 'public');
    }

    public function reporteCumplimientoDescargar(Request $request)
    {
        $nombre_reporte = 'reporte-cumplimiento-' . $request->distribuidora_nombre . '-' . $request->mes . '-' . $request->anio . '.xlsx';
        $file = storage_path() . '/app/public/' . $nombre_reporte;
 
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'DESCARGAR REPORTE';
            $historial->descripcion = 'DESCARGÓ UN REPORTE DE CUMPLIMIENTO: ' . $request->distribuidora_nombre . '-' . $request->mes . '-' . $request->anio;

        $historial->save();

        return response()->download($file, $nombre_reporte, $headers);
    }

    public function reportePresupuestos(Request $request)
    {

        $fecha_busqueda_fin = $request->anio . '-' . $request->mes . '-31';
        $fecha_busqueda_inicio = $request->anio . '-' . $request->mes . '-01';

        if($request->meses_anteriores == 'true'){
            $fecha_busqueda_inicio = $request->anio . '-01-01';
        }

        $id_distribuidora = $request->id_distribuidora;
        $id_taller = array();
        
        if($request->tipo_taller == 'DIANA'){
            
            $talleres = Taller::where('nombre', 'DIANA')->first();
            $id_taller[] = $talleres->id;

        }else{
            
            $talleres = Taller::whereNotIn('nombre', ['DIANA', 'N/A'])->get();
            
            foreach($talleres as $item){
                $id_taller[] = $item->id;
            }

        }

        setlocale(LC_TIME, 'es_ES');
        $fecha = \DateTime::createFromFormat('!m', $request->mes);
        $nombremes = strtoupper(strftime("%B", $fecha->getTimestamp()));

        $costos_rutinas = Vehiculo::select('vehiculos.placa', 'vehiculos.modelo', 'vehiculos.conductor', 
                                            'talleres.nombre as nombre_taller', 'mantenimientos.nombre_rutina_nueva', 'semaforos.fecha_prox_manto_kms',
                                            'semaforos.fecha_prox_manto_tiempo')
                                    ->join('talleres', 'talleres.id', '=', 'vehiculos.id_taller')
                                    ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                                    ->join('semaforos', 'semaforos.id_vehiculo', '=', 'vehiculos.id')
                                    ->join('mantenimientos', 'mantenimientos.id', '=', 'semaforos.id_mantenimiento')
                                    ->where('vehiculos.id_distribuidora', $id_distribuidora)
                                    ->whereIn('vehiculos.id_taller', $id_taller)
                                    ->where(function($query) use ($fecha_busqueda_inicio, $fecha_busqueda_fin) {
                                        $query->whereBetween('semaforos.fecha_prox_manto_kms', array($fecha_busqueda_inicio, $fecha_busqueda_fin))
                                        ->orWhereBetween('semaforos.fecha_prox_manto_tiempo', array($fecha_busqueda_inicio, $fecha_busqueda_fin));
                                    })
                                    ->get();

        $reporte_rutinas = collect();
        $i = 1;
        $arreglo_modelos = array();

        foreach($costos_rutinas as $item){
            
            $objeto = new \stdClass();

            if($item->fecha_prox_manto_kms <= $item->fecha_prox_manto_tiempo){
                $fechaf = $item->fecha_prox_manto_kms;
            }else{
                $fechaf = $item->fecha_prox_manto_tiempo;
            }
            
            $objeto->correlativo = $i++;
            $objeto->placa = $item->placa;
            $objeto->modelo = $item->modelo;
            $objeto->conductor = $item->conductor;
            $objeto->rutina = $item->nombre_rutina_nueva;
            $objeto->taller = $item->nombre_taller;
            $objeto->fecha_mas_prox = $fechaf;
            $string_rutina = substr($item->nombre_rutina_nueva, 0, 1);
            
            $rutina_costo = CostoRutina::where('modelo', $item->modelo)->where('rutina', $string_rutina)->first();

            $objeto->costo = $rutina_costo->costo;

            $reporte_rutinas->push($objeto);

            $arreglo_modelos[] = $item->modelo; 

        }

        $sorted = $reporte_rutinas->sortBy('fecha_mas_prox')->values();

        ///////////////////////////////////////////////////////////////////

        $costo_repuesto = CostoRepuesto::whereIn('modelo', $arreglo_modelos)->orWhere('modelo', 'TODOS')->get();
        $cantidad_repuesto = 0;
        $reporte_repuestos = collect();

        if($costo_repuesto != null){

            foreach($costo_repuesto as $item){

                $objeto = new \stdClass();
    
                $objeto->material = $item->material;
                $objeto->repuesto = $item->repuesto;
                $objeto->descripcion = $item->descripcion;
                $objeto->modelo = $item->modelo;
    
                $rutinas = explode(',', $item->rutinas);
                $rutinas_new = array();
    
                foreach($rutinas as $data){
    
                    if($data == 'A'){
                        $rutinas_new[] = 'A1';
                        $rutinas_new[] = 'A2';
                        $rutinas_new[] = 'A3';
                        $rutinas_new[] = 'A4';
                        $rutinas_new[] = 'A5';
                        $rutinas_new[] = 'A6';
                    }
    
                    if($data == 'B'){
                        $rutinas_new[] = 'B';
                    }
    
                    if($data == 'C'){
                        $rutinas_new[] = 'C';
                    }
    
                    if($data == 'D'){
                        $rutinas_new[] = 'D';
                    }
                    
                }
    
                $vehiculos = count($reporte_rutinas->where('modelo', $item->modelo)->whereIn('rutina', $rutinas_new));
    
                $objeto->cantidad = $item->cantidad * $vehiculos;
                $objeto->costo = $item->cantidad * $vehiculos * $item->costo;

                if(!($objeto->modelo == 'TODOS' && $objeto->cantidad == 0)){
                    $reporte_repuestos->push($objeto);
                }
    
            }

        }

        $distribuidora = Distribuidora::findOrFail($request->id_distribuidora)->nombre;

        return [

            'reporterutinas' => $sorted,
            'reporterepuestos' => $reporte_repuestos,
            'total_costosrutinas' => round($sorted->sum('costo'), 2, PHP_ROUND_HALF_UP),
            'total_costosrepuestos' => round($reporte_repuestos->sum('costo'), 2, PHP_ROUND_HALF_UP),
            'nombre_mes' => $nombremes,
            'nombre_anio' => $request->anio,
            'distribuidora_nombre' => $distribuidora,
            'nombre_tipo_taller' => $request->nombre_tipo_taller

        ];

    }

    public function reportePresupuestoExportarExcel(Request $request)
    {
        //dd($request->all());
        $nombre_reporte = 'reporte-presupuesto-' . $request->distribuidora_nombre . '-' . $request->mes . '-' . $request->anio . '.xlsx';
        
        Excel::store(new PresupuestoExport($request->reporte_rutinas, $request->reporte_repuestos, $request->total_reporte_rutinas, $request->total_reporte_repuestos, $request->distribuidora_nombre, $request->mes, $request->anio), $nombre_reporte, 'public');
    }

    public function reportePresupuestoDescargar(Request $request)
    {
        $nombre_reporte = 'reporte-presupuesto-' . $request->distribuidora_nombre . '-' . $request->mes . '-' . $request->anio . '.xlsx';
        $file = storage_path() . '/app/public/' . $nombre_reporte;
 
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $historial = new Historial();

            $historial->id_user = Auth::user()->id;
            $historial->nombre_accion = 'DESCARGAR REPORTE';
            $historial->descripcion = 'DESCARGÓ UN REPORTE DE PRESUPUESTO: ' . $request->distribuidora_nombre . '-' . $request->mes . '-' . $request->anio;

        $historial->save();

        return response()->download($file, $nombre_reporte, $headers);
    }

    public function reporteGrafico(Request $request)
    {
        
        $vehiculos = $request->vehiculos;

        $listado_rendimientos = collect();

        $id_vehiculo = array();
        $arreglo_fechas = array();
        

        foreach($vehiculos as $item){
           $id_vehiculo[] = $item['id'];
        }

        $fechas = Rendimiento::select('fecha')->where('fecha', 'like', $request->anio . '%')
                                ->whereIn('id_vehiculo', $id_vehiculo)
                                ->whereIn('status', ['OK', 'OK - 1'])
                                ->orderBy('fecha')
                                ->distinct('fecha')
                                ->get();

        foreach($fechas as $item){
            $arreglo_fechas[] = $item->fecha;
        }

        foreach($vehiculos as $item){
            
            $arreglo_rendimientos = array();

            $objeto = new \stdClass();

            $vehiculo_id = Vehiculo::where('id', $item)->first();
            $codigo_placa = $vehiculo_id->codigo_comb;

            $fechas_todos = Rendimiento::select('id_vehiculo', 'fecha', 'rendimiento', 'status')
                                        ->where('fecha', 'like', $request->anio . '%')
                                        ->where('id_vehiculo', $item)
                                        ->whereIn('status', ['OK', 'OK - 1'])
                                        ->orderBy('fecha')
                                        ->get();

            $arreglo_fechas2 = array();

            foreach($fechas_todos as $data){
                $arreglo_fechas2[] = $data->fecha;
            }

            foreach($arreglo_fechas as $data){

                if(in_array($data, $arreglo_fechas2)){

                    $rendimiento = Rendimiento::select('id_vehiculo', 'fecha', 'rendimiento')
                                                ->where('fecha', $data)
                                                ->where('id_vehiculo', $item)
                                                ->whereIn('status', ['OK', 'OK - 1'])
                                                ->first();

                    

                    $arreglo_rendimientos[] = $rendimiento->rendimiento;

                }else{

                    $arreglo_rendimientos[] = null;

                }
            }

            $objeto->codigo_placa = $codigo_placa;
            $objeto->rendimientos = $arreglo_rendimientos;

            $listado_rendimientos->push($objeto);


        }

        return [
            'fechas' => $arreglo_fechas,
            'rendimientos' => $listado_rendimientos
        ];

    }

    public function reporteGrafico2(Request $request)
    {
        //dd($request->all());

        $distribuidoras = $request->distribuidoras;
        $modelos = $request->modelos;
        $vehiculos = $request->vehiculos;
        $fecha_busqueda = $request->anio;

        $array_distribuidora = array();

        foreach($distribuidoras as $item){
            $array_distribuidora[] = $item["id"];
        }

        foreach($modelos as $item){
            $array_modelo[] = $item["modelo"];
        }

        foreach($vehiculos as $item){
            $array_vehiculo[] = $item["id"];
        }

        if($modelos == null && $vehiculos == null){
            
            $vehiculos_rendimiento = collect();

            $vehiculos = Vehiculo::with(['rendimientos' => function($q) use ($fecha_busqueda) {
                $q->where('fecha', 'like', $fecha_busqueda . '%');
            }])->whereIn('id_distribuidora', $array_distribuidora)->get();

        }elseif($modelos != null && $vehiculos == null){

            $vehiculos_rendimiento = collect();

            $vehiculos = Vehiculo::with(['rendimientos' => function($q) use ($fecha_busqueda) {
                $q->where('fecha', 'like', $fecha_busqueda . '%');
            }])->whereIn('id_distribuidora', $array_distribuidora)->whereIn('modelo', $array_modelo)->get();

        }elseif($modelos != null && $vehiculos != null){

            $vehiculos_rendimiento = collect();

            $vehiculos = Vehiculo::with(['rendimientos' => function($q) use ($fecha_busqueda) {
                $q->where('fecha', 'like', $fecha_busqueda . '%');
            }])->whereIn('id', $array_vehiculo)->get();

        }

        $i = 0;

        foreach($vehiculos as $item){
            
            $rend_enero = $rend_febrero = $rend_marzo = $rend_abril = $rend_mayo = $rend_junio = $rend_julio = $rend_agosto = $rend_septiembre = $rend_octubre = $rend_noviembre = $rend_diciembre = 0;

            $reporte_anual = new \stdClass();
            $rendimientos = array();

            if(!$item->rendimientos->isEmpty()){

                foreach($item->rendimientos as $data){

                    $fecha_entero = strtotime($data->fecha);
                    $mes_fecha = date('m', $fecha_entero);

                    if($mes_fecha == "01"){
                        $rend_enero = $rend_enero + $data->rendimiento;
                    }elseif($mes_fecha == "02"){
                        $rend_febrero = $rend_febrero + $data->rendimiento;
                    }elseif($mes_fecha == "03"){
                        $rend_marzo = $rend_marzo + $data->rendimiento;
                    }elseif($mes_fecha == "04"){
                        $rend_abril = $rend_abril + $data->rendimiento;
                    }elseif($mes_fecha == "05"){
                        $rend_mayo = $rend_mayo + $data->rendimiento;
                    }elseif($mes_fecha == "06"){
                        $rend_junio = $rend_junio + $data->rendimiento;
                    }elseif($mes_fecha == "07"){
                        $rend_julio = $rend_julio + $data->rendimiento;
                    }elseif($mes_fecha == "08"){
                        $rend_agosto = $rend_agosto + $data->rendimiento;
                    }elseif($mes_fecha == "09"){
                        $rend_septiembre = $rend_septiembre + $data->rendimiento;
                    }elseif($mes_fecha == "10"){
                        $rend_octubre = $rend_octubre + $data->rendimiento;
                    }elseif($mes_fecha == "11"){
                        $rend_noviembre = $rend_noviembre + $data->rendimiento;
                    }elseif($mes_fecha == "12"){
                        $rend_diciembre = $rend_diciembre + $data->rendimiento;
                    }
                }

                if($rend_enero == 0) $rend_enero = null;
                if($rend_febrero == 0) $rend_febrero = null;
                if($rend_marzo == 0) $rend_marzo = null;
                if($rend_abril == 0) $rend_abril = null;
                if($rend_mayo == 0) $rend_mayo = null;
                if($rend_junio == 0) $rend_junio = null;
                if($rend_julio == 0) $rend_julio = null;
                if($rend_agosto == 0) $rend_agosto = null;
                if($rend_septiembre == 0) $rend_septiembre = null;
                if($rend_octubre == 0) $rend_octubre = null;
                if($rend_noviembre == 0) $rend_noviembre = null;
                if($rend_diciembre == 0) $rend_diciembre = null;

                $rendimientos[] = $rend_enero;
                $rendimientos[] = $rend_febrero;
                $rendimientos[] = $rend_marzo;
                $rendimientos[] = $rend_abril;
                $rendimientos[] = $rend_mayo;
                $rendimientos[] = $rend_junio;
                $rendimientos[] = $rend_julio;
                $rendimientos[] = $rend_agosto;
                $rendimientos[] = $rend_septiembre;
                $rendimientos[] = $rend_octubre;
                $rendimientos[] = $rend_noviembre;
                $rendimientos[] = $rend_diciembre;

                $reporte_anual->correlativo = ++$i;
                $reporte_anual->codigo_placa = $item->codigo_comb;
                $reporte_anual->rendimientos = $rendimientos;


                $vehiculos_rendimiento->push($reporte_anual);
            }


        }

        //dd($vehiculos_rendimiento);


        return [
            'rendimientos' => $vehiculos_rendimiento
        ];

    }

}
