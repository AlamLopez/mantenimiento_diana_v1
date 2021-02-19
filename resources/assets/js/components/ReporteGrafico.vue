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
                        <i class="fa fa-align-justify"></i><strong>REPORTE GRÁFICO</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-12 mb-4">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#realizados4" role="tab" aria-controls="home">
                                            <i class="icon-calculator"></i> RENDIMIENTO POR DESPACHO
                                            <span class="badge badge-success"></span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#proyectados4" role="tab" aria-controls="profile">
                                            <i class="icon-basket-loaded"></i> RENDIMIENTO GLOBAL ANUAL
                                            <span class="badge badge-pill badge-warning"></span>
                                        </a>
                                    </li>
                                    
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="realizados4" role="tabpanel">
                                        <div class="form-group row">
                                            <div class="col-md-6">
                                                <label class="form-control-label" for="email-input">VEHÍCULOS (*):</label>
                                                <v-select 
                                                    @input="getDatosVehiculo"
                                                    @search="selectVehiculo" 
                                                    label="codigo_comb"
                                                    multiple
                                                    v-model="vehiculos"
                                                    :options="arrayVehiculo"
                                                    placeholder="BUSCAR CÓDIGOS..."
                                                    >
                                                </v-select>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="input-group input-daterange">
                                                    <div class="input-group-addon bg-danger">AÑO</div>
                                                    <input type="number" class="form-control" v-model="anio" min="0" placeholder="AÑO">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <button type="button" @click="generarReporte()" class="btn btn-warning text-white" title="GENERAR GRÁFICOS">
                                                    <i class="fa fa-line-chart"></i>&nbsp;GENERAR GRÁFICO
                                                </button>
                                            </div>
                                        </div>
                                        <div v-show="errorReporte" class="form-group row div-error">
                                            <div class="text-center text-error">
                                                <div v-for="error in errorMostrarMsjReporte" :key="error" v-text="error">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="small" v-if="reporte!=0">
                                            <h4>RENDIMIENTOS POR DESPACHO - (KM / GAL)</h4>
                                            <line-chart :chart-data="datacollection" :height="200"></line-chart>
                                        </div>   
                                    </div>
                                    <div class="tab-pane" id="proyectados4" role="tabpanel">
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="col-md-3 form-control-label" for="email-input">DISTRIBUIDORAS (*):</label>
                                                <v-select 
                                                    @input="getDatosDistribuidora"
                                                    @search="selectDistribuidora" 
                                                    label="nombre"
                                                    multiple
                                                    v-model="distribuidoras"
                                                    :options="arrayDistribuidora"
                                                    placeholder="BUSCAR DISTRIBUIDORAS..."
                                                >
                                                </v-select>
                                            </div>
                                            <div class="col-md-3"></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12">
                                                <label class="col-md-3 form-control-label" for="email-input">MODELOS:</label>
                                                <v-select 
                                                    @input="getDatosModelo"
                                                    @search="selectModelo" 
                                                    label="modelo"
                                                    multiple
                                                    v-model="modelos"
                                                    :options="arrayModelo"
                                                    placeholder="BUSCAR MODELOS..."
                                                >
                                                </v-select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-9">
                                                <label class="col-md-3 form-control-label" for="email-input">VEHÍCULOS:</label>
                                                <v-select 
                                                    @input="getDatosVehiculo2"
                                                    @search="selectVehiculo2" 
                                                    label="codigo_comb"
                                                    multiple
                                                    v-model="vehiculos2"
                                                    :options="arrayVehiculo2"
                                                    placeholder="BUSCAR CÓDIGOS..."
                                                    >
                                                </v-select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-2">
                                                <div class="input-group input-daterange">
                                                    <div class="input-group-addon bg-danger">AÑO</div>
                                                    <input type="number" class="form-control" v-model="anio2" min="0" placeholder="AÑO">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" @click="generarReporte2()" class="btn btn-warning text-white" title="GENERAR GRÁFICOS">
                                                    <i class="fa fa-line-chart"></i>&nbsp;GENERAR GRÁFICO
                                                </button>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" @click="limpiar()" class="btn btn-warning text-white" title="GENERAR GRÁFICOS">
                                                    <i class="fa fa-trash"></i>&nbsp;LIMPIAR
                                                </button>
                                            </div>
                                        </div>
                                        <div v-show="errorReporte2" class="form-group row div-error">
                                            <div class="text-center text-error">
                                                <div v-for="error in errorMostrarMsjReporte2" :key="error" v-text="error">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="small" v-if="reporte2!=0">
                                            <h4>RENDIMIENTOS POR DESPACHO - (KM / GAL)</h4>
                                            <line-chart :chart-data="datacollection2" :height="200"></line-chart>
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
    import LineChart from './LineChart.js';

    import 'vue-select/dist/vue-select.css';

    Vue.component('v-select', vSelect);

    export default {

        name : 'reporte-grafico',

        components: {

            vSelect,
            LineChart 

        },

        data (){

            return {

                id_vehiculo : 0,
                id_vehiculo2 : 0,
                arrayVehiculo : [],
                arrayVehiculo2 : [],
                vehiculos : [],
                vehiculos2 : [],
                vehiculo : {
                    id : 0,
                    codigo_comb : ''
                },
                vehiculo2 : {
                    id : 0,
                    codigo_comb : ''
                },
                reporte : 0,
                reporte2 : 0,
                anio : '',
                anio2 : '',
                datacollection: null,
                datacollection2: null,
                errorReporte : 0,
                errorMostrarMsjReporte : [],
                errorReporte2 : 0,
                errorMostrarMsjReporte2 : [],
                fechas : [],
                rendimientos : [],
                rendimientos2 : [],
                distribuidoras : [],
                arrayDistribuidora : [],
                distribuidora : {
                    id : 0,
                    nombre : ''
                },
                modelos : [],
                arrayModelo : [],
                modelo : {
                    id : 0,
                    nombre : ''
                }

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

            },

            chartOptions() { 
                return {
                    chart: {  type: this.modo},
                    title: {  text: this.title  },
                    series: this.series,
                }
            }

        },

        methods : {

            selectVehiculo(search, loading){

                // Buscar los codigos de placa que coincidan con el campo search

                let me = this;
                loading(true);

                var url = '/vehiculos/selectVehiculo?filtro='+search;
                
                axios.get(url).then(function (response) {
                    
                    let respuesta = response.data;
                    q: search
                    me.arrayVehiculo = respuesta.vehiculos;
                    loading(false);
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            getDatosVehiculo(val1){

                // Asignar los datos del cliente seleccionado a la data

                let me = this;
                me.loading = true;

                if(val1 !== null){
                    me.id_vehiculo = val1.id;
                    me.vehiculo.id = val1.id;
                    me.vehiculo.codigo_comb = val1.codigo_comb;
                }else{
                    me.id_vehiculo = 0;
                    me.vehiculo.id = 0;
                    me.vehiculo.codigo_comb = '';
                }
                
            },

            fillData(){

                let labelsArray = [];
                let dataRend = [];
                
                for (let i = 0; i < this.fechas.length; i++) {
                    labelsArray.push(this.fechas[i] ? this.fechas[i] : '');
                }

                console.log(this.rendimientos);

                for (let i = 0; i < this.rendimientos.length; i++) {
                    let color = this.colorHEX();
                    let objeto = new Object();
                    objeto.label = this.rendimientos[i].codigo_placa
                    objeto.backgroundColor = color;
                    objeto.data = this.rendimientos[i].rendimientos;
                    objeto.lineTension = 0;
                    objeto.fill = false;
                    objeto.borderColor = color;
                    objeto.spanGaps = true;
                    dataRend.push(objeto);
                }

                console.log(labelsArray);

                this.datacollection = {

                    labels: labelsArray,

                    datasets: dataRend

                }

            },

            fillData2(){

                let labelsArray = ['ENERO', 'FEBRERO', 'MARZO', 'ABRIL', 'MAYO', 'JUNIO', 'JULIO', 'AGOSTO', 'SEPTIEMBRE', 'OCTUBRE', 'NOVIEMBRE', 'DICIEMBRE'];
                let dataRend = [];

                console.log(this.rendimientos);

                for (let i = 0; i < this.rendimientos2.length; i++) {
                    let color = this.colorHEX();
                    let objeto = new Object();
                    objeto.label = this.rendimientos2[i].codigo_placa
                    objeto.backgroundColor = color;
                    objeto.data = this.rendimientos2[i].rendimientos;
                    objeto.lineTension = 0;
                    objeto.fill = false;
                    objeto.borderColor = color;
                    objeto.spanGaps = true;
                    dataRend.push(objeto);
                }

                console.log(labelsArray);

                this.datacollection2 = {

                    labels: labelsArray,

                    datasets: dataRend

                }

            },

            generarReporte(){

                if(this.validarReporte()){

                    return;
                    
                }

                let me = this;

                
                axios.post('/reporteria/reporte-grafico', {

                    'vehiculos' : this.vehiculos,
                    'anio' : this.anio

                }).then(function (response) {
                    var respuesta = response.data;
                    
                    me.fechas = respuesta.fechas;
                    me.reporte = 1;
                    me.rendimientos = respuesta.rendimientos;
                    me.fillData();
                    
                    console.log(respuesta);
                })
                .catch(function (error) {
                    
                    console.log(error);

                })
                
            },

            generarReporte2(){

                if(this.validarReporte2()){

                    return;
                    
                }

                let me = this;

                
                axios.post('/reporteria/reporte-grafico2', {

                    'distribuidoras' : this.distribuidoras,
                    'modelos' : this.modelos,
                    'vehiculos' : this.vehiculos2,
                    'anio' : this.anio2

                }).then(function (response) {
                    var respuesta = response.data;
                    
                    me.fechas = respuesta.fechas;
                    me.reporte2 = 1;
                    me.rendimientos2 = respuesta.rendimientos;
                    me.fillData2();
                    
                    console.log(respuesta);
                })
                .catch(function (error) {
                    
                    console.log(error);

                })
                
            },

            validarReporte(){

                this.errorReporte = 0;
                this.errorMostrarMsjReporte = [];

                if(!this.arrayVehiculo.length) this.errorMostrarMsjReporte.push("DEBE SELECCIONAR AL MENOS UN VEHÍCULO.");
                if(!this.anio) this.errorMostrarMsjReporte.push("DEBE INDICAR UN AÑO.");
        
                if(this.errorMostrarMsjReporte.length) this.errorReporte = 1;

                return this.errorReporte;

            },

            validarReporte2(){

                this.errorReporte2 = 0;
                this.errorMostrarMsjReporte2 = [];

                if(!this.distribuidoras.length) this.errorMostrarMsjReporte2.push("DEBE SELECCIONAR AL MENOS UNA DISTRIBUIDORA.");
                if(!this.anio) this.errorMostrarMsjReporte2.push("DEBE INDICAR UN AÑO.");
        
                if(this.errorMostrarMsjReporte2.length) this.errorReporte2 = 1;

                return this.errorReporte2;

            },

            generarLetra(){
                var letras = ["a","b","c","d","e","f","0","1","2","3","4","5","6","7","8","9"];
                var numero = (Math.random()*15).toFixed(0);
                return letras[numero];
            },

            colorHEX(){
                var coolor = "";
                for(var i=0;i<6;i++){
                    coolor = coolor + this.generarLetra() ;
                }
                return "#" + coolor;
            },

            selectDistribuidora(search, loading){

                // Buscar los clientes que coincidan con el campo search

                let me = this;
                loading(true);

                var url = '/distribuidoras/selectDistribuidora?filtro='+search;
                
                axios.get(url).then(function (response) {
                    
                    let respuesta = response.data;
                    q: search
                    me.arrayDistribuidora = respuesta.distribuidoras;
                    loading(false);
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            getDatosDistribuidora(val1){

                // Asignar los datos del cliente seleccionado a la data

                let me = this;
                me.loading = true;

                if(val1 !== null){
                    me.distribuidora.id = val1.id;
                    me.distribuidora.nombre = val1.nombre;
                }else{
                    me.distribuidora.id = 1;
                    me.distribuidora.nombre = '';
                }
                
            },

            selectModelo(search, loading){

                let me = this;
                loading(true);

                let url = '/vehiculos/selectModeloGrafico';
                
                axios.post(url, {
                    filtro : search,
                    distribuidoras : me.distribuidoras
                }).then(function (response) {
                    let respuesta = response.data;
                    q: search
                    me.arrayModelo = respuesta.modelos;
                    loading(false);
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            getDatosModelo(val1){

                // Asignar los datos del cliente seleccionado a la data

                let me = this;
                me.loading = true;
                
                if(val1 !== null){
                    me.modelo.id = val1.id;
                    me.modelo.nombre = val1.nombre;
                }else{
                    me.modelo.id = 1;
                    me.modelo.nombre = '';
                }
                
            },

            selectVehiculo2(search, loading){

                // Buscar los codigos de placa que coincidan con el campo search

                let me = this;
                loading(true);

                var url = '/vehiculos/selectVehiculoGrafico';
                
                axios.post(url, {
                    filtro : search,
                    distribuidoras : me.distribuidoras,
                    modelos : me.modelos
                }).then(function (response) {
                    
                    let respuesta = response.data;
                    q: search
                    me.arrayVehiculo2 = respuesta.vehiculos;
                    loading(false);
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            getDatosVehiculo2(val1){

                // Asignar los datos del cliente seleccionado a la data

                let me = this;
                me.loading = true;

                if(val1 !== null){
                    me.id_vehiculo2 = val1.id;
                    me.vehiculo2.id = val1.id;
                    me.vehiculo2.codigo_comb = val1.codigo_comb;
                }else{
                    me.id_vehiculo2 = 0;
                    me.vehiculo2.id = 0;
                    me.vehiculo2.codigo_comb = '';
                }
                
            },

            fechaActual2(){
                
                let hoy = new Date();

                let dd = hoy.getDate();
                let mm = hoy.getMonth()+1;
                let yyyy = hoy.getFullYear();
        
                this.anio = yyyy;
                this.anio2 = yyyy;

            },

            limpiar(){
                let me = this;
                me.reporte2 = 0;
                me.arrayVehiculo2 = [];
                me.arrayModelo = [];
                me.arrayDistribuidora = [];
                me.vehiculos2 = [];
                me.modelos = [];
                me.distribuidoras = [];
            }

        },

        mounted() {

            //this.fillData();
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

    .small {
        max-width: 1200px;
        max-height: 2500px;
        margin:  50px auto;
    }
</style>

</style>
