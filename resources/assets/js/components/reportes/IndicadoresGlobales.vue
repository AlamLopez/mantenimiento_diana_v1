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
                        <i class="fa fa-align-justify"></i><strong>INDICADORES GLOBALES</strong>
                    </div>
                    <div class="card-body prueba">
                        <div class="form-group row">
                            <div class="col-md-4"></div>
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
                                <p><b>AÑO: </b> <span v-text="nombre_anio"></span></p> 
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-2"></div>
                            <div class="col-md-2">
                                <!--button type="submit" @click="exportarExcel()" class="btn btn-warning text-white"><i class="fa fa-file-text"></i> EXPORTAR EXCEL</button-->
                            </div>
                        </div>
                        
                        <div class="indicadores" v-for="indicador in arrayIndicadorGlobal" :key="indicador.correlativo">
                            <div class="contenedor">
                                <table class="table table-reporte-anual table-responsive table-bordered table-striped table-sm">
                                    <thead>
                                        <tr class="bg-danger">
                                            <th colspan="13" class="align-middle" style="text-align: center;" v-text="indicador.nombre_distribuidora"></th>
                                        </tr>
                                        <tr class="bg-danger">
                                            <th class="align-middle" style="text-align: center;">INDICADORES</th>
                                            <th class="align-middle" style="text-align: center;">ENERO</th>
                                            <th class="align-middle" style="text-align: center;">FEBRERO</th>
                                            <th class="align-middle" style="text-align: center;">MARZO</th>
                                            <th class="align-middle" style="text-align: center;">ABRIL</th>
                                            <th class="align-middle" style="text-align: center;">MAYO</th>
                                            <th class="align-middle" style="text-align: center;">JUNIO</th>
                                            <th class="align-middle" style="text-align: center;">JULIO</th>
                                            <th class="align-middle" style="text-align: center;">AGOSTO</th>
                                            <th class="align-middle" style="text-align: center;">SEPTIEMBRE</th>
                                            <th class="align-middle" style="text-align: center;">OCTUBRE</th>
                                            <th class="align-middle" style="text-align: center;">NOVIEMBRE</th>
                                            <th class="align-middle" style="text-align: center;">DICIEMBRE</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="align-middle" style="text-align: center;">RECORRIDO</td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rec_enero"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rec_febrero"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rec_marzo"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rec_abril"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rec_mayo"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rec_junio"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rec_julio"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rec_agosto"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rec_septiembre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rec_octubre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rec_noviembre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rec_diciembre"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle" style="text-align: center;">CONSUMO TOTAL</td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.gal_enero"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.gal_febrero"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.gal_marzo"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.gal_abril"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.gal_mayo"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.gal_junio"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.gal_julio"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.gal_agosto"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.gal_septiembre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.gal_octubre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.gal_noviembre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.gal_diciembre"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle" style="text-align: center;">COSTO TOTAL COMBUSTIBLE</td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costo_enero"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costo_febrero"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costo_marzo"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costo_abril"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costo_mayo"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costo_junio"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costo_julio"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costo_agosto"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costo_septiembre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costo_octubre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costo_noviembre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costo_diciembre"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle" style="text-align: center;">COSTO TOTAL MANTENIMIENTO</td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle" style="text-align: center;">RENDIMIENTO</td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rend_enero"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rend_febrero"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rend_marzo"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rend_abril"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rend_mayo"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rend_junio"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rend_julio"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rend_agosto"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rend_septiembre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rend_octubre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rend_noviembre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.rend_diciembre"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle" style="text-align: center;">COSTO / KM COMBUSTIBLE</td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costoxkm_enero"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costoxkm_febrero"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costoxkm_marzo"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costoxkm_abril"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costoxkm_mayo"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costoxkm_junio"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costoxkm_julio"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costoxkm_agosto"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costoxkm_septiembre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costoxkm_octubre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costoxkm_noviembre"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="indicador.costoxkm_diciembre"></td>
                                        </tr>
                                        <tr>
                                            <td class="align-middle" style="text-align: center;">COSTO / KM MANTENIMIENTO</td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                            <td class="align-middle" style="text-align: center;"></td>
                                        </tr>
                                    </tbody>
                                </table>
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

        name : 'indicadores-globales',

        components: {

            vSelect

        },

        data (){

            return {

                anio : '',
                arrayIndicadorGlobal : [],
                reporte : 0,
                nombre_anio : '',
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
                var url = '/reporteria/indicadores-globales?anio=' + me.anio;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayIndicadorGlobal = respuesta.indicadoresglobales;
                    me.nombre_anio = respuesta.nombre_anio;
                    me.reporte = 1;
                    me.anio = '';
                    console.log(respuesta);
                })
                .catch(function (error) {
                    
                    console.log(error);

                })
            },

            exportarExcel(){

                let me = this;

                axios.post('/reporteria/reporte-recorrido-exportar-excel', {

                    'arrayReporteRecorrido' : me.arrayReporteRecorrido,
                    'anio' : me.nombre_anio,

                }).then(function (response) {

                    axios.get('/reporteria/reporte-recorrido-descargar-excel?anio=' + me.nombre_anio, {

                        responseType: "blob"

                    }).then((response) => {
                        
                        console.log(response.data);
                        FileSaver.saveAs(response.data, 'reporte-recorridos-' + me.nombre_anio + '.xlsx');
                        
                    });

                }).catch(function (error) {

                    console.log(error);

                });

            },

            validarReporte(){

                this.errorReporte = 0;
                this.errorMostrarMsjReporte = [];

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
            this.fechaActual2() ;
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

</style>
