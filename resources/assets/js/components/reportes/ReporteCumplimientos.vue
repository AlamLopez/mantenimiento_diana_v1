<template>
    <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">HOME</li>
                <li class="breadcrumb-item"><a href="#">ADMIN</a></li>
                <li class="breadcrumb-item active">DASHBOARD</li>
            </ol>
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card">
                    <div class="card-header text-white bg-danger">
                        <i class="fa fa-align-justify"></i><strong>REPORTE DE CUMPLIMIENTOS</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">DISTRIBUIDORA</div>
                                    <select class="form-control col-md-7" v-model="id_distribuidora">
                                        <option value="">SELECCIONE</option>
                                        <option v-for="nombreDistribuidora in arrayDistribuidora" :key="nombreDistribuidora.id" :value="nombreDistribuidora.id" v-text="nombreDistribuidora.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">MES</div>
                                    <select class="form-control col-md-5" v-model="mes">
                                        <option value="">SELECCIONE</option>
                                        <option value="01">ENERO</option>
                                        <option value="02">FEBRERO</option>
                                        <option value="03">MARZO</option>
                                        <option value="04">ABRIL</option>
                                        <option value="05">MAYO</option>
                                        <option value="06">JUNIO</option>
                                        <option value="07">JULIO</option>
                                        <option value="08">AGOSTO</option>
                                        <option value="09">SEPTIEMBRE</option>
                                        <option value="10">OCTUBRE</option>
                                        <option value="11">NOVIEMBRE</option>
                                        <option value="12">DICIEMBRE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">AÑO</div>
                                    <input type="number" class="form-control" v-model="anio" placeholder="AÑO">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" @click="generarReporte()" class="btn btn-warning text-white"><i class="fa fa-file-text"></i> GENERAR REPORTE</button>
                            </div>
                        </div>
                        <div v-show="errorReporte" class="form-group row div-error">
                            <div class="text-center text-error">
                                <div v-for="error in errorMostrarMsjReporte" :key="error" v-text="error">

                                </div>
                            </div>
                        </div>
                        <div class="form-group row" v-if="reporte!=0">
                            <div class="col-md-4">
                                <p><b>DISTRIBUIDORA: </b> <span v-text="distribuidora_nombre"></span></p> 
                                <p><b>MES: </b> <span v-text="nombre_mes + ' ' + nombre_anio"></span></p> 
                            </div>
                            <div class="col-md-4">
                                <p><b>INDICADOR DE CUMPLIMIENTO: </b> <span v-text="indicador_cumplimiento + ' %'"></span></p> 
                            </div>
                            <div class="col-md-2">
                                
                            </div>
                            <div class="col-md-2">
                                <button type="submit" @click="exportarExcel()" class="btn btn-warning text-white"><i class="fa fa-file-text"></i> EXPORTAR EXCEL</button>
                            </div>
                        </div>
                        <div v-if="reporte!=0">
                            <div class="col-md-12 mb-4">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#realizados4" role="tab" aria-controls="home">
                                            <i class="icon-calculator"></i> REALIZADOS
                                            <span class="badge badge-success" v-text="cant_mtto_realizados"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#proyectados4" role="tab" aria-controls="profile">
                                            <i class="icon-basket-loaded"></i> PROYECTADOS
                                            <span class="badge badge-pill badge-warning" v-text="cant_mtto_proyectados"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#atrasados4" role="tab" aria-controls="profile">
                                            <i class="icon-basket-loaded"></i> ATRASADOS
                                            <span class="badge badge-pill badge-danger" v-text="cant_mtto_atrasados"></span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="realizados4" role="tabpanel">
                                        <div class="table-wrap">
                                            <table class="table table-responsive table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr class="bg-danger">
                                                        <th class="align-middle" style="text-align: center;">NUMERO VEHICULO</th>
                                                        <th class="align-middle" style="text-align: center;">PLACA</th>
                                                        <th class="align-middle" style="text-align: center;">FECHA</th>
                                                        <th class="align-middle" style="text-align: center;">RUTINA</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="reporte in arrayMantenimientoRealizado" :key="reporte.id">
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.numero_vehiculo"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.placa"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.fecha"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.nombre_rutina_anterior"></td>
                                                    </tr>
                                                </tbody>                                     
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="proyectados4" role="tabpanel">
                                        <div class="table-wrap">
                                            <table class="table table-responsive table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr class="bg-danger">
                                                        <th colspan="6"></th>
                                                        <th colspan="2" style="text-align: center;">MANTENIMIENTO</th>
                                                        <th colspan="3"></th>
                                                    </tr>
                                                    <tr class="bg-danger">
                                                        <th class="align-middle" style="text-align: center;">No.</th>
                                                        <th class="align-middle" style="text-align: center;">PLACA</th>
                                                        <th class="align-middle" style="text-align: center;">CONDUCTOR</th>
                                                        <th class="align-middle" style="text-align: center;">TALLER</th>
                                                        <th class="align-middle" style="text-align: center;">RUTINA</th>
                                                        <th class="align-middle" style="text-align: center;">CRITERIO CUMPL.</th>
                                                        <th class="align-middle" style="text-align: center;">FECHA</th>
                                                        <th class="align-middle" style="text-align: center;">KM</th>
                                                        <th class="align-middle" style="text-align: center;">KM ACTUAL</th>
                                                        <th class="align-middle" style="text-align: center;">CRITICIDAD</th>
                                                        <th class="align-middle" style="text-align: center;">FECHA</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="reporte in arrayMantenimientoProyectado" :key="reporte.correlativo">
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.numero_vehiculo"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.placa"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.conductor"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.taller"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rutina"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.criterio_cumpl"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.fecha_mtto"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.km_mtto"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.km_actual"></td>
                                                        <template v-if="reporte.criticidad == 'ALTA'">
                                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.criticidad"></td>
                                                        </template>
                                                        <template v-else-if="reporte.criticidad == 'MEDIA'">
                                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.criticidad"></td>
                                                        </template>
                                                        <template v-else-if="reporte.criticidad == 'BAJA'">
                                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.criticidad"></td>
                                                        </template>
                                                        <template v-else-if="reporte.criticidad == 'PLANIFICADA'">
                                                            <td class="align-middle bg-primary" style="text-align: center;" v-text="reporte.criticidad"></td>
                                                        </template>
                                                        <template v-if="reporte.criterio_cumpl == 'KILOMETRAJE'">
                                                            <td class="align-middle" style="text-align: center;" v-text="reporte.fecha_prox_manto_kms"></td>
                                                        </template>
                                                        <template v-else-if="reporte.criterio_cumpl == 'TIEMPO'">
                                                            <td class="align-middle" style="text-align: center;" v-text="reporte.fecha_prox_manto_tiempo"></td>
                                                        </template>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="tab-pane" id="atrasados4" role="tabpanel">
                                        <div class="table-wrap">
                                            <table class="table table-responsive table-bordered table-striped table-sm">
                                                <thead>
                                                    <tr class="bg-danger">
                                                        <th colspan="7"></th>
                                                        <th colspan="2" style="text-align: center;">MANTENIMIENTO</th>
                                                        <th colspan="2"></th>
                                                    </tr>
                                                    <tr class="bg-danger">
                                                        <th class="align-middle" style="text-align: center;">No.</th>
                                                        <th class="align-middle" style="text-align: center;">PLACA</th>
                                                        <th class="align-middle" style="text-align: center;">CONDUCTOR</th>
                                                        <th class="align-middle" style="text-align: center;">TALLER</th>
                                                        <th class="align-middle" style="text-align: center;">PROYECCIÓN</th>
                                                        <th class="align-middle" style="text-align: center;">RUTINA</th>
                                                        <th class="align-middle" style="text-align: center;">CRITERIO CUMPL.</th>
                                                        <th class="align-middle" style="text-align: center;">FECHA</th>
                                                        <th class="align-middle" style="text-align: center;">KM</th>
                                                        <th class="align-middle" style="text-align: center;">KM ACTUAL</th>
                                                        <th class="align-middle" style="text-align: center;">CRITICIDAD</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="reporte in arrayMantenimientoAtrasado" :key="reporte.correlativo2">
                                                        
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.numero_vehiculo"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.placa"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.conductor"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.taller"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.proyeccion"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rutina"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.criterio_cumpl"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.fecha_mtto"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.km_mtto"></td>
                                                        <td class="align-middle" style="text-align: center;" v-text="reporte.km_actual"></td>
                                                        <template v-if="reporte.criticidad == 'ALTA'">
                                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.criticidad"></td>
                                                        </template>
                                                        <template v-else-if="reporte.criticidad == 'MEDIA'">
                                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.criticidad"></td>
                                                        </template>
                                                        <template v-else-if="reporte.criticidad == 'BAJA'">
                                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.criticidad"></td>
                                                        </template>
                                                        <template v-else-if="reporte.criticidad == 'PLANIFICADA'">
                                                            <td class="align-middle bg-primary" style="text-align: center;" v-text="reporte.criticidad"></td>
                                                        </template>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.col-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
        </main>
