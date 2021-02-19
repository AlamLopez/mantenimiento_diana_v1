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
                        <i class="fa fa-align-justify"></i><strong>PRESUPUESTOS</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
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
                                    <input type="number" class="form-control" min="0" v-model="anio" placeholder="AÑO">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">TALLER</div>
                                    <select class="form-control col-md-5" v-model="tipo_taller">
                                        <option value="">SELECCIONE</option>
                                        <option value="DIANA">DIANA</option>
                                        <option value="AGENCIA">AGENCIA</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-daterange">
                                    Incluir meses anteriores
                                    <input type="checkbox" v-model="meses_anteriores" class="form-control"> 
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
                        <br>
                        <div class="form-group row" v-if="reporte!=0">
                            <div class="col-md-4">
                                <p><b>DISTRIBUIDORA: </b> <span v-text="distribuidora_nombre"></span></p> 
                                <p><b>MES: </b> <span v-text="nombre_mes"></span></p> 
                            </div>
                            <div class="col-md-6">
                                <p><b>TALLER: </b> <span v-text="nombre_tipo_taller"></span></p> 
                                <p><b>ANIO: </b> <span v-text="nombre_anio"></span></p> 
                            </div>
                            <div class="col-md-2">
                                <button type="submit" @click="exportarExcel()" class="btn btn-warning text-white"><i class="fa fa-file-text"></i> EXPORTAR EXCEL</button>
                            </div>
                        </div>
                    </div>
                    <div v-if="reporte!=0" class="col-md-12 mb-4">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#realizados4" role="tab" aria-controls="home">
                                    <i class="icon-calculator"></i> RUTINAS
                                    <span class="badge badge-success"></span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#proyectados4" role="tab" aria-controls="profile">
                                    <i class="icon-basket-loaded"></i> REPUESTOS
                                    <span class="badge badge-pill badge-warning"></span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="realizados4" role="tabpanel">
                                <div class="table-wrap">
                                    <table class="table table-responsive table-bordered table-striped table-sm">
                                        <thead>
                                            <tr class="bg-danger">
                                                <th style="text-align: center;">PLACA</th>
                                                <th style="text-align: center;">MODELO</th>
                                                <th style="text-align: center;">CONDUCTOR</th>
                                                <th style="text-align: center;">RUTINA</th>
                                                <th style="text-align: center;">TALLER</th>
                                                <th style="text-align: center;">FECHA MAS PROX.</th>
                                                <th style="text-align: center;">COSTO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="reporte_rutinas in arrayReporteRutinas" :key="reporte_rutinas.correlativo">
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_rutinas.placa"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_rutinas.modelo"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_rutinas.conductor"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_rutinas.rutina"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_rutinas.taller"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_rutinas.fecha_mas_prox"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_rutinas.costo"></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-danger">
                                                <td style="text-align: center;" class="align-middle" colspan="6">TOTAL:</td>
                                                <td style="text-align: center;" class="align-middle" v-text="total_costosrutinas"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="proyectados4" role="tabpanel">
                                <div class="table-wrap">
                                    <table class="table table-responsive table-bordered table-striped table-sm">
                                        <thead>
                                            <tr class="bg-danger">
                                                <th style="text-align: center;">MATERIAL</th>
                                                <th style="text-align: center;">REPUESTO</th>
                                                <th style="text-align: center;">DESCRIPCIÓN</th>
                                                <th style="text-align: center;">MODELO ASOCIADO</th>
                                                <th style="text-align: center;">CANTIDAD</th>
                                                <th style="text-align: center;">COSTO</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="reporte_repuestos in arrayReporteRepuestos" :key="reporte_repuestos.correlativo2">
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_repuestos.material"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_repuestos.repuesto"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_repuestos.descripcion"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_repuestos.modelo"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_repuestos.cantidad"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="reporte_repuestos.costo"></td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-danger">
                                                <td style="text-align: center;" class="align-middle" colspan="5">TOTAL:</td>
                                                <td style="text-align: center;" class="align-middle" v-text="total_costosrepuestos"></td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            
                        </div>
                        <!-- /.col-->
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

        name : 'reporte-presupuestos',

        components: {

            vSelect

        },

        data (){

            return {

                costorepuesto_id : 0,
                rol_id : 0,
                id_distribuidora : '',
                mes : '',
                anio : '',
                tipo_taller : '',
                meses_anteriores : false,
                reporte : 0,
                arrayDistribuidora : [],
                arrayReporteRutinas : [],
                arrayReporteRepuestos : [],
                distribuidoras : [],
                total_costosrutinas : '',
                total_costosrepuestos : '',
                modelo : '',
                rutinas : '',
                material : '',
                repuesto : '',
                descripcion : '',
                cantidad : 0,
                costo : 0,
                nombre_mes : '',
                nombre_anio : '',
                distribuidora_nombre : '',
                nombre_tipo_taller : '',
                errorReporte : 0,
                errorMostrarMsjReporte : []

            }

        },

        computed : {

            isActived : function(){
                return this.pagination.current_page;
            },

            pagesNumber: function() {

                if(!this.pagination.to) {
                    return [];
                }

                var from = this.pagination.current_page - this.offset;

                if(from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);

                if(to >= this.pagination.last_page){
                    to = this.pagination.last_page;
                }

                var pagesArray = [];

                while(from <= to){
                    pagesArray.push(from);
                    from++;
                }

                return pagesArray;

            }

        },

        methods : {

            generarReporte(){

                if(this.validarReporte()){

                    return;
                    
                }

                let me = this;
                var url = '/reporteria/reporte-presupuestos?id_distribuidora=' + me.id_distribuidora + '&mes=' + me.mes + '&anio=' + me.anio + '&tipo_taller=' + me.tipo_taller + '&meses_anteriores=' + me.meses_anteriores;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    console.log(respuesta);
                    me.arrayReporteRutinas = respuesta.reporterutinas;
                    me.arrayReporteRepuestos = respuesta.reporterepuestos;
                    me.total_costosrutinas = respuesta.total_costosrutinas;
                    me.total_costosrepuestos = respuesta.total_costosrepuestos;
                    me.distribuidora_nombre = respuesta.distribuidora_nombre;
                    me.nombre_mes = respuesta.nombre_mes;
                    me.nombre_anio = respuesta.nombre_anio;
                    me.nombre_tipo_taller = respuesta.nombre_tipo_taller;
                    me.reporte = 1;
                    me.mes = '';
                    me.anio = '';
                    me.id_distribuidora = '';
                    me.tipo_taller = '';
                    me.meses_anteriores = false;
                    console.log(respuesta);
                })
                .catch(function (error) {
                    
                    console.log(error);

                })
            },

            exportarExcel(){

                let me = this;

                axios.post('/reporteria/reporte-presupuesto-exportar-excel', {

                    'reporte_rutinas' : me.arrayReporteRutinas,
                    'reporte_repuestos' : me.arrayReporteRepuestos,
                    'mes' : me.nombre_mes,
                    'anio' : me.nombre_anio,
                    'nombre_tipo_taller' : me.nombre_tipo_taller,
                    'distribuidora_nombre' : me.distribuidora_nombre,
                    'total_reporte_rutinas' : me.total_costosrutinas,
                    'total_reporte_repuestos' : me.total_costosrepuestos,

                }).then(function (response) {

                    axios.get('/reporteria/reporte-presupuesto-descargar-excel?mes=' + me.nombre_mes + '&anio=' + me.nombre_anio + '&distribuidora_nombre=' + me.distribuidora_nombre, {

                        responseType: "blob"

                    }).then((response) => {
                        
                        console.log(response.data);
                        FileSaver.saveAs(response.data, 'reporte-presupuesto-' + me.distribuidora_nombre + '-' + me.nombre_mes + '-' + me.nombre_anio + '.xlsx');
                        
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
                if(!this.tipo_taller) this.errorMostrarMsjReporte.push("DEBE SELECCIONAR UN TALLER.");
        
                if(this.errorMostrarMsjReporte.length) this.errorReporte = 1;

                return this.errorReporte;

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
        position: absolute !important;

    }

    .mostrar{

        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
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

    .table-container-costo{
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
