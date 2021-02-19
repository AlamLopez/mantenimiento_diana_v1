<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest']], function() {
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('principal');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function(){

    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/main', function () {
        return view('contenido.contenido');
    })->name('main');

    Route::get('/conteo', 'PrincipalController@index');

    Route::get('/historial', 'HistorialController@index');

    Route::get('/rutinas/selectRutina', 'RutinaController@selectRutina');

    Route::get('/semaforos', 'SemaforoController@index');
    Route::get('/semaforos/actualizar', 'SemaforoController@actualizar');

    Route::get('/roles', 'RolController@index');
    Route::get('/roles/selectRol', 'RolController@selectRol');

    Route::get('/costosrutinas', 'CostoRutinaController@index');
    Route::post('/costosrutinas/registrar', 'CostoRutinaController@store');
    Route::put('/costosrutinas/actualizar', 'CostoRutinaController@update');

    Route::get('/costosrepuestos', 'CostoRepuestoController@index');
    Route::post('/costosrepuestos/registrar', 'CostoRepuestoController@store');
    Route::put('/costosrepuestos/actualizar', 'CostoRepuestoController@update');
    Route::delete('/costosrepuestos/eliminar', 'CostoRepuestoController@delete');

    Route::get('/distribuidoras', 'DistribuidoraController@index');
    Route::get('/distribuidoras/selectDistribuidora', 'DistribuidoraController@selectDistribuidora');
    Route::get('/distribuidoras/selectDistribuidoraUsuario', 'DistribuidoraController@selectDistribuidoraUsuario');
    Route::post('/distribuidoras/registrar', 'DistribuidoraController@store');
    Route::put('/distribuidoras/actualizar', 'DistribuidoraController@update');
    Route::put('/distribuidoras/desactivar', 'DistribuidoraController@desactivar');
    Route::put('/distribuidoras/activar', 'DistribuidoraController@activar');

    Route::get('/combustibles', 'CombustibleController@index');
    Route::get('/combustibles/selectCombustible', 'CombustibleController@selectCombustible');
    Route::post('/combustibles/registrar', 'CombustibleController@store');
    Route::put('/combustibles/actualizar', 'CombustibleController@update');
    Route::put('/combustibles/desactivar', 'CombustibleController@desactivar');
    Route::put('/combustibles/activar', 'CombustibleController@activar');

    Route::get('/talleres', 'TallerController@index');
    Route::get('/talleres/selectTaller', 'TallerController@selectTaller');
    Route::post('/talleres/registrar', 'TallerController@store');
    Route::put('/talleres/actualizar', 'TallerController@update');
    Route::put('/talleres/desactivar', 'TallerController@desactivar');
    Route::put('/talleres/activar', 'TallerController@activar');

    Route::get('/usuarios', 'UserController@index');
    Route::get('/usuarios/unico/{nombre}', 'UserController@unico');
    Route::get('/usuarios/unico/{nombre}/{user_id}', 'UserController@unico2');
    Route::get('/usuarios/unicoEmail/{email}', 'UserController@unico3');
    Route::get('/usuarios/unicoEmail/{email}/{user_id}', 'UserController@unico4');
    Route::post('/usuarios/registrar', 'UserController@store');
    Route::put('/usuarios/actualizar', 'UserController@update');
    Route::put('/usuarios/desactivar', 'UserController@desactivar');
    Route::put('/usuarios/activar', 'UserController@activar');

    Route::get('/vehiculos', 'VehiculoController@index');
    Route::get('/vehiculos/unico/{placa}', 'VehiculoController@unico');
    Route::get('/vehiculos/unico/{placa}/{vehiculo_id}', 'VehiculoController@unico2');
    Route::get('/vehiculos/selectVehiculo', 'VehiculoController@selectVehiculo');
    Route::get('/vehiculos/selectModelo', 'VehiculoController@selectModelo');
    Route::post('/vehiculos/selectModeloGrafico', 'VehiculoController@selectModeloGrafico');
    Route::post('/vehiculos/selectVehiculoGrafico', 'VehiculoController@selectVehiculoGrafico');
    Route::get('/vehiculos/selectVehiculoPlaca', 'VehiculoController@selectVehiculoPlaca');
    Route::get('/vehiculos/descargarFormato', 'VehiculoController@descargarFormato');
    Route::get('/vehiculos/vehiculo-descargar-excel', 'VehiculoController@vehiculoDescargarExcel');
    Route::post('/vehiculos/registrar', 'VehiculoController@store');
    Route::post('/vehiculos/importar', 'VehiculoController@importar');
    Route::post('/vehiculos/exportar-excel', 'VehiculoController@exportarExcel');
    Route::put('/vehiculos/actualizar', 'VehiculoController@update');
    Route::put('/vehiculos/desactivar', 'VehiculoController@desactivar');
    Route::put('/vehiculos/activar', 'VehiculoController@activar');

    Route::get('/rendimientos', 'RendimientoController@index');
    Route::get('/rendimientos/kilometrajeAnterior', 'RendimientoController@kilometrajeAnterior');
    Route::get('/rendimientos/kilometrajePosterior', 'RendimientoController@kilometrajePosterior');
    Route::get('/rendimientos/unico/{id_data}', 'RendimientoController@unico');
    Route::get('/rendimientos/unico/{id_data}/{rendimiento_id}', 'RendimientoController@unico2');
    Route::get('/rendimientos/ultimaFechaKilometrajeVehiculo', 'RendimientoController@ultimaFechaKilometrajeVehiculo');
    Route::get('/rendimientos/descargarFormato', 'RendimientoController@descargarFormato');
    Route::get('/rendimientos/rendimiento-descargar-excel', 'RendimientoController@rendimientoDescargarExcel');
    Route::get('/rendimientos/ultimoRendimiento', 'RendimientoController@ultimoRendimiento');
    Route::post('/rendimientos/importar', 'RendimientoController@importar');
    Route::post('/rendimientos/registrar', 'RendimientoController@store');
    Route::post('/rendimientos/exportar-excel', 'RendimientoController@exportarExcel');
    Route::put('/rendimientos/actualizar', 'RendimientoController@update');

    Route::get('/reporteria/inventario-flota-descargar', 'ReporteriaController@inventarioFlotaDescargar');
    Route::get('/reporteria/ingreso-datos-descargar', 'ReporteriaController@ingresoDatosDescargar');
    Route::get('/reporteria/reporte-mensual', 'ReporteriaController@reporteMensual');
    Route::get('/reporteria/reporte-anual', 'ReporteriaController@reporteAnual');
    Route::get('/reporteria/reporte-recorrido', 'ReporteriaController@reporteRecorrido');
    Route::get('/reporteria/indicadores-globales', 'ReporteriaController@indicadorGlobal');
    Route::post('/reporteria/reporte-grafico', 'ReporteriaController@reporteGrafico');
    Route::post('/reporteria/reporte-grafico2', 'ReporteriaController@reporteGrafico2');
    Route::get('/reporteria/reporte-cumplimientos', 'ReporteriaController@reporteCumplimientos');
    Route::get('/reporteria/reporte-presupuestos', 'ReporteriaController@reportePresupuestos');
    Route::get('/reporteria/reporte-mensual-descargar-excel', 'ReporteriaController@reporteMensualDescargar');
    Route::get('/reporteria/reporte-anual-descargar-excel', 'ReporteriaController@reporteAnualDescargar');
    Route::get('/reporteria/reporte-recorrido-descargar-excel', 'ReporteriaController@reporteRecorridoDescargar');
    Route::get('/reporteria/reporte-cumplimiento-descargar-excel', 'ReporteriaController@reporteCumplimientoDescargar');
    Route::get('/reporteria/reporte-presupuesto-descargar-excel', 'ReporteriaController@reportePresupuestoDescargar');
    Route::post('/reporteria/reporte-mensual-exportar-excel', 'ReporteriaController@reporteMensualExportarExcel');
    Route::post('/reporteria/reporte-anual-exportar-excel', 'ReporteriaController@reporteAnualExportarExcel');
    Route::post('/reporteria/reporte-recorrido-exportar-excel', 'ReporteriaController@reporteRecorridoExportarExcel');
    Route::post('/reporteria/reporte-cumplimiento-exportar-excel', 'ReporteriaController@reporteCumplimientoExportarExcel');
    Route::post('/reporteria/reporte-presupuesto-exportar-excel', 'ReporteriaController@reportePresupuestoExportarExcel');
    Route::post('/reporteria/inventario-flota', 'ReporteriaController@inventarioFlota');
    Route::post('/reporteria/ingreso-datos', 'ReporteriaController@ingresoDatos');
    Route::put('/reporteria/reporteAnualRendimientoMeta', 'ReporteriaController@reporteAnualRendimientoMeta');

    Route::get('/mantenimientos/selectMantenimientoPlaca', 'MantenimientoController@selectMantenimientoPlaca');
    Route::get('/mantenimientos/descargarFormato', 'MantenimientoController@descargarFormato');
    Route::get('/mantenimientos', 'MantenimientoController@index');
    Route::get('/mantenimientos/ultimaFechaKilometrajeVehiculo', 'MantenimientoController@ultimaFechaKilometrajeVehiculo');
    Route::get('/mantenimientos/ultimoMantenimiento', 'MantenimientoController@ultimoMantenimiento');
    Route::post('/mantenimientos/registrar', 'MantenimientoController@store');
    Route::post('/mantenimientos/importar', 'MantenimientoController@importar');

});