</template>

<script>

    import Vue from 'vue';
    import vSelect from 'vue-select';
    import FileSaver from 'file-saver';

    import 'vue-select/dist/vue-select.css';

    Vue.component('v-select', vSelect);

    export default {

        name : 'reporte-cumplimientos',

        components: {

            vSelect

        },

        data (){

            return {

                id_distribuidora : '',
                mes : '',
                anio : '',
                arrayMantenimientoRealizado : [],
                arrayMantenimientoProyectado : [],
                arrayMantenimientoAtrasado : [],
                cant_mtto_realizados : '',
                cant_mtto_proyectados : '',
                cant_mtto_atrasados : '',
                indicador_cumplimiento : '',
                arrayDistribuidora : [],
                reporte : 0,
                distribuidora_nombre : '',
                nombre_mes : '',
                nombre_anio : '',
                buscar : '',
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorReporte : 0,
                errorMostrarMsjReporte : []

            }

        },

        methods : {

            generarReporte(){

                if(this.validarReporte()){

                    return;
                    
                }

                let me = this;
                var url = '/reporteria/reporte-cumplimientos?id_distribuidora=' + me.id_distribuidora + '&mes=' + me.mes + '&anio=' + me.anio;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    console.log(respuesta);
                    me.arrayMantenimientoRealizado = respuesta.mantenimientos_realizados;
                    me.arrayMantenimientoProyectado = respuesta.mantenimientos_proyectados;
                    me.arrayMantenimientoAtrasado = respuesta.mantenimientos_atrasados;
                    me.cant_mtto_realizados = respuesta.cant_mtto_realizados;
                    me.cant_mtto_proyectados = respuesta.cant_mtto_proyectados;
                    me.cant_mtto_atrasados = respuesta.cant_mtto_atrasados;
                    me.indicador_cumplimiento = respuesta.indicador_cumplimiento;
                    me.distribuidora_nombre = respuesta.distribuidora_nombre;
                    me.nombre_mes = respuesta.nombre_mes;
                    me.nombre_anio = respuesta.nombre_anio;
                    me.datos = respuesta.datos;
                    me.validos = respuesta.validos;
                    me.invalidos = respuesta.invalidos;
                    me.porcentaje = respuesta.porcentaje;
                    me.reporte = 1;
                    me.mes = '';
                    me.anio = '';
                    me.id_distribuidora = '';
                    console.log(respuesta);
                })
                .catch(function (error) {
                    
                    console.log(error);

                })
            },

            selectDistribuidora(){

                let me = this;
                var url = '/distribuidoras/selectDistribuidora';
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayDistribuidora = respuesta.distribuidoras;
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            exportarExcel(){

                let me = this;

                axios.post('/reporteria/reporte-cumplimiento-exportar-excel', {

                    'mantenimientosrealizados' : me.arrayMantenimientoRealizado,
                    'mantenimientosproyectados' : me.arrayMantenimientoProyectado,
                    'mantenimientosatrasados' : me.arrayMantenimientoAtrasado,
                    'mes' : me.nombre_mes,
                    'anio' : me.nombre_anio,
                    'distribuidora_nombre' : me.distribuidora_nombre,
                    'indicador_cumplimiento': me.indicador_cumplimiento,
                    'cant_mtto_realizados' : me.cant_mtto_realizados,
                    'cant_mtto_proyectados' : me.cant_mtto_proyectados,
                    'cant_mtto_atrasados' : me.cant_mtto_atrasados

                }).then(function (response) {

                    axios.get('/reporteria/reporte-cumplimiento-descargar-excel?mes=' + me.nombre_mes + '&anio=' + me.nombre_anio + '&distribuidora_nombre=' + me.distribuidora_nombre, {

                        responseType: "blob"

                    }).then((response) => {
                        
                        console.log(response.data);
                        FileSaver.saveAs(response.data, 'reporte-cumplimiento-' + me.distribuidora_nombre + '-' + me.nombre_mes + '-' + me.nombre_anio + '.xlsx');
                        
                    });

                }).catch(function (error) {

                    console.log(error);

                });

            },

            validarReporte(){

                this.errorReporte = 0;
                this.errorMostrarMsjReporte = [];

                if(!this.id_distribuidora) this.errorMostrarMsjReporte.push("DEBE SELECCIONAR UNA DISTRIBUIDORA.");  
                if(!this.mes) this.errorMostrarMsjReporte.push("DEBE SELECCIONAR UN MES.");
                if(!this.anio) this.errorMostrarMsjReporte.push("DEBE INDICAR UN AÑO.");
        
                if(this.errorMostrarMsjReporte.length) this.errorReporte = 1;

                return this.errorReporte;

            },

            fechaActual2(){
                
                let hoy = new Date();

                let dd = hoy.getDate();
                let mm = hoy.getMonth()+1;
                let yyyy = hoy.getFullYear();
        
                this.anio = yyyy;

            },

        },

        mounted() {

            this.selectDistribuidora();
            this.fechaActual2();

        }

    }
