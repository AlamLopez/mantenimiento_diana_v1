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
                    <i class="fa fa-align-justify"></i> <strong>SEMÁFORO</strong>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-5">
                            <button v-if="rol_id != 1" type="button" @click="abrirModal('mantenimiento', 'registrar')" class="btn btn-success" title="IMPORTAR REGISTROS">
                                <i class="fa fa-plus-circle"></i>&nbsp;ULTO MANTO
                            </button>
                            <button type="button" @click="actualizarSemaforo()" class="btn btn-warning text-white" data-toggle="modal" data-target="#modalNuevo" title="NUEVO REGISTRO">
                                <i class="fa fa-refresh"></i>&nbsp;ACTUALIZAR
                            </button>
                            <button v-if="rol_id != 1" type="button" @click="abrirModal('mantenimiento', 'importar')" class="btn btn-warning text-white" data-toggle="modal" data-target="#modalNuevo" title="IMPORTAR MANTENIMIENTOS">
                                <i class="fa fa-file-excel-o"></i>&nbsp;IMPORTAR
                            </button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-addon bg-danger">PLACA</div>
                                <input type="text" v-model="buscar" @keyup="listarSemaforo(1, buscar, criterio, diferencia_kms_filtro, diferencia_dias_filtro)" class="form-control" placeholder="TEXTO A BUSCAR">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-daterange">
                                <div class="input-group-addon bg-danger">DIFERENCIA KMS</div>
                                <select class="form-control col-md-5" v-model="diferencia_kms_filtro" @change="listarSemaforo(1, buscar, criterio, diferencia_kms_filtro, diferencia_dias_filtro)">
                                    <option value="">TODOS</option>
                                    <option value="BLANCO">BLANCO</option>
                                    <option value="VERDE" class="bg-success">VERDE</option>
                                    <option value="AMARILLO" class="bg-warning">AMARILLO</option>
                                    <option value="ROJO" class="bg-danger">ROJO</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group input-daterange">
                                <div class="input-group-addon bg-danger">DIFERENCIA_DIAS</div>
                                <select class="form-control col-md-5" v-model="diferencia_dias_filtro" @change="listarSemaforo(1, buscar, criterio, diferencia_kms_filtro, diferencia_dias_filtro)">
                                    <option value="">TODOS</option>
                                    <option value="BLANCO">BLANCO</option>
                                    <option value="VERDE" class="bg-success">VERDE</option>
                                    <option value="AMARILLO" class="bg-warning">AMARILLO</option>
                                    <option value="ROJO" class="bg-danger">ROJO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                        <table class="table table-responsive table-bordered table-sm">
                            <thead>
                                <tr class="bg-danger">
                                    <th class="align-middle"></th>
                                    <th class="align-middle"></th>
                                    <th colspan="3" class="align-middle" style="text-align: center;">ÚLTIMO MANTENIMIENTO</th>
                                    <th colspan="2" class="align-middle" style="text-align: center;">ÚLTIMO KILOMETRAJE</th>
                                    <th colspan="5" class="align-middle" style="text-align: center;">PROYECCIÓN MANTENIMIENTO</th>
                                </tr>
                                <tr class="bg-danger">
                                    <th style="text-align: center;" class="align-middle">OPCIONES</th>
                                    <th style="text-align: center;" class="align-middle">PLACA</th>
                                    <th style="text-align: center;" class="align-middle"><div class="sizeOpcion">FECHA</div></th>
                                    <th style="text-align: center;" class="align-middle">KM</th>
                                    <th style="text-align: center;" class="align-middle">RUTINA</th>
                                    <th style="text-align: center;" class="align-middle"><div class="sizeOpcion">FECHA</div></th>
                                    <th style="text-align: center;" class="align-middle">KM</th>
                                    <th style="text-align: center;" class="align-middle">DIFERENCIA KMS PROX REV</th>
                                    <th style="text-align: center;" class="align-middle">FECHA ESTIMADA PROX MANT</th>
                                    <th style="text-align: center;" class="align-middle">DIFERENCIA DIAS PROX MANT</th>
                                    <th style="text-align: center;" class="align-middle">FECHA MANT TIEMPO</th>
                                    <th style="text-align: center;" class="align-middle">PROX RUTINA</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="semaforo in arraySemaforo" :key="semaforo.id">
                                    <td style="text-align: center;" class="align-middle"> 
                                        <button type="button" @click="abrirModal('mantenimiento', 'leer', semaforo)" class="btn btn-info text-white btn-sm" data-toggle="modal" data-target="#modalNuevo" title="INFORMACIÓN VEHÍCULO">
                                            <i class="fa fa-eye"></i>
                                        </button> &nbsp;
                                    </td>
                                    <td style="text-align: center;" class="align-middle" v-text="semaforo.vehiculo.placa"></td>
                                    <template v-if="semaforo.mantenimiento == null">
                                        <td style="text-align: center;" class="align-middle"></td>
                                        <td style="text-align: center;" class="align-middle"></td>
                                        <td style="text-align: center;" class="align-middle"></td>
                                    </template>
                                    <template v-else>
                                        <td style="text-align: center;" class="align-middle" v-text="semaforo.mantenimiento.fecha"></td>
                                        <td style="text-align: center;" class="align-middle" v-text="semaforo.mantenimiento.kilometraje"></td>
                                        <td style="text-align: center;" class="align-middle" v-text="semaforo.mantenimiento.nombre_rutina_anterior"></td>
                                    </template>
                                    <td style="text-align: center;" class="align-middle" v-text="semaforo.vehiculo.ulto_km"></td>
                                    <td style="text-align: center;" class="align-middle" v-text="semaforo.vehiculo.km"></td>
                                    <template v-if="semaforo.diferencia_kms_color == 'VERDE'">
                                        <td style="text-align: center;" class="align-middle bg-success" v-text="semaforo.diferencia_kms"></td>
                                    </template>
                                    <template v-else-if="semaforo.diferencia_kms_color == 'AMARILLO'">
                                        <td style="text-align: center;" class="align-middle bg-warning" v-text="semaforo.diferencia_kms"></td>
                                    </template>
                                    <template v-else-if="semaforo.diferencia_kms_color == 'ROJO'">
                                        <td style="text-align: center;" class="align-middle bg-danger" v-text="semaforo.diferencia_kms"></td>
                                    </template>
                                    <template v-else>
                                        <td style="text-align: center;" class="align-middle" v-text="semaforo.diferencia_kms"></td>
                                    </template>
                                    <td style="text-align: center;" class="align-middle" v-text="semaforo.fecha_prox_manto_kms"></td>
                                    <template v-if="semaforo.diferencia_dias_color == 'VERDE'">
                                        <td style="text-align: center;" class="align-middle bg-success" v-text="semaforo.diferencia_dias"></td>
                                    </template>
                                    <template v-else-if="semaforo.diferencia_dias_color == 'AMARILLO'">
                                        <td style="text-align: center;" class="align-middle bg-warning" v-text="semaforo.diferencia_dias"></td>
                                    </template>
                                    <template v-else-if="semaforo.diferencia_dias_color == 'ROJO'">
                                        <td style="text-align: center;" class="align-middle bg-danger" v-text="semaforo.diferencia_dias"></td>
                                    </template>
                                    <template v-else>
                                        <td style="text-align: center;" class="align-middle" v-text="semaforo.diferencia_dias"></td>
                                    </template>
                                    <td style="text-align: center;" class="align-middle" v-text="semaforo.fecha_prox_manto_tiempo"></td>
                                    <template v-if="semaforo.mantenimiento == null">
                                        <td style="text-align: center;" class="align-middle"></td>
                                    </template>
                                    <template v-else>
                                        <td style="text-align: center;" class="align-middle" v-text="semaforo.mantenimiento.nombre_rutina_nueva"></td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                    <nav>
                        <span class="page-stats">Del {{pagination.from}} al {{pagination.to}} de un total de  {{pagination.total}} registros.</span>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio, diferencia_kms_filtro, diferencia_dias_filtro)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio, diferencia_kms_filtro, diferencia_dias_filtro)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio, diferencia_kms_filtro, diferencia_dias_filtro)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <!--Inicio del modal agregar/actualizar-->
        <div class="modal fade" tabindex="-1" :class="{'mostrar' : modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-warning" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="tituloModal"></h4>
                        <button type="button" @click="cerrarModal()" class="close" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <template v-if="tipoAccion==1">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">PLACA:</label>
                                    <div class="col-md-9">
                                        <v-select 
                                            @input="getDatosVehiculo"
                                            @search="selectVehiculo" 
                                            label="placa"
                                            v-model="vehiculo.placa"
                                            :options="arrayVehiculo"
                                            placeholder="BUSCAR PLACAS..."
                                        >
                                        </v-select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">FECHA:</label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control" v-model="fecha" :max="fechaActual" :min="ultimaFecha" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">KILOMETRAJE:</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" v-model="kilometraje" min="0" placeholder="KILOMETRAJE">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">RUTINA REALIZADA:</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="id_rutina_anterior" @change="rutinaSiguiente()">
                                            <option value="0">SELECCIONE UNA RUTINA</option>
                                            <option v-for="rutina in arrayRutina" :key="rutina.id" :value="rutina.id" v-text="rutina.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">RUTINA SIGUIENTE:</label>
                                    <label class="col-md-9 form-control-label" for="text-input" v-text="nombre_rutina_nueva"></label>
                                </div> 
                                <div v-show="errorMantenimiento" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjMantenimiento" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </template>
                        <template v-else-if="tipoAccion == 2">
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label" for="text-input">NUMERO VEHICULO:</label>
                                <div class="col-md-9">
                                    <label class="form-control-label" v-text="numero_vehiculo"></label>
                                </div>
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
                        </template>
                        <template v-else>
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">DESCARGAR FORMATO:</label>
                                    <div class="col-md-9">
                                        <button type="button" @click="descargarFormato()" class="btn btn-warning text-white" title="FORMATO VEHÍCULOS">
                                            <i class="fa fa-file-excel-o"></i>&nbsp;DESCARGAR
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">DATOS PARA IMPORTAR:</label>
                                    <div class="col-md-9">
                                        <input  
                                            type="file" 
                                            class="form-control" 
                                            id="input-file-import" 
                                            name="file_import" 
                                            ref="import_file"
                                            accept=".xlsx"
                                            @change="onFileChange"
                                        >
                                    </div>
                                </div>
                                <div v-show="errorMantenimiento" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjMantenimiento" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="loading" v-if="uploading">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="preuploader"></div>
                                        IMPORTANDO DATOS, ESPERE UN MOMENTO...
                                    </div>
                                    <div class="col-md-2">
                                    </div>
                                </div>
                            </div>
                        </template>
                        <template v-if="tipoAccion==3 && estadoImport=='con errores'">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm">
                                    <thead>
                                        <tr class="bg-danger">
                                            <th class="align-middle" style="text-align: center;">FILA</th>
                                            <th class="align-middle" style="text-align: center;">CAMPO</th>
                                            <th class="align-middle" style="text-align: center;">DESCRIPCION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="errores in arrayErroresImport" :key="errores.id">
                                            <td class="align-middle" style="text-align: center;" v-text="errores.fila"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="errores.atributo"></td>
                                            <td class="align-middle" style="text-align: center;" v-text="errores.descripcion[0]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </template>
                    </div>
                    <div class="modal-footer">
                        <button type="button" @click="cerrarModal()" class="btn btn-danger">CERRAR</button>
                        <button type="button" v-if="tipoAccion==1" @click="registrarMantenimiento()" class="btn btn-warning text-white">GUARDAR</button>
                        <button type="button" v-if="tipoAccion==3" @click="importExcel()" class="btn btn-warning text-white">IMPORTAR</button>
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

        name : 'semaforo-mantenimiento',

        components: {

            vSelect

        },

        data (){

            return {

                mantenimiento_id : 0,
                rol_id : 0,
                id_vehiculo : 0,
                numero_vehiculo : '',
                marca : '',
                modelo : '',
                anio : '',
                conductor : '',
                ruta : '',
                fecha : '',
                kilometraje : '',
                id_rutina_anterior : 0,
                nombre_rutina_anterior : '',
                id_rutina_nueva : 0,
                nombre_rutina_nueva : '',
                arrayVehiculo : [],
                arrayRutina : [],
                arraySemaforo : [],
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                errorMantenimiento : 0,
                errorMostrarMsjMantenimiento : [],
                kilometraje_maximo : '',
                vehiculo : {
                    id : 0,
                    placa : ''
                },
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                buscar : '',
                criterio : '',
                diferencia_dias_filtro : '',
                diferencia_kms_filtro : '',
                uploading : false,
                import_file : '',
                arrayErroresImport : [],
                estadoImport : '',
                fechaMaxima : '',
                fechaActual : '',
                km_Maxima : '',
                ultimaFecha : '',
                alerta : '',
                fecha_rendimiento : ''

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

            listarSemaforo(page, buscar, criterio, diferencia_kms_filtro, diferencia_dias_filtro){

                let me = this;
                var url = '/semaforos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&diferencia_kms_filtro=' + diferencia_kms_filtro + '&diferencia_dias_filtro=' + diferencia_dias_filtro;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arraySemaforo = respuesta.semaforos.data;
                    me.rol_id = respuesta.rol;
                    me.pagination = respuesta.pagination;
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            cambiarPagina(page, buscar, criterio){
                
                let me = this;

                me.pagination.current_page = page;
                //me.listarSemaforo(page, buscar, criterio, diferencia_kms_filtro, diferencia_dias_filtro);
                me.actualizarSemaforo();

            },

            selectRutina(){

                let me = this;
                var url = '/rutinas/selectRutina';
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayRutina = respuesta.rutinas;
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            registrarMantenimiento(){

                let me = this;

                if(this.validarMantenimiento()){

                    return;
                    
                }

                axios.post('/mantenimientos/registrar', {

                    'id_vehiculo': this.id_vehiculo,
                    'fecha' : this.fecha,
                    'kilometraje' : this.kilometraje,
                    'nombre_rutina_anterior' : this.nombre_rutina_anterior,
                    'nombre_rutina_nueva' : this.nombre_rutina_nueva,

                }).then(function (response) {

                    me.cerrarModal();
                    me.actualizarSemaforo();

                }).catch(function (error) {

                    console.log(error);

                });

            },

            selectVehiculo(search, loading){

                // Buscar los codigos de placa que coincidan con el campo search

                let me = this;
                loading(true);

                var url = '/vehiculos/selectVehiculoPlaca?filtro='+search;
                
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
                    me.vehiculo.placa = val1.placa;
                    
                    var url = '/mantenimientos/selectMantenimientoPlaca?placa='+me.vehiculo.placa;
                
                    axios.get(url).then(function (response) {
                        
                        let respuesta = response.data;
                        me.id_rutina_anterior = respuesta.rutina;
                        me.kilometraje_maximo = respuesta.kilometraje;
                        me.getFechaVehiculo(val1.id);
                        me.rutinaSiguiente();
                        console.log(response);

                    })
                    .catch(function (error) {
                        
                        console.log(error);

                    })

                }else{
                    me.id_vehiculo = 0;
                    me.vehiculo.id = 0;
                    me.vehiculo.placa = '';
                }

                
                
            },

            getFechaVehiculo(id_vehiculo){

                let me = this;

                console.log(id_vehiculo);

                var url = '/mantenimientos/ultimaFechaKilometrajeVehiculo?id_vehiculo='+id_vehiculo;
                
                axios.get(url).then(function (response) {
                    
                    let respuesta = response.data;
    
                    me.fecha = '';
                    me.kilometraje = ''
                    me.ultimaFecha = respuesta.fecha;
                    me.ultimoKilometraje = respuesta.kilometraje;

                })
                .catch(function (error) {
                    
                    console.log(error);

                })
            },

            abrirModal(modelo, accion, data = []){

                this.selectRutina();

                switch (modelo) {

                    case 'mantenimiento':
                        
                        {

                            switch (accion) {
                                
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'INGRESO DE ÚLTIMO MANTENIMIENTO';
                                        this.tipoAccion = 1;
                                        break;
                                    }

                                case 'leer':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'INFORMACIÓN DEL VEHÍCULO';
                                        this.numero_vehiculo = data['vehiculo']['numero_vehiculo'];
                                        this.marca = data['vehiculo']['marca'];
                                        this.modelo = data['vehiculo']['modelo'];
                                        this.anio = data['vehiculo']['anio'];
                                        this.conductor = data['vehiculo']['conductor'];
                                        this.ruta = data['vehiculo']['ruta'];
                                        this.tipoAccion = 2;
                                        break;
                                    }

                                case 'importar':
                                    {
                                        let me = this;
                                        var url = '/mantenimientos/ultimoMantenimiento';
                                        
                                        axios.get(url).then(function (response) {
                                            var respuesta = response.data;
                                            me.fecha_mantenimiento = respuesta.fecha;
                                            me.alerta = respuesta.alerta;
                                            console.log(respuesta);
                                            if(me.alerta == 'si'){
                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: 'ADVERTENCIA...',
                                                    html: 'El último mantenimiento se registró el <b>' + me.fecha_mantenimiento + '</b>. Asegúrese de ingresar registros pendientes entre la fecha mencionada y la fecha actual antes de continuar!',
                                                });

                                        }
                                        })
                                        .catch(function (error) {
                                            
                                            console.log(error);

                                        })

                                        this.modal = 1;
                                        this.tituloModal = 'IMPORTAR MANTENIMIENTOS';
                                        this.tipoAccion = 3;
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
                this.numero_vehiculo = '';
                this.marca = '';
                this.modelo = '';
                this.anio = '';
                this.conductor = '';
                this.ruta = '';
                this.kilometraje_maximo = '',
                this.id_vehiculo = 0;
                this.mantenimiento_id = '';
                this.vehiculo = {
                    id : 0,
                    placa : ''
                };
                this.fecha = '';
                this.kilometraje = '';
                this.id_rutina_anterior = 0;
                this.id_rutina_nueva = 0;
                this.nombre_rutina_nueva = '';
                this.arrayVehiculo = [];
                this.modal = 0;
                this.tituloModal = '';
                this.tipoAccion = 0;
                this.errorMantenimiento = 0;
                this.errorMostrarMsjMantenimiento = [];
                this.uploading = false;
                this.import_file = '';
                this.$refs.import_file.value = null;
                this.estadoImport = '';
                this.arrayErroresImport = [];
                this.ultimaFecha = '';
                this.fechaMaxima = '';
                this.ultimoKilometraje = '';
                
            },

            rutinaSiguiente(){

                let me = this;

                if(me.id_rutina_anterior < 8){
                    me.id_rutina_nueva = me.id_rutina_anterior + 1;
                }else{
                    me.id_rutina_nueva = 1;
                }

                if(me.id_rutina_anterior == 0){
                    me.nombre_rutina_nueva = '';
                    me.nombre_rutina_anterior = '';
                    me.id_rutina_nueva = 0;
                    me.id_rutina_anterior = 0;
                }else{
                    me.nombre_rutina_anterior = me.arrayRutina[me.id_rutina_anterior - 1].nombre;
                    me.nombre_rutina_nueva = me.arrayRutina[me.id_rutina_nueva - 1].nombre;
                }

            },

            actualizarSemaforo(){

                let me = this;

                var url = '/semaforos/actualizar';
                
                axios.get(url).then(function (response) {
    
                    console.log(response);
                    me.listarSemaforo(me.pagination.current_page, me.buscar, me.criterio, me.diferencia_kms_filtro, me.diferencia_dias_filtro);

                })
                .catch(function (error) {
                    
                    console.log(error);

                });

            },

            validarMantenimiento(){

                this.errorMantenimiento = 0;
                this.errorMostrarMsjMantenimiento = [];

                if(!this.id_vehiculo) this.errorMostrarMsjMantenimiento.push("DEBE SELECCIONAR LA PLACA DE UN VEHÍCULO.");
                if(!this.fecha) this.errorMostrarMsjMantenimiento.push("DEBE SELECCIONAR UNA FECHA.");  
                if(!this.kilometraje) this.errorMostrarMsjMantenimiento.push("EL KILOMETRAJE NO PUEDE IR VACÍO.");
                if(this.kilometraje < this.kilometraje_maximo) this.errorMostrarMsjMantenimiento.push('EL KILOMETRAJE DEBE SER MAYOR AL REGISTRADO EN EL ULTIMO MANTENIMIENTO');
                if(!this.id_rutina_anterior) this.errorMostrarMsjMantenimiento.push("DEBE SELECCIONAR LA RUTINA A APLICAR.");
                if(this.errorMostrarMsjMantenimiento.length) this.errorMantenimiento = 1;

                return this.errorMantenimiento;

            },

            descargarFormato(){
                
                let me = this;

                axios.get('/mantenimientos/descargarFormato', {

                    responseType: "blob"

                }).then((response) => {
                    
                    console.log(response.data);
                    FileSaver.saveAs(response.data, 'formato-ingreso-mtto.xlsx');
                    
                }).catch(function (error) {

                    console.log(error);

                });

            },

            importExcel(){
                
                let me = this;

                if(this.validarImportExcel()){

                    return;
                    
                }

                let formData = new FormData();
                formData.append('import_file', me.import_file);

                me.uploading = true;

                axios.post('/mantenimientos/importar', formData, {

                    headers: { 'content-type': 'multipart/form-data' }
                
                }).then(function (response) {

                    console.log(response.data);
                    me.uploading = false;

                    var respuesta = response.data;
                    me.arrayErroresImport = respuesta.errores;
                    me.estadoImport = respuesta.estado;
                    
                    if(me.estadoImport === 'sin errores'){

                        me.actualizarSemaforo();
                        me.cerrarModal();

                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'MANTENIMIENTOS IMPORTADOS CON ÉXITO!',
                            showConfirmButton: false,
                            timer: 1500
                        });

                    }else if(me.estadoImport === 'con errores'){

                        me.import_file = '';
                        me.$refs.import_file.value = null;

                        Swal.fire({
                            position: 'top-end',
                            icon: 'warning',
                            title: 'SE DETECTARON ERRORES DURANTE LA IMPORTACIÓN!',
                            showConfirmButton: false,
                            timer: 2000
                        });

                    }
                    

                }).catch(function (error) {

                    console.log(error);
                    me.uploading = false;

                    me.import_file = '';
                    me.$refs.import_file.value = null;

                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'OCURRIÓ UN ERROR EN LA IMPORTACIÓN!',
                        showConfirmButton: false,
                        timer: 1500
                    });

                });
            },

            onFileChange(e) {
                this.arrayErroresImport = [];
                this.estadoImport = '';
                this.import_file = e.target.files[0];
            },

            validarImportExcel(){

                this.errorMantenimiento = 0;
                this.errorMostrarMsjMantenimiento = [];

                if(!this.import_file) this.errorMostrarMsjMantenimiento.push("DEBE SELECCIONAR UN ARCHIVO PARA IMPORTAR DATOS.");  
                if(this.errorMostrarMsjMantenimiento.length) this.errorMantenimiento = 1;

                return this.errorMantenimiento;

            },

            fechaActual2(){
                
                let hoy = new Date();

                let dd = hoy.getDate();
                let mm = hoy.getMonth()+1;
                let yyyy = hoy.getFullYear();

                if(dd < 10){
                    dd = '0'+dd;
                }

                if(mm < 10){
                    mm = '0'+mm;
                }
        
                this.fechaActual = yyyy + '-' + mm + '-' + dd;

            },

        },

        mounted() {

            this.actualizarSemaforo();

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

    .sizeOpcion{
        width: 100px;
    }

    .preloader {
        width: 300px;
        height: 300px;
        border: 10px solid red;
        border-top: 10px solid yellow;
        border-radius: 50%;
        animation-name: girar;
        animation-duration: 2s;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
    }

    .preuploader {
        width: 300px;
        height: 300px;
        border: 10px solid red;
        border-top: 10px solid yellow;
        border-radius: 50%;
        animation-name: girar;
        animation-duration: 2s;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
    }

    @keyframes girar {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    

</style>
