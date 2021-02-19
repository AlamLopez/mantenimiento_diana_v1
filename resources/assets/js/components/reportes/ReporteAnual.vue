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
                        <i class="fa fa-align-justify"></i><strong>REPORTE ANUAL</strong>
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
                            <div class="col-md-4"></div>
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
                                <p><b>AÑO: </b> <span v-text="nombre_anio"></span></p> 
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-2"></div>
                            <div class="col-md-2">
                                <button type="submit" @click="exportarExcel()" class="btn btn-warning text-white"><i class="fa fa-file-text"></i> EXPORTAR EXCEL</button>
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-reporte-anual table-responsive table-bordered table-striped table-sm">
                                <thead>
                                    <tr class="bg-danger">
                                        <th colspan="8" style="text-align: center;" class="">VEHÍCULO</th>
                                        <th colspan="3" style="text-align: center;">ENERO</th>
                                        <th colspan="3" style="text-align: center;">FEBRERO</th>
                                        <th colspan="3" style="text-align: center;">MARZO</th>
                                        <th colspan="3" style="text-align: center;">ABRIL</th>
                                        <th colspan="3" style="text-align: center;">MAYO</th>
                                        <th colspan="3" style="text-align: center;">JUNIO</th>
                                        <th colspan="3" style="text-align: center;">JULIO</th>
                                        <th colspan="3" style="text-align: center;">AGOSTO</th>
                                        <th colspan="3" style="text-align: center;">SEPTIEMBRE</th>
                                        <th colspan="3" style="text-align: center;">OCTUBRE</th>
                                        <th colspan="3" style="text-align: center;">NOVIEMBRE</th>
                                        <th colspan="3" style="text-align: center;">DICIEMBRE</th>
                                    </tr>
                                    <tr class="bg-danger">
                                        <th style="text-align: center;" class="sticky">OPCIONES</th>
                                        <th style="text-align: center;" class="sticky">PLACA</th>
                                        <th style="text-align: center;" class="sticky2">MODELO</th>
                                        <th style="text-align: center;" class="sticky3">AÑO</th>
                                        <th style="text-align: center;" class="sticky4">KM</th>
                                        <th style="text-align: center;" class="sticky5">CONDUCTOR</th>
                                        <th style="text-align: center;" class="sticky6">RUTA</th>
                                        <th style="text-align: center;" class="sticky7">REND META</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">CST/KM</th>
                                        <th style="text-align: center;">REND</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">CST/KM</th>
                                        <th style="text-align: center;">REND</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">CST/KM</th>
                                        <th style="text-align: center;">REND</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">CST/KM</th>
                                        <th style="text-align: center;">REND</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">CST/KM</th>
                                        <th style="text-align: center;">REND</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">CST/KM</th>
                                        <th style="text-align: center;">REND</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">CST/KM</th>
                                        <th style="text-align: center;">REND</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">CST/KM</th>
                                        <th style="text-align: center;">REND</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">CST/KM</th>
                                        <th style="text-align: center;">REND</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">CST/KM</th>
                                        <th style="text-align: center;">REND</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">CST/KM</th>
                                        <th style="text-align: center;">REND</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">CST/KM</th>
                                        <th style="text-align: center;">REND</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="reporte in arrayReporteAnual" :key="reporte.correlativo">
                                        <td class="align-middle" style="text-align: center;">
                                            <button type="button" @click="abrirModal('reporteAnual', 'actualizar', reporte)" class="btn btn-warning text-white btn-sm" data-toggle="modal" data-target="#modalNuevo" title="ACTUALIZAR RENDIMIENTO META">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                        </td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.placa"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.modelo"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.anio"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.km"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.conductor"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.ruta"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rend_meta"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec_enero"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm_enero"></td>
                                        <template v-if="reporte.rend_enero > reporte.rend_meta">
                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.rend_enero"></td>
                                        </template>
                                        <template v-else-if="reporte.rend_enero <= reporte.rend_meta && reporte.rend_enero > (reporte.rend_meta * 0.95)">
                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.rend_enero"></td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.rend_enero"></td>
                                        </template>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec_febrero"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm_febrero"></td>
                                        <template v-if="reporte.rend_febrero > reporte.rend_meta">
                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.rend_febrero"></td>
                                        </template>
                                        <template v-else-if="reporte.rend_febrero <= reporte.rend_meta && reporte.rend_febrero > (reporte.rend_meta * 0.95)">
                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.rend_febrero"></td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.rend_febrero"></td>
                                        </template>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec_marzo"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm_marzo"></td>
                                        <template v-if="reporte.rend_marzo > reporte.rend_meta">
                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.rend_marzo"></td>
                                        </template>
                                        <template v-else-if="reporte.rend_marzo <= reporte.rend_meta && reporte.rend_marzo > (reporte.rend_meta * 0.95)">
                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.rend_marzo"></td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.rend_marzo"></td>
                                        </template>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec_abril"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm_abril"></td>
                                        <template v-if="reporte.rend_abril > reporte.rend_meta">
                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.rend_abril"></td>
                                        </template>
                                        <template v-else-if="reporte.rend_abril <= reporte.rend_meta && reporte.rend_abril > (reporte.rend_meta * 0.95)">
                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.rend_abril"></td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.rend_abril"></td>
                                        </template>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec_mayo"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm_mayo"></td>
                                        <template v-if="reporte.rend_mayo > reporte.rend_meta">
                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.rend_mayo"></td>
                                        </template>
                                        <template v-else-if="reporte.rend_mayo <= reporte.rend_meta && reporte.rend_mayo > (reporte.rend_meta * 0.95)">
                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.rend_mayo"></td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.rend_mayo"></td>
                                        </template>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec_junio"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm_junio"></td>
                                        <template v-if="reporte.rend_junio > reporte.rend_meta">
                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.rend_junio"></td>
                                        </template>
                                        <template v-else-if="reporte.rend_junio <= reporte.rend_meta && reporte.rend_junio > (reporte.rend_meta * 0.95)">
                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.rend_junio"></td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.rend_junio"></td>
                                        </template>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec_julio"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm_julio"></td>
                                        <template v-if="reporte.rend_julio > reporte.rend_meta">
                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.rend_julio"></td>
                                        </template>
                                        <template v-else-if="reporte.rend_julio <= reporte.rend_meta && reporte.rend_julio > (reporte.rend_meta * 0.95)">
                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.rend_julio"></td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.rend_julio"></td>
                                        </template>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec_agosto"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm_agosto"></td>
                                        <template v-if="reporte.rend_agosto > reporte.rend_meta">
                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.rend_agosto"></td>
                                        </template>
                                        <template v-else-if="reporte.rend_agosto <= reporte.rend_meta && reporte.rend_agosto > (reporte.rend_meta * 0.95)">
                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.rend_agosto"></td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.rend_agosto"></td>
                                        </template>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec_septiembre"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm_septiembre"></td>
                                        <template v-if="reporte.rend_septiembre > reporte.rend_meta">
                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.rend_septiembre"></td>
                                        </template>
                                        <template v-else-if="reporte.rend_septiembre <= reporte.rend_meta && reporte.rend_septiembre > (reporte.rend_meta * 0.95)">
                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.rend_septiembre"></td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.rend_septiembre"></td>
                                        </template>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec_octubre"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm_octubre"></td>
                                        <template v-if="reporte.rend_octubre > reporte.rend_meta">
                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.rend_octubre"></td>
                                        </template>
                                        <template v-else-if="reporte.rend_octubre <= reporte.rend_meta && reporte.rend_octubre > (reporte.rend_meta * 0.95)">
                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.rend_octubre"></td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.rend_octubre"></td>
                                        </template>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec_noviembre"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm_noviembre"></td>
                                        <template v-if="reporte.rend_noviembre > reporte.rend_meta">
                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.rend_noviembre"></td>
                                        </template>
                                        <template v-else-if="reporte.rend_noviembre <= reporte.rend_meta && reporte.rend_noviembre > (reporte.rend_meta * 0.95)">
                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.rend_noviembre"></td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.rend_noviembre"></td>
                                        </template>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec_diciembre"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm_diciembre"></td>
                                        <template v-if="reporte.rend_diciembre > reporte.rend_meta">
                                            <td class="align-middle bg-success" style="text-align: center;" v-text="reporte.rend_diciembre"></td>
                                        </template>
                                        <template v-else-if="reporte.rend_diciembre <= reporte.rend_meta && reporte.rend_diciembre > (reporte.rend_meta * 0.95)">
                                            <td class="align-middle bg-warning" style="text-align: center;" v-text="reporte.rend_diciembre"></td>
                                        </template>
                                        <template v-else>
                                            <td class="align-middle bg-danger" style="text-align: center;" v-text="reporte.rend_diciembre"></td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-warning modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" @click="cerrarModal()" class="close" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">PLACA:</label>
                                <div class="col-md-9">
                                    <input type="TEXT" v-model="placa" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">RENDIMIENTO META:</label>
                                <div class="col-md-9">
                                    <input type="number" v-model="rendimiento_meta" class="form-control" placeholder="RENDIMIENTO META" autofocus>
                                </div>
                            </div>
                            <div v-show="errorRendimientoMeta" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjRendimientoMeta" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" @click="cerrarModal()">CERRAR</button>
                        <button type="button" v-if="tipoAccion==1" @click="actualizarRendimientoMeta()" class="btn btn-warning text-white">ACTUALIZAR</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>

    import Vue from 'vue';
    import vSelect from 'vue-select';
    import FileSaver from 'file-saver';

    import 'vue-select/dist/vue-select.css';

    Vue.component('v-select', vSelect);

    export default {

        name : 'reporte-anual',

        components: {

            vSelect

        },

        data (){

            return {

                id_distribuidora : '',
                mes : '',
                anio : '',
                arrayReporteAnual : [],
                arrayDistribuidora : [],
                reporte : 0,
                total_recorridos: '',
                total_galones : '',
                total_costo : '',
                total_rend : '',
                total_costoxkm : '',
                distribuidora_nombre : '',
                nombre_mes : '',
                nombre_anio : '',
                datos : '',
                validos : '',
                invalidos : '',
                porcentaje : '',
                marca : '',
                modelo : '',
                anio : '',
                conductor : '',
                ruta : '',
                select_status : '',
                buscar : '',
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorReporte : 0,
                errorMostrarMsjReporte : [],
                placa : '',
                rendimiento_meta : '',
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorRendimientoMeta : 0,
                errorMostrarMsjRendimientoMeta : [],

            }

        },

        methods : {

            generarReporte(){

                if(this.validarReporte()){

                    return;
                    
                }

                let me = this;
                var url = '/reporteria/reporte-anual?id_distribuidora=' + me.id_distribuidora + '&anio=' + me.anio;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayReporteAnual = respuesta.reporteanual;
                    me.distribuidora_nombre = respuesta.distribuidora_nombre;
                    me.nombre_anio = respuesta.nombre_anio;
                    me.reporte = 1;
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

                axios.post('/reporteria/reporte-anual-exportar-excel', {

                    'arrayReporteAnual' : me.arrayReporteAnual,
                    'anio' : me.nombre_anio,
                    'distribuidora_nombre' : me.distribuidora_nombre

                }).then(function (response) {

                    axios.get('/reporteria/reporte-anual-descargar-excel?anio=' + me.nombre_anio + '&distribuidora_nombre=' + me.distribuidora_nombre, {

                        responseType: "blob"

                    }).then((response) => {
                        
                        console.log(response.data);
                        FileSaver.saveAs(response.data, 'reporte-anual-' + me.distribuidora_nombre + '-' + me.nombre_anio + '.xlsx');
                        
                    });

                }).catch(function (error) {

                    console.log(error);

                });

            },

            validarReporte(){

                this.errorReporte = 0;
                this.errorMostrarMsjReporte = [];

                if(!this.id_distribuidora) this.errorMostrarMsjReporte.push("DEBE SELECCIONAR UNA DISTRIBUIDORA.");  
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

            abrirModal(modelo, accion, data = []){

                switch (modelo) {

                    case 'reporteAnual':
                        
                        {

                            switch (accion) {

                                case 'actualizar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'ACTUALIZAR VEHÍCULO';
                                        this.placa = data['placa'];
                                        this.rendimiento_meta = data['rend_meta'];
                                        this.tipoAccion = 1;
                                        break;
                                    }
                            
                            }

                        }
                        
                }
            },

            cerrarModal(){
                this.modal = 0;
                this.tituloModal = '';
                this.tipoAccion = 0;
                this.placa = '';
                this.rendimiento_meta = '';
            },

            validarRendimientoMeta(){
                
                this.errorRendimientoMeta = 0;
                this.errorMostrarMsjRendimientoMeta = [];

                if(!this.rendimiento_meta) this.errorMostrarMsjRendimientoMeta.push("EL RENDIMIENTO META NO PUEDE IR VACÍO.");
                if(this.errorMostrarMsjRendimientoMeta.length) this.errorRendimientoMeta = 1;

                return this.errorRendimientoMeta;

            },

            actualizarRendimientoMeta(){

                if(this.validarRendimientoMeta()){

                    return;
                    
                }

                let me = this;

                axios.put('/reporteria/reporteAnualRendimientoMeta', {

                    'placa': this.placa,
                    'rendimiento_meta': this.rendimiento_meta,
                    'anio' : this.nombre_anio

                }).then(function (response) {
                    var respuesta = response.data;
                    me.id_distribuidora = respuesta.id_distribuidora;
                    me.anio = respuesta.anio;
                    me.cerrarModal();
                    me.generarReporte();

                }).catch(function (error) {

                    console.log(error);

                });

            },

        },

        mounted() {

            this.selectDistribuidora();
            this.fechaActual2()

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

    .table-container{
        max-width: 100%;
        overflow-x: scroll;
        height: 500px;
        overflow-y: auto;
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