</script>

<style>

    .modal-content{

        width: 100% !important;
        position: fixed !important;

    }

    .mostrar{

        display: list-item !important;
        opacity: 1 !important;
        position: fixed !important;
        background-color: #3c29297a !important;

    }

    .div-error{

        display: flex;
        justify-content: center;

    }

    .text-error{
        color: red !important;
        font-weight: bold;
    }

    .pagination > li > a{
        background-color: white;
        color: #d96465 !important;
    }

    .pagination > li > a:focus,
    .pagination > li > a:hover,
    .pagination > li > span:focus,
    .pagination > li > span:hover{
        color: #d96465 !important;
        background-color: #eee !important;
        border-color: #ddd !important;
    }

    .pagination > .active > a{
        color: white !important;
        background-color: #d96465 !important;
        border: solid 1px #d96465 !important;
    }

    .pagination > .active > a:hover{
        background-color: #d96465 !important;
        border: solid 1px #d96465 !important;
    }

    .table-wrap {
        height: 400px;
        overflow: auto;
    }

    thead tr th {
        position: sticky;
        top: 0;
        border-left: 1px dotted rgba(200, 209, 224, 0.6);
        border-bottom: 1px solid #c2cfd6;
        background: #f86c6b;
        /* With border-collapse, we must use box-shadow or psuedo elements
        for the header borders */
        box-shadow: 0px 0px 0 1px #c2cfd6;
    }

</style>
