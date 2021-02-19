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
                    <i class="fa fa-align-justify"></i><strong>BASE DE DATOS</strong>
                </div>
                <div class="card-body">
                    <div class="loading" v-if="loading2">
                        <div class="form-group row">
                            <div class="col-md-4">
                            </div>
                            <div class="col-md-4">
                                <div class="preloader"></div>
                                GENERANDO REPORTE, ESPERE UN MOMENTO...
                            </div>
                            <div class="col-md-4">
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div class="form-group row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <!--button type="button" @click="exportarPDF()" class="btn btn-warning text-white" title="EXPORTAR DATOS">
                                        <i class="fa fa-file-pdf-o"></i>&nbsp;PDF
                                </button-->
                                <button type="button" @click="exportarExcel()" class="btn btn-warning text-white" data-toggle="modal" data-target="#modalNuevo" title="NUEVO REGISTRO">
                                    <i class="fa fa-file-excel-o"></i>&nbsp;EXCEL
                                </button>
                                <button v-if="rol_id != 1" type="button" @click="abrirModal('rendimiento', 'importar')" class="btn btn-warning text-white" title="IMPORTAR REGISTROS">
                                    <i class="fa fa-file-excel-o"></i>&nbsp;IMPORTAR
                                </button>
                                <button v-if="rol_id != 1" type="button" @click="abrirModal('rendimiento', 'registrar')" class="btn btn-success" data-toggle="modal" data-target="#modalNuevo" title="NUEVO REGISTRO">
                                    <i class="fa fa-plus-circle"></i>&nbsp;NUEVO
                                </button>
                            </div>
                            <div class="col-md-3"></div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select class="form-control col-md-5" v-model="criterio">
                                        <option value="vehiculos.codigo_comb">CODIGO PLACA</option>
                                        <option value="rendimientos.id_data">ID DATA</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup="listarRendimiento(1, buscar, criterio, desde_fecha, hasta_fecha, select_combustible, select_status, criterio2, desde, hasta)" class="form-control" placeholder="TEXTO A BUSCAR">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">DESDE</div>
                                    <input type="date" v-model="desde_fecha" @change="cambiarHasta()" :max="fechaMaxima" class="form-control">
                                    <div class="input-group-addon bg-danger">HASTA</div>
                                    <input type="date" v-model="hasta_fecha" @change="cambiarDesde()" :max="fechaActual" :min="desde_fecha" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">COMBUSTIBLE</div>
                                    <select class="form-control col-md-5" v-model="select_combustible" @change="listarRendimiento(1, buscar, criterio, desde_fecha, hasta_fecha, select_combustible, select_status, criterio2, desde, hasta)">
                                        <option value="">TODOS</option>
                                        <option v-for="nombreCombustible in arrayCombustible" :key="nombreCombustible.id" :value="nombreCombustible.id" v-text="nombreCombustible.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">STATUS</div>
                                    <select class="form-control col-md-5" v-model="select_status" @change="listarRendimiento(1, buscar, criterio, desde_fecha, hasta_fecha, select_combustible, select_status, criterio2, desde, hasta)">
                                        <option value="">TODOS</option>
                                        <option value="OK">OK</option>
                                        <option value="OK - 1">OK - 1</option>
                                        <option value="OK - ILOGICO">OK - ILOGICO</option>
                                        <option value="SIN LECTURA">ERROR</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <div class="input-group input-daterange">
                                    <select class="form-control col-md-5" v-model="criterio2">
                                        <option value="rendimientos.kilometraje">KILOMETRAJE</option>
                                        <option value="rendimientos.cantidad_galones">CANT. GALONES</option>
                                        <option value="rendimientos.valor">VALOR</option>
                                        <option value="rendimientos.recorrido">RECORRIDO</option>
                                        <option value="rendimientos.rendimiento">RENDIMIENTO</option>
                                    </select>                            
                                    <div class="input-group-addon bg-danger">DESDE</div>
                                    <input type="number" v-model="desde" @change="cambiarKmHasta()" :min="minimo_desde" :max="maximo_desde" class="form-control">
                                    <div class="input-group-addon bg-danger">HASTA</div>
                                    <input type="number" v-model="hasta" @change="cambiarKmDesde()" :min="minimo_hasta" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-container2">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr class="bg-danger">
                                                <th class="align-middle" style="text-align: center;"></th>
                                                <th class="align-middle" style="text-align: center;"><div class="sizeOpcion">FECHA</div></th>
                                                <th class="align-middle" style="text-align: center;">CÓDIGO PLACA</th>
                                                <th class="align-middle" style="text-align: center;">ID DATA (No. AUTORIZACIÓN)</th>
                                                <th class="align-middle" style="text-align: center;">COMBUSTIBLE</th>
                                                <th class="align-middle" style="text-align: center;">KILOMETRAJE</th>
                                                <th class="align-middle" style="text-align: center;">CANTIDAD DE GALONES</th>
                                                <th class="align-middle" style="text-align: center;">VALOR</th>
                                                <th class="align-middle" style="text-align: center;">RECORRIDO</th>
                                                <th class="align-middle" style="text-align: center;">RENDIMIENTO</th>
                                                <th class="align-middle" style="text-align: center;">STATUS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="rendimiento in arrayRendimiento" :key="rendimiento.id">
                                                <td class="align-middle" style="text-align: center;">
                                                    <button type="button" @click="abrirModal('rendimiento', 'actualizar', rendimiento)" class="btn btn-warning text-white btn-sm" data-toggle="modal" data-target="#modalNuevo" title="ACTUALIZAR REGISTRO">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                </td>
                                                <td class="align-middle" style="text-align: center;" v-text="rendimiento.fecha"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="rendimiento.codigo_comb"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="rendimiento.id_data"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="rendimiento.nombre_combustible"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="rendimiento.kilometraje"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="rendimiento.cantidad_galones"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="rendimiento.valor"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="rendimiento.recorrido"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="rendimiento.rendimiento"></td>
                                                <template v-if="rendimiento.status == 'OK - 1'">
                                                    <td style="text-align: center;" class="bg-success align-middle" v-text="rendimiento.status"></td>
                                                </template>
                                                <template v-else-if="rendimiento.status == 'OK'">
                                                    <td style="text-align: center;" class="bg-success align-middle" v-text="rendimiento.status"></td>
                                                </template>
                                                <template v-else-if="rendimiento.status == 'OK - ILOGICO'">
                                                    <td style="text-align: center;" class="bg-warning align-middle" v-text="rendimiento.status"></td>
                                                </template>
                                                <template v-else-if="rendimiento.status == 'SIN LECTURA'">
                                                    <td style="text-align: center;" class="bg-danger align-middle" v-text="rendimiento.status"></td>
                                                </template>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <nav>
                            <span class="page-stats">Del {{pagination.from}} al {{pagination.to}} de un total de  {{pagination.total}} registros.</span>
                            <ul class="pagination">
                                <li class="page-item" v-if="pagination.current_page > 1">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio, desde_fecha, hasta_fecha, select_combustible, select_status, criterio2, desde, hasta)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio, desde_fecha, hasta_fecha, select_combustible, select_status, criterio2, desde, hasta)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio, desde_fecha, hasta_fecha, select_combustible, select_status, criterio2, desde, hasta)">Sig</a>
                                </li>
                            </ul>
                        </nav>
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
                        <template v-if="tipoAccion == 1 || tipoAccion == 2">
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div v-if="tipoAccion == 1" class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">CÓDIGO PLACA (*):</label>
                                    <div class="col-md-9">
                                        <v-select 
                                            @input="getDatosVehiculo"
                                            @search="selectVehiculo" 
                                            label="codigo_comb"
                                            v-model="vehiculos"
                                            :options="arrayVehiculo"
                                            placeholder="BUSCAR CÓDIGOS..."
                                        >
                                        </v-select>
                                    </div>
                                </div>
                                <div v-if="tipoAccion == 1" class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">FECHA (*):</label>
                                    <div class="col-md-9">
                                        <input type="date" class="form-control" v-model="fecha" :max="fechaActual" :min="ultimaFecha" placeholder="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">ID DATA (No. AUTORIZACIÓN) (*):</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" v-model="id_data" @blur="validarData(id_data, rendimiento_id)" placeholder="No. AUTORIZACION">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">COMBUSTIBLE (*)</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="id_combustible">
                                            <option value="">SELECCIONE UN TIPO DE COMBUSTIBLE</option>
                                            <option v-for="combustible in arrayCombustible" :key="combustible.id" :value="combustible.id" v-text="combustible.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">KILOMETRAJE (*):</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" v-model="kilometraje" :min="ultimoKilometraje" :max="siguienteKilometraje" placeholder="KILOMETRAJE DEL VEHÍCULO">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">CANTIDAD EN GALONES:</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" v-model="cantidad_galones" min="0" placeholder="CANTIDAD GALONES">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">VALOR:</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" v-model="valor" min="0" placeholder="VALOR EN DINERO">
                                    </div>
                                </div>
                                <div v-show="errorRendimiento" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjRendimiento" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </template>
                        <template v-else>
                            <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">DESCARGAR FORMATO:</label>
                                    <div class="col-md-9">
                                        <button type="button" @click="descargarFormato()" class="btn btn-warning text-white" title="FORMATO INGRESO DATOS">
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
                                <div v-show="errorRendimiento" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjRendimiento" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="loading" v-if="uploading">
                                <div class="form-group row">
                                    <div class="col-md-4">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="preloader"></div>
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
                        <button type="button" @click="registrarRendimiento()" v-if="tipoAccion==1" class="btn btn-warning text-white">GUARDAR</button>
                        <button type="button" @click="actualizarRendimiento()" v-if="tipoAccion==2" class="btn btn-warning text-white">ACTUALIZAR</button>
                        <button type="button" @click="importExcel()" v-if="tipoAccion==3" class="btn btn-warning text-white">IMPORTAR</button>
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

        name : 'base-datos-rendimiento',

        components: {

            vSelect

        },

        data (){

            return {

                rendimiento_id : 0,
                rol_id : 0,
                fecha : '',
                id_vehiculo : 0,
                id_data : '',
                id_combustible : '',
                kilometraje : '',
                cantidad_galones : '',
                valor : '',
                arrayRendimiento: [],
                arrayRendimiento2 : [],
                arrayCombustible : [],
                errorRendimiento : 0,
                errorMostrarMsjRendimiento : [],
                criterio : 'vehiculos.codigo_comb',
                criterio2 : 'rendimientos.kilometraje',
                buscar : '',
                desde : '',
                hasta : '',
                desde_fecha : '',
                hasta_fecha : '',
                select_status : '',
                select_combustible : '',
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                arrayVehiculo : [],
                arrayCombustible : [],
                vehiculo : {
                    id : 0,
                    codigo_comb : ''
                },
                fechaActual : '',
                ultimaFecha : '',
                ultimoKilometraje : '',
                siguienteKilometraje : '',
                import_file : '',
                estadoImport : '',
                arrayErroresImport : [],
                uploading : false,
                loading : false,
                fechaMaxima : '',
                fechaActual : '',
                ultimaFecha : '',
                km_Maxima : '',
                minimo_desde : 0,
                minimo_hasta : 0,
                maximo_desde : '',
                minimo_hasta : '',
                fecha_rendimiento : '',
                alerta : '',
                loading2 : false

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

            listarRendimiento(page, buscar, criterio, desde_fecha, hasta_fecha, select_combustible, select_status, criterio2, desde, hasta){

                let me = this;
                var url = '/rendimientos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&desde_fecha=' + desde_fecha + '&hasta_fecha=' + hasta_fecha + '&select_combustible=' + select_combustible + '&select_status=' + select_status + '&criterio2=' + criterio2 + '&desde=' + desde + '&hasta=' + hasta;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayRendimiento = respuesta.rendimientos.data;
                    me.arrayRendimiento2 = respuesta.rendimientos2;
                    me.arrayCombustible = respuesta.nombreCombustibles;
                    me.rol_id = respuesta.rol;
                    me.pagination = respuesta.pagination;
                    console.log(respuesta);
                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            cambiarPagina(page, buscar, criterio, desde_fecha, hasta_fecha, select_combustible, select_status, criterio2, desde, hasta){
                
                let me = this;

                me.pagination.current_page = page;
                me.listarRendimiento(page, buscar, criterio, desde_fecha, hasta_fecha, select_combustible, select_status, criterio2, desde, hasta);

            },

            selectCombustible(){

                let me = this;
                var url = '/combustibles/selectCombustible';
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCombustible = respuesta.combustibles;
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            registrarRendimiento(){

                if(this.validarRendimiento()){

                    return;
                    
                }

                let me = this;

                axios.post('/rendimientos/registrar', {

                    'fecha': this.fecha,
                    'id_vehiculo' : this.id_vehiculo,
                    'id_data' : this.id_data,
                    'id_combustible' : this.id_combustible,
                    'kilometraje' : this.kilometraje,
                    'cantidad_galones' : this.cantidad_galones,
                    'valor' : this.valor,

                }).then(function (response) {

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'INGRESO DE DATOS COMPLETADO',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    me.listarRendimiento(1, '', 'codigo_placa', '', '', '', '', 'kilometraje', '', '');
                    me.cerrarModal();

                }).catch(function (error) {

                    console.log(error);

                });

            },

            actualizarRendimiento(){

                if(this.validarRendimiento()){

                    return;
                    
                }

                let me = this;

                axios.put('/rendimientos/actualizar', {

                    'rendimiento_id' : this.rendimiento_id,
                    'id_vehiculo' : this.id_vehiculo,
                    'id_data' : this.id_data,
                    'id_combustible' : this.id_combustible,
                    'kilometraje' : this.kilometraje,
                    'cantidad_galones' : this.cantidad_galones,
                    'valor' : this.valor,

                }).then(function (response) {

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'INGRESO DE DATOS COMPLETADO',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    me.listarRendimiento(1, '', 'codigo_placa', '', '', '', '', 'kilometraje', '', '');
                    me.cerrarModal();

                }).catch(function (error) {

                    console.log(error);

                });

            },

            abrirModal(modelo, accion, data = []){

                this.selectCombustible();

                switch (modelo) {

                    case 'rendimiento':
                        
                        {

                            switch (accion) {
                                
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'INGRESAR REGISTRO';
                                        this.tipoAccion = 1;
                                        break;
                                    }

                                case 'actualizar':
                                    {

                                        let me = this;
                                        var url = '/rendimientos/kilometrajeAnterior?id_vehiculo=' + data['id_vehiculo'] + '&rendimiento_id=' + data['id'];
                                        
                                        axios.get(url).then(function (response) {
                                            var respuesta = response.data;
                                            me.ultimoKilometraje = respuesta.kilometraje;
                                            console.log(respuesta);
                                        })
                                        .catch(function (error) {
                                            
                                            console.log(error);

                                        });

                                        var url = '/rendimientos/kilometrajePosterior?id_vehiculo=' + data['id_vehiculo'] + '&rendimiento_id=' + data['id'];
                                        
                                        axios.get(url).then(function (response) {
                                            var respuesta = response.data;
                                            me.siguienteKilometraje = respuesta.kilometraje;
                                            console.log(respuesta);
                                        })
                                        .catch(function (error) {
                                            
                                            console.log(error);

                                        })

                                        this.modal = 1;
                                        this.tituloModal = 'ACTUALIZAR REGISTRO';
                                        this.tipoAccion = 2;
                                        this.rendimiento_id = data['id'];
                                        this.fecha = data['fecha'];
                                        this.id_vehiculo = data['id_vehiculo'];
                                        this.id_data = data['id_data'];
                                        this.id_combustible = data['id_combustible'];
                                        this.kilometraje = data['kilometraje'];
                                        this.cantidad_galones = data['cantidad_galones'];
                                        this.valor = data['valor'];
                                        break;
                                    }

                                case 'importar':
                                    {
                                        let me = this;
                                        var url = '/rendimientos/ultimoRendimiento';
                                        
                                        axios.get(url).then(function (response) {
                                            var respuesta = response.data;
                                            me.fecha_rendimiento = respuesta.fecha;
                                            me.alerta = respuesta.alerta;
                                            console.log(respuesta);
                                            if(me.alerta == 'si'){
                                                Swal.fire({
                                                    icon: 'warning',
                                                    title: 'ADVERTENCIA...',
                                                    html: 'La ultima toma de combustible se registró el <b>' + me.fecha_rendimiento + '</b>. Asegúrese de ingresar registros pendientes entre la fecha mencionada y la fecha actual antes de continuar!',
                                                });

                                        }
                                        })
                                        .catch(function (error) {
                                            
                                            console.log(error);

                                        })
                                        
                                        
                                        

                                        this.modal = 1;
                                        this.tituloModal = 'IMPORTAR REGISTROS';
                                        this.tipoAccion = 3;
                                    }
                            
                            }

                        }
                        
                }
            },

            cerrarModal(){

                this.modal = 0;
                this.tipoAccion = 0;
                this.tituloModal = '';
                this.fecha = '';
                this.id_vehiculo = 0;
                this.vehiculo = {
                    id : 0,
                    codigo_comb : ''
                }
                this.id_data = '';
                this.id_combustible = '';
                this.kilometraje = '';
                this.cantidad_galones = '';
                this.valor = '';
                this.errorRendimiento = 0;
                this.errorMostrarMsjRendimiento = [];
                this.ultimaFecha = '';
                this.ultimoKilometraje = '';
                this.siguienteKilometraje = '';
                this.import_file = '';
                this.$refs.import_file.value = null;
                this.arrayErroresImport = [];
                this.estadoImport = '';
                this.fecha_rendimiento = '';
                this.alerta = '';
                this.loaging = false;
                this.uploading = false;
            },

            validarData(id_data, rendimiento_id){
                
                let me = this;
                me.errorRendimiento = 0;
                me.errorMostrarMsjRendimiento = [];

                if(rendimiento_id == 0){

                    var url = '/rendimientos/unico/' + id_data;

                    axios.get(url).then(function (response) {

                        var respuesta = response.data;

                        if(respuesta=="no") me.errorMostrarMsjRendimiento.push("EL ID DATA (No. AUTORIZACIÓN) " + id_data + " YA EXISTE.");

                        if(me.errorMostrarMsjRendimiento.length){
                            
                            me.id_data = '';
                            me.errorRendimiento = 1;
                            
                        } 

                        return me.errorRendimiento;

                    }).catch(function (error) {

                        console.log(error);

                    });
                }else{

                    var url = '/rendimiento/unico/' + id_data + '/' + rendimiento_id;

                    axios.get(url).then(function (response) {

                        var respuesta = response.data;

                        if(respuesta=="no") me.errorMostrarMsjRendimiento.push("EL ID DATA (No. AUTORIZACIÓN) " + id_data + " YA EXISTE.");

                        if(me.errorMostrarMsjRendimiento.length){
                            
                            me.id_data = '';
                            me.errorRendimiento = 1;
                            
                        } 

                        return me.errorRendimiento;

                    }).catch(function (error) {

                        console.log(error);

                    });

                }

            },

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
                    me.getFechaVehiculo(val1.id);
                }else{
                    me.id_vehiculo = 0;
                    me.vehiculo.id = 0;
                    me.vehiculo.codigo_comb = '';
                    me.ultimaFecha = '';
                    me.fecha = '';
                    me.ultimoKilometraje = '';
                    me.kilometraje = ''
                }
                
            },

            getFechaVehiculo(id_vehiculo){

                let me = this;

                console.log(id_vehiculo);

                var url = '/rendimientos/ultimaFechaKilometrajeVehiculo?id_vehiculo='+id_vehiculo;
                
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

            validarRendimiento(){

                this.errorRendimiento = 0;
                this.errorMostrarMsjRendimiento = [];

                if(!this.fecha) this.errorMostrarMsjRendimiento.push("DEBE SELECCIONAR UNA FECHA.");  
                if(!this.id_vehiculo) this.errorMostrarMsjRendimiento.push("DEBE SELECCIONAR EL CODIGO DE UN VEHÍCULO.");
                if(!this.id_data) this.errorMostrarMsjRendimiento.push("EL ID DATA (No. AUTORIZACIÓN) NO PUEDE IR VACÍO.");
                if(!this.id_combustible || this.id_combustible == "0") this.errorMostrarMsjRendimiento.push("DEBE SELECCIONAR UN TIPO DE COMBUSTIBLE.");
                if(!this.kilometraje) this.errorMostrarMsjRendimiento.push("EL KILOMETRAJE NO PUEDE IR VACÍO.");
                if(!this.cantidad_galones || this.id_ejecutor == "0") this.errorMostrarMsjRendimiento.push("LA CANTIDAD DE GALONES NO PUEDE IR VACÍA.");
                if(!this.valor) this.errorMostrarMsjRendimiento.push("EL VALOR NO PUEDE IR VACÍO.");
                if(parseInt(this.kilometraje) < this.ultimoKilometraje && this.tipoAccion == 2){ this.errorMostrarMsjRendimiento.push("EL KILOMETRAJE DEBE SER MAYOR AL ANTERIOR REGISTRADO PARA ESTE VEHÍCULO."); this.kilometraje = '';}
                if(parseInt(this.kilometraje) > this.siguienteKilometraje && this.tipoAccion == 2 && this.siguienteKilometraje != ''){ this.errorMostrarMsjRendimiento.push("EL KILOMETRAJE DEBE SER MENOR AL POSTERIOR REGISTRADO PARA ESTE VEHÍCULO."); this.kilometraje = '';}
                if(this.errorMostrarMsjRendimiento.length) this.errorRendimiento = 1;

                return this.errorRendimiento;

            },

            importExcel(){
                
                let me = this;

                if(this.validarImportExcel()){

                    return;
                    
                }

                let formData = new FormData();
                formData.append('import_file', me.import_file);

                me.uploading = true;

                axios.post('/rendimientos/importar', formData, {

                    headers: { 'content-type': 'multipart/form-data' }
                
                }).then(function (response) {

                    console.log(response.data);
                    me.uploading = false;

                    var respuesta = response.data;
                    me.arrayErroresImport = respuesta.errores;
                    me.estadoImport = respuesta.estado;
                    
                    if(me.estadoImport === 'sin errores'){

                        me.$refs.import_file.value = null;
                        
                        me.listarRendimiento(1, '', 'codigo_placa', '', '', '', '', 'kilometraje', '', '');
                        me.cerrarModal();
                        
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'INGRESO DE DATOS COMPLETADO!',
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

                this.errorRendimiento = 0;
                this.errorMostrarMsjRendimiento = [];

                if(!this.import_file) this.errorMostrarMsjRendimiento.push("DEBE SELECCIONAR UN ARCHIVO PARA IMPORTAR DATOS.");  
                if(this.errorMostrarMsjRendimiento.length) this.errorRendimiento = 1;

                return this.errorRendimiento;

            },

            descargarFormato(){
                
                let me = this;

                axios.get('/rendimientos/descargarFormato', {

                    responseType: "blob"

                }).then((response) => {
                    
                    console.log(response.data);
                    FileSaver.saveAs(response.data, 'formato-ingreso-datos.xlsx');
                    
                }).catch(function (error) {

                    console.log(error);

                });

            },

            exportarExcel(){

                let me = this;

                axios.post('/rendimientos/exportar-excel', {

                    'rendimientos': me.arrayRendimiento2

                }).then(function (response) {

                    axios.get('/rendimientos/rendimiento-descargar-excel', {

                        responseType: "blob"

                    }).then((response) => {
                        
                        console.log(response.data);
                        FileSaver.saveAs(response.data, 'ingreso-datos.xlsx');
                        
                    });

                    me.listarRendimiento(1, me.buscar, me.criterio, '', '', '', '', '', '', '');

                }).catch(function (error) {

                    console.log(error);
                    me.listarRendimiento(1, me.buscar, me.criterio, '', '', '', '', '', '', '');

                });

            },

            cambiarDesde(){

                if(this.desde_fecha == '' && this.hasta_fecha != ''){
                    this.desde_fecha = this.hasta_fecha;
                    this.fechaMaxima = this.hasta_fecha;
                }else if(this.hasta_fecha == ''){
                    this.desde_fecha = '';
                    this.fechaActual2();
                }else{
                    this.fechaMaxima = this.hasta_fecha;
                }

                this.listarRendimiento(1, this.buscar, this.criterio, this.desde_fecha, this.hasta_fecha, this.select_combustible, this.select_status, this.criterio2, this.desde, this.hasta);
                
            },

            cambiarHasta(){

                if(this.hasta_fecha == '' && this.desde_fecha != ''){

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
            
                    this.hasta_fecha = yyyy + '-' + mm + '-' + dd;

                }else if(this.desde_fecha == ''){
                    this.hasta_fecha = '';
                    this.fechaActual2();
                }

                this.listarRendimiento(1, this.buscar, this.criterio, this.desde_fecha, this.hasta_fecha, this.select_combustible, this.select_status, this.criterio2, this.desde, this.hasta);
                
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
                this.fechaMaxima = yyyy + '-' + mm + '-' + dd;

            },

            cambiarKmDesde(){

                if(this.desde == '' && this.hasta != ''){
                    this.desde = this.hasta;
                    this.maximo_desde = this.hasta;
                }else if(this.hasta == ''){
                    this.desde = '';
                }else{
                    this.maximo_desde = this.hasta;
                }

                this.listarRendimiento(1, this.buscar, this.criterio, this.desde_fecha, this.hasta_fecha, this.select_combustible, this.select_status, this.criterio2, this.desde, this.hasta);
                
            },

            cambiarKmHasta(){

                if(this.hasta == '' && this.desde != ''){

                    this.hasta = this.desde;
                    this.minimo_hasta = this.desde;
                    this.maximo_desde = this.hasta;

                }else if(this.desde == ''){
                    this.hasta = '';
                }else{
                    this.maximo_desde = this.hasta;
                    this.minimo_hasta = this.desde;
                }

                this.listarRendimiento(1, this.buscar, this.criterio, this.desde_fecha, this.hasta_fecha, this.select_combustible, this.select_status, this.criterio2, this.desde, this.hasta);             
            },

            exportarPDF(){
                
                let me = this;
                
                me.loading2 = true;

                axios.post('/reporteria/ingreso-datos', {

                    'data' : me.arrayRendimiento2

                }).then(function (response) {

                    me.loading2 = false;

                    axios.get('/reporteria/ingreso-datos-descargar', {

                        responseType: "blob"

                    }).then((response) => {
                        
                        console.log(response.data);
                        FileSaver.saveAs(response.data, 'ingreso-datos.pdf');
                        
                    });

                    console.response(response);

                }).catch(function (error) {

                    console.log(error);

                });

            }

        },

        mounted() {

            this.listarRendimiento(1, this.buscar, this.criterio, this.desde_fecha, this.hasta_fecha, this.select_combustible, this.select_status, this.criterio2, this.desde, this.hasta);

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
        overflow-y: auto;

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

    .table-container2{
        max-width: 100%;
        overflow-x: scroll;
    }

</style>
