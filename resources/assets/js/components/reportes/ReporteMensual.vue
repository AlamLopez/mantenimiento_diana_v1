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
                        <i class="fa fa-align-justify"></i><strong>REPORTE MENSUAL</strong>
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
                                <p><b>DATOS: </b> <span v-text="datos">RETALULHEU</span></p>
                                <p><b>PORCENTAJE: </b> <span v-text="porcentaje">RETALULHEU</span></p>
                            </div>
                            <div class="col-md-2">
                                <p><b>VALIDOS: </b> <span v-text="validos"></span></p>
                                <p><b>INVALIDOS: </b> <span v-text="invalidos"></span></p>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" @click="exportarExcel()" class="btn btn-warning text-white"><i class="fa fa-file-text"></i> EXPORTAR EXCEL</button>
                            </div>
                        </div>
                        <div class="table-wrap">
                            <table class="table table-responsive table-bordered table-striped table-collapse table-sm">
                                <thead>
                                    <tr class="bg-danger">
                                        <th style="text-align: center;">OPCIONES</th>
                                        <th style="text-align: center;">PLACA</th>
                                        <th style="text-align: center;">FECHA 1</th>
                                        <th style="text-align: center;">FECHA 2</th>
                                        <th style="text-align: center;">KM 1</th>
                                        <th style="text-align: center;">KM 2</th>
                                        <th style="text-align: center;">REC</th>
                                        <th style="text-align: center;">GAL</th>
                                        <th style="text-align: center;">COSTO</th>
                                        <th style="text-align: center;">REND</th>
                                        <th style="text-align: center;">COSTO/KM</th>
                                        <th style="text-align: center;">VALIDOS</th>
                                        <th style="text-align: center;">INVALIDOS</th>
                                        <th style="text-align: center;">STATUS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="reporte in arrayReporteMensual" :key="reporte.correlativo">
                                        <td style="text-align: center;">
                                            <button type="button" class="btn btn-info text-white btn-sm" @click="abrirModal('reporte', 'leer', reporte)" data-toggle="modal" data-target="#modalNuevo" title="INFO. VEHÍCULO">
                                                <i class="fa fa-eye"></i>
                                            </button> &nbsp;
                                        </td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.placa"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.fecha1"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.fecha2"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.km1"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.km2"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rec"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.gal"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costo"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.rend"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.costoxkm"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.validos"></td>
                                        <td class="align-middle" style="text-align: center;" v-text="reporte.invalidos"></td>
                                        <template v-if="reporte.status == 'OK'">
                                            <td style="text-align: center;" class="bg-success align-middle" v-text="reporte.status"></td>
                                        </template>
                                        <template v-else-if="reporte.status == 'SIN INF'">
                                            <td style="text-align: center;" class="bg-warning align-middle" v-text="reporte.status"></td>
                                        </template>
                                        <template v-else-if="reporte.status == 'ERROR DATA'">
                                            <td style="text-align: center;" class="bg-warning align-middle" v-text="reporte.status"></td>
                                        </template>
                                        <template v-else-if="reporte.status == 'DATA ERROR'">
                                            <td style="text-align: center;" class="bg-danger align-middle" v-text="reporte.status"></td>
                                        </template>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="bg-danger">
                                        <td style="text-align: center;" class="align-middle" colspan="6">GLOBAL:</td>
                                        <td style="text-align: center;" class="align-middle" v-text="total_recorridos"></td>
                                        <td style="text-align: center;" class="align-middle" v-text="total_galones"></td>
                                        <td style="text-align: center;" class="align-middle" v-text="total_costo"></td>
                                        <td style="text-align: center;" class="align-middle" v-text="total_rend"></td>
                                        <td style="text-align: center;" class="align-middle" v-text="total_costoxkm"></td>
                                        <td colspan="3"></td>
                                    </tr>
                                </tfoot>
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
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="text-input">MARCA:</label>
                            <div class="col-md-9">
                                <label class="form-control-label" v-text="marca"></label>
                            </div>
                            <label class="col-md-3 form-control-label" for="text-input">MODELO:</label>
                            <div class="col-md-9">
                                <label class="form-control-label" v-text="modelo"></label>
                            </div>
                            <label class="col-md-3 form-control-label" for="text-input">AÑO:</label>
                            <div class="col-md-9">
                                <label class="form-control-label" v-text="anio"></label>
                            </div>
                            <label class="col-md-3 form-control-label" for="text-input">CONDUCTOR:</label>
                            <div class="col-md-9">
                                <label class="form-control-label" v-text="conductor"></label>
                            </div>
                            <label class="col-md-3 form-control-label" for="text-input">RUTA:</label>
                            <div class="col-md-9">
                                <label class="form-control-label" v-text="ruta"></label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="cerrarModal()" class="btn btn-danger">CERRAR</button>
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

        name : 'reporte-mensual',

        components: {

            vSelect

        },

        data (){

            return {

                id_distribuidora : '',
                mes : '',
                anio : '',
                arrayReporteMensual : [],
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
                errorMostrarMsjReporte : []

            }

        },

        methods : {

            generarReporte(){

                if(this.validarReporte()){

                    return;
                    
                }

                let me = this;
                var url = '/reporteria/reporte-mensual?id_distribuidora=' + me.id_distribuidora + '&mes=' + me.mes + '&anio=' + me.anio;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayReporteMensual = respuesta.reportemensual;
                    me.total_recorridos = respuesta.total_recorridos;
                    me.total_galones = respuesta.total_galones;
                    me.total_rend = respuesta.total_rend;
                    me.total_costo = respuesta.total_costo;
                    me.total_costoxkm = respuesta.total_costoxkm;
                    me.distribuidora_nombre = respuesta.distribuidora;
                    me.nombre_mes = respuesta.nombremes;
                    me.nombre_anio = respuesta.anio;
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

            abrirModal(modelo, accion, data = []){

                switch (modelo) {

                    case 'reporte':
                        
                        {

                            switch (accion) {
                                
                                case 'leer':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'INFORMACIÓN VEHÍCULO'
                                        this.tipoAccion = 1;
                                        this.marca = data['marca'];
                                        this.modelo = data['modelo'];
                                        this.anio = data['anio'];
                                        this.conductor = data['conductor'];
                                        this.ruta = data['ruta'];
                                        break;
                                    }
                            
                            }

                        }
                        
                }
            },

            cerrarModal(){

                this.modal = 0;
                this.tipoAccion = 0;
                this.tituloModal = '';
                this.marca = '';
                this.modelo = '';
                this.anio = '';
                this.conductor = '';
                this.ruta = '';
            },

            exportarExcel(){

                let me = this;

                axios.post('/reporteria/reporte-mensual-exportar-excel', {

                    'arrayReporteMensual' : me.arrayReporteMensual,
                    'mes' : me.nombre_mes,
                    'anio' : me.nombre_anio,
                    'distribuidora_nombre' : me.distribuidora_nombre,
                    'total_recorridos': me.total_recorridos,
                    'total_galones' : me.total_galones,
                    'total_costo' : me.total_costo,
                    'total_rend' : me.total_rend,
                    'total_costoxkm' : me.total_costoxkm,

                }).then(function (response) {

                    axios.get('/reporteria/reporte-mensual-descargar-excel?mes=' + me.nombre_mes + '&anio=' + me.nombre_anio + '&distribuidora_nombre=' + me.distribuidora_nombre, {

                        responseType: "blob"

                    }).then((response) => {
                        
                        console.log(response.data);
                        FileSaver.saveAs(response.data, 'reporte-mensual-' + me.distribuidora_nombre + '-' + me.nombre_mes + '-' + me.nombre_anio + '.xlsx');
                        
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
        height: 800px;
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

    tfoot tr td {
        position: sticky;
        bottom: 0;
        background: #f86c6b;
    }

</style>
