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
                    <i class="fa fa-align-justify"></i><strong>INVENTARIO DE FLOTA</strong>
                </div>
                <div class="card-body">
                    <div class="loading" v-if="loading">
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
                            <div class="col-md-6">
                                <strong>NOTA: </strong> SOLO SE MUESTRA LOS VEHÍCULOS CUYOS TALLERES SON VISIBLES PARA ESTE USUARIO.
                            </div>
                            <div class="col-md-6">
                                <!--button type="button" @click="exportarPDF()" class="btn btn-warning text-white" title="EXPORTAR VEHÍCULOS">
                                    <i class="fa fa-file-pdf-o"></i>&nbsp;PDF
                                </button-->
                                <button type="button" @click="exportarExcel()" class="btn btn-warning text-white" data-toggle="modal" data-target="#modalNuevo" title="NUEVO REGISTRO">
                                    <i class="fa fa-file-excel-o"></i>&nbsp;EXCEL
                                </button>
                                <button v-if="rol_id != 1" type="button" @click="abrirModal('vehiculo', 'importar')" class="btn btn-warning text-white" data-toggle="modal" data-target="#modalNuevo" title="IMPORTAR VEHÍCULOS">
                                    <i class="fa fa-file-excel-o"></i>&nbsp;IMPORTAR
                                </button>
                                <button v-if="rol_id != 1" type="button" @click="abrirModal('vehiculo', 'registrar')" class="btn btn-success" data-toggle="modal" data-target="#modalNuevo" title="NUEVO VEHÍCULO">
                                    <i class="fa fa-plus-circle"></i>&nbsp;NUEVO
                                </button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group">
                                    <select class="form-control col-md-5" v-model="criterio">
                                        <option value="codigo_comb">CODIGO_COMB.</option>
                                        <option value="placa">PLACA</option>
                                        <option value="numero_vehiculo">NUMERO VEHICULO</option>
                                        <option value="marca">MARCA</option>
                                        <option value="modelo">MODELO</option>
                                        <option value="anio">AÑO</option>
                                        <option value="conductor">CONDUCTOR</option>
                                        <option value="ruta">RUTA</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup="listarVehiculo(1, buscar, criterio, select_distribuidora, select_taller, desde, hasta, desde_km, hasta_km, select_estado, select_propietario)" class="form-control" placeholder="TEXTO A BUSCAR">
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">DESDE</div>
                                    <input type="date" v-model="desde" @change="cambiarHasta()" :max="fechaMaxima" class="form-control">
                                    <div class="input-group-addon bg-danger">HASTA</div>
                                    <input type="date" v-model="hasta" @change="cambiarDesde()" :max="fechaActual" :min="desde" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-5">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">DISTRIBUIDORA</div>
                                    <select class="form-control col-md-5" v-model="select_distribuidora" @change="listarVehiculo(1, buscar, criterio, select_distribuidora, select_taller, desde, hasta, desde_km, hasta_km, select_estado, select_propietario)">
                                        <option value="">TODOS</option>
                                        <option v-for="nombreDistribuidora in arrayNombreDistribuidoras" :key="nombreDistribuidora.id" :value="nombreDistribuidora.id" v-text="nombreDistribuidora.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">TALLER</div>
                                    <select class="form-control col-md-5" v-model="select_taller" @change="listarVehiculo(1, buscar, criterio, select_distribuidora, select_taller, desde, hasta, desde_km, hasta_km, select_estado, select_propietario)">
                                        <option value="">TODOS</option>
                                        <option v-for="nombreTaller in arrayNombreTalleres" :key="nombreTaller.id" :value="nombreTaller.id" v-text="nombreTaller.nombre"></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">ESTADO</div>
                                    <select class="form-control col-md-5" v-model="select_estado" @change="listarVehiculo(1, buscar, criterio, select_distribuidora, select_taller, desde, hasta, desde_km, hasta_km, select_estado, select_propietario)">
                                        <option value="">TODOS</option>
                                        <option value="1">ACTIVO</option>
                                        <option value="0">INACTIVO</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-8">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">DESDE</div>
                                    <input type="number" v-model="desde_km" @change="cambiarKmHasta()" :min="minimo_km_desde" :max="maximo_km_desde" class="form-control" placeholder="KILOMETRAJE">
                                    <div class="input-group-addon bg-danger">HASTA</div>
                                    <input type="number" v-model="hasta_km" @change="cambiarKmDesde()" :min="minimo_km_hasta" class="form-control" placeholder="KILOMETRAJE">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group input-daterange">
                                    <div class="input-group-addon bg-danger">PROPIETARIO</div>
                                    <select class="form-control col-md-5" v-model="select_propietario" @change="listarVehiculo(1, buscar, criterio, select_distribuidora, select_taller, desde, hasta, desde_km, hasta_km, select_estado, select_propietario)">
                                        <option value="">TODOS</option>
                                        <option value="ALQUILER">ALQUILER</option>
                                        <option value="DIANA">DIANA</option>
                                        <option value="HINORENT">HINORENT</option>
                                        <option value="PARTICULAR">PARTICULAR</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-sm">
                                        <thead>
                                            <tr class="bg-danger">
                                                <th class="align-middle" style="text-align: center;"><div class="sizeOpcion">OPCIONES</div></th>
                                                <th class="align-middle" style="text-align: center;">CÓDIGO COMB.</th>
                                                <th class="align-middle" style="text-align: center;">PLACA</th>
                                                <th class="align-middle" style="text-align: center;">DISTRIBUIDORA</th>
                                                <th class="align-middle" style="text-align: center;">FECHA ULTO KM</th>
                                                <th class="align-middle" style="text-align: center;">KM</th>
                                                <th class="align-middle" style="text-align: center;">ESTADO</th>
                                                <th class="align-middle" style="text-align: center;">TALLER</th>
                                                <th class="align-middle" style="text-align: center;">MTTO KMS</th>
                                                <th class="align-middle" style="text-align: center;">MTTO MESES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="vehiculo in arrayVehiculo" :key="vehiculo.id_vehiculo">
                                                <td style="text-align: center;">
                                                    <button type="button" @click="abrirModal('vehiculo', 'leer', vehiculo)" class="btn btn-info text-white btn-sm" data-toggle="modal" data-target="#modalNuevo" title="INFO. VEHÍCULO">
                                                        <i class="fa fa-eye"></i>
                                                    </button> &nbsp;
                                                    <button type="button" @click="abrirModal('vehiculo', 'actualizar', vehiculo)" class="btn btn-warning text-white btn-sm" data-toggle="modal" data-target="#modalNuevo" title="ACTUALIZAR VEHÍCULO">
                                                        <i class="fa fa-pencil"></i>
                                                    </button> &nbsp;
                                                    <template v-if="vehiculo.condicion">
                                                        <button type="button" @click="desactivarVehiculo(vehiculo.id_vehiculo)" class="btn btn-danger btn-sm" title="DESACTIVAR VEHÍCULO">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </template>
                                                    <template v-else>
                                                        <button type="button" @click="activarVehiculo(vehiculo.id_vehiculo)" class="btn btn-success btn-sm" title="ACTIVAR VEHÍCULO">
                                                            <i class="fa fa-check"></i>
                                                        </button>
                                                    </template>
                                                </td>
                                                <td class="align-middle" style="text-align: center;" v-text="vehiculo.codigo_comb"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="vehiculo.placa"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="vehiculo.nombre_distribuidora"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="vehiculo.ulto_km"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="vehiculo.km"></td>
                                                <td class="align-middle" style="text-align: center;">
                                                    <div v-if="vehiculo.condicion">
                                                        <span class="badge badge-success">ACTIVO</span>
                                                    </div>
                                                    <div v-else>
                                                        <span class="badge badge-danger">INACTIVO</span>
                                                    </div>
                                                </td>
                                                <td class="align-middle" style="text-align: center;" v-text="vehiculo.nombre_taller"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="vehiculo.periodo_mtto_kms"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="vehiculo.periodo_mtto_meses"></td>
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
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio, select_distribuidora, select_taller, desde, hasta, desde_km, hasta_km, select_estado, select_propietario)">Ant</a>
                                </li>
                                <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio, select_distribuidora, select_taller, desde, hasta, desde_km, hasta_km, select_estado, select_propietario)" v-text="page"></a>
                                </li>
                                <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                    <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio, select_distribuidora, select_taller, desde, hasta, desde_km, hasta_km, select_estado, select_propietario)">Sig</a>
                                </li>
                            </ul>
                        </nav>
                    </div>    
                </div>
            <!-- Fin ejemplo de tabla Listado -->
            </div>
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
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">PLACA (*):</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" v-model="placa" @blur="validarPlaca(placa, vehiculo_id)" placeholder="PLACA DEL VEHÍCULO" autofocus>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">CÓDIGO_COMB. (*):</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" v-model="codigo_comb" placeholder="CÓDIGO_COMB.">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">NUMERO DE VEHICULO:</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" v-model="numero_vehiculo" placeholder="NUMERO DEL VEHÍCULO">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">MARCA (*):</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" v-model="marca" placeholder="MARCA DEL VEHÍCULO">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">MODELO (*):</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" v-model="modelo" placeholder="MODELO DEL VEHÍCULO">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">AÑO (*):</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" v-model="anio" placeholder="AÑO DEL VEHÍCULO">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">DISTRIBUIDORA (*)</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="id_distribuidora">
                                            <option value="0">SELECCIONE UNA DISTRIBUIDORA</option>
                                            <option v-for="distribuidora in arrayDistribuidora" :key="distribuidora.id" :value="distribuidora.id" v-text="distribuidora.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">CONDUCTOR (*):</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" v-model="conductor" placeholder="CONDUCTOR DEL VEHÍCULO">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">TALLER (*)</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="id_taller">
                                            <option value="0">SELECCIONE UN TALLER</option>
                                            <option v-for="taller in arrayTaller" :key="taller.id" :value="taller.id" v-text="taller.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">PERIODO MTTO KMS:</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" v-model="periodo_mtto_kms" min="0" placeholder="MTTO KMS DEL VEHÍCULO">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">PERIODO MTTO MESES:</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" v-model="periodo_mtto_meses" min="0" placeholder="MTTO MESES DEL VEHÍCULO">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">RUTA (*):</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" v-model="ruta" placeholder="RUTA DEL VEHÍCULO">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">PROPIETARIO (*)</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="propietario">
                                            <option value="">SELECCIONE UN PROPIETARIO</option>
                                            <option value="ALQUILER">ALQUILER</option>
                                            <option value="DIANA">DIANA</option>
                                            <option value="HINORENT">HINRORENT</option>
                                            <option value="PARTICULAR">PARTICULAR</option>
                                        </select>
                                    </div>
                                </div>
                                <div v-show="errorVehiculo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVehiculo" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </template>
                        <template v-else-if="tipoAccion == 3">
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
                                <label class="col-md-3 form-control-label" for="text-input">PROPIETARIO:</label>
                                <div class="col-md-9">
                                    <label class="form-control-label" v-text="propietario"></label>
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
                                <div v-show="errorVehiculo" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjVehiculo" :key="error" v-text="error">

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
                        <template v-if="tipoAccion==4 && estadoImport=='con errores'">
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
                        <button type="button" v-if="tipoAccion==1" @click="registrarVehiculo()" class="btn btn-warning text-white">GUARDAR</button>
                        <button type="button" v-if="tipoAccion==2" @click="actualizarVehiculo()" class="btn btn-warning text-white">ACTUALIZAR</button>
                        <button type="button" v-if="tipoAccion==4" @click="importExcel()" class="btn btn-warning text-white">IMPORTAR</button>
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

        name : 'inventario-flota',

        components: {

            vSelect

        },

        data (){

            return {

                vehiculo_id: 0,
                rol_id : 0,
                codigo_comb : '',
                numero_vehiculo : '',
                placa : '',
                marca : '',
                modelo : '',
                anio : '',
                id_distribuidora : 0,
                arrayDistribuidora : [],
                conductor : '',
                ulto_kms : '',
                km: 0,
                id_taller : 0,
                arrayTaller : [],
                arrayVehiculo : [],
                arrayVehiculo2 : [],
                arrayNombreTalleres : [],
                arrayNombreDistribuidoras : [],
                periodo_mtto_kms : '',
                periodo_mtto_meses : '',
                ruta : '',
                propietario : '',
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorVehiculo : 0,
                errorMostrarMsjVehiculo : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'codigo_comb',
                buscar : '',
                desde : '',
                hasta : '',
                fechaMaxima : '',
                fechaActual : '',
                desde_km : '',
                hasta_km : '',
                km_Maxima : '',
                minimo_km_desde : 0,
                minimo_km_hasta : 0,
                maximo_km_desde : '',
                minimo_km_hasta : '',
                select_distribuidora : '',
                select_taller : '',
                select_estado : '',
                select_propietario : '',
                loading : false,
                uploading : false,
                import_file : '',
                arrayErroresImport : [],
                estadoImport : ''

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

            listarVehiculo(page, buscar, criterio, select_distribuidora, select_taller, desde, hasta, desde_km, hasta_km, select_estado, select_propietario){

                let me = this;
                var url = '/vehiculos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&select_distribuidora=' + select_distribuidora + '&select_taller=' + select_taller + '&desde=' + desde + '&hasta=' + hasta + '&desde_km=' + desde_km + '&hasta_km=' + hasta_km + '&select_estado=' + select_estado + '&select_propietario=' + select_propietario;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayVehiculo = respuesta.vehiculos.data;
                    me.arrayVehiculo2 = respuesta.vehiculos2;
                    me.arrayNombreDistribuidoras = respuesta.nombresDistribuidoras;
                    me.arrayNombreTalleres = respuesta.nombresTalleres;
                    me.rol_id = respuesta.rol;
                    me.pagination = respuesta.pagination;
                    console.log(respuesta);
                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            cambiarPagina(page, buscar, criterio, select_distribuidora, select_taller, desde, hasta, desde_km, hasta_km, select_estado, select_propietario){
                
                let me = this;

                me.pagination.current_page = page;
                me.listarVehiculo(page, buscar, criterio, select_distribuidora, select_taller, desde, hasta, desde_km, hasta_km, select_estado, select_propietario);

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

            selectTaller(){

                let me = this;
                var url = '/talleres/selectTaller';
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayTaller = respuesta.talleres;
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            registrarVehiculo(){

                if(this.validarVehiculo()){

                    return;
                    
                }

                let me = this;

                axios.post('/vehiculos/registrar', {

                    'codigo_comb': this.codigo_comb,
                    'placa': this.placa,
                    'numero_vehiculo' : this.numero_vehiculo,
                    'marca' : this.marca,
                    'modelo' : this.modelo,
                    'anio' : this.anio,
                    'id_distribuidora' : this.id_distribuidora,
                    'conductor' : this.conductor,
                    'id_taller' : this.id_taller,
                    'periodo_mtto_kms' : this.periodo_mtto_kms,
                    'periodo_mtto_meses' : this.periodo_mtto_meses,
                    'ruta' : this.ruta,
                    'propietario' : this.propietario 

                }).then(function (response) {

                    me.listarVehiculo(1, '', 'codigo_comb', '', '', '', '', '', '', '', '');
                    me.cerrarModal();
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'VEHÍCULO INGRESADO A LA FLOTA',
                        showConfirmButton: false,
                        timer: 1500
                    });

                }).catch(function (error) {

                    console.log(error);

                });

            },

            actualizarVehiculo(){

                if(this.validarVehiculo()){

                    return;
                    
                }

                let me = this;

                axios.put('/vehiculos/actualizar', {

                    'id' : this.vehiculo_id,
                    'codigo_comb': this.codigo_comb,
                    'placa': this.placa,
                    'numero_vehiculo' : this.numero_vehiculo,
                    'marca' : this.marca,
                    'modelo' : this.modelo,
                    'anio' : this.anio,
                    'id_distribuidora' : this.id_distribuidora,
                    'conductor' : this.conductor,
                    'id_taller' : this.id_taller,
                    'periodo_mtto_kms' : this.periodo_mtto_kms,
                    'periodo_mtto_meses' : this.periodo_mtto_meses,
                    'ruta' : this.ruta,
                    'propietario' : this.propietario

                }).then(function (response) {

                    me.listarVehiculo(1, me.buscar, me.criterio, me.select_distribuidora, me.select_taller, me.desde, me.hasta, me.desde_km, me.hasta_km, me.select_estado, me.select_propietario);
                    me.cerrarModal();
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'VEHÍCULO ACTUALIZADO',
                        showConfirmButton: false,
                        timer: 1500
                    });

                }).catch(function (error) {

                    console.log(error);

                });

            },

            desactivarVehiculo(id){

                const swalWithBootstrapButtons = Swal.mixin({

                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },

                    buttonsStyling: false

                    })

                    swalWithBootstrapButtons.fire({
                    
                        title: 'ESTÁ SEGURO DE DESACTIVAR A ESTE VEHÍCULO?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'SÍ, DESACTÍVALO!',
                        cancelButtonText: 'NO, CANCELAR!',
                        reverseButtons: true

                    }).then((result) => {

                        if (result.value) {

                            let me = this;

                            axios.put('/vehiculos/desactivar', {

                                'id': id

                            }).then(function (response) {

                                me.listarVehiculo(1, me.buscar, me.criterio, me.select_distribuidora, me.select_taller, me.desde, me.hasta, me.desde_km, me.hasta_km, me.select_estado, me.select_propietario);
                                swalWithBootstrapButtons.fire(
                                    'DESACTIVADO!',
                                    'EL VEHÍCULO HA SIDO DESACTIVADO.',
                                    'success'
                                )

                            }).catch(function (error) {

                                console.log(error);

                            });

                            
                        } else if (
                            /* Read more about handling dismissals below */
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            
                        }

                });
            },

            activarVehiculo(id){

                const swalWithBootstrapButtons = Swal.mixin({

                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },

                    buttonsStyling: false

                    })

                    swalWithBootstrapButtons.fire({
                    
                        title: 'ESTÁ SEGURO DE ACTIVAR A ESTE VEHÍCULO?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'SÍ, ACTÍVALO!',
                        cancelButtonText: 'NO, CANCELAR!',
                        reverseButtons: true

                    }).then((result) => {

                        if (result.value) {

                            let me = this;

                            axios.put('/vehiculos/activar', {

                                'id': id

                            }).then(function (response) {

                                me.listarVehiculo(1, me.buscar, me.criterio, me.select_distribuidora, me.select_taller, me.desde, me.hasta, me.desde_km, me.hasta_km, me.select_estado, me.select_propietario);
                                swalWithBootstrapButtons.fire(
                                    'ACTIVADO!',
                                    'EL VEHÍCULO HA SIDO ACTIVADO.',
                                    'success'
                                )

                            }).catch(function (error) {

                                console.log(error);

                            });

                            
                        } else if (
                            /* Read more about handling dismissals below */
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            
                        }

                });
            },

            validarVehiculo(){
                
                this.errorVehiculo = 0;
                this.errorMostrarMsjVehiculo = [];

                if(!this.placa) this.errorMostrarMsjVehiculo.push("LA PLACA DEL VEHÍCULO NO PUEDE IR VACÍA.");  
                if(!this.codigo_comb) this.errorMostrarMsjVehiculo.push("EL CÓDIGO_COMB. VEHÍCULO NO PUEDE IR VACÍO.");
                if(!this.marca) this.errorMostrarMsjVehiculo.push("LA MARCA DEL VEHÍCULO NO PUEDE IR VACÍA.");
                if(!this.modelo) this.errorMostrarMsjVehiculo.push("EL MODELO DEL VEHÍCULO NO PUEDE IR VACÍO.");
                if(!this.anio) this.errorMostrarMsjVehiculo.push("EL AÑO DEL VEHÍCULO NO PUEDE IR VACÍO.");
                if(!this.id_distribuidora || this.id_distribuidora == "0") this.errorMostrarMsjVehiculo.push("DEBE SELECCIONAR UNA DISTRIBUIDORA PARA EL VEHÍCULO.");
                if(!this.conductor) this.errorMostrarMsjVehiculo.push("EL CONDUCTOR DEL VEHÍCULO NO PUEDE IR VACÍO.");
                if(!this.id_taller || this.id_taller == "0") this.errorMostrarMsjVehiculo.push("DEBE SELECCIONAR UN TALLER PARA EL VEHÍCULO.");
                if(!this.ruta) this.errorMostrarMsjVehiculo.push("LA RUTA DEL VEHÍCULO NO PUEDE IR VACÍO.");
                if(!this.propietario) this.errorMostrarMsjVehiculo.push("DEBE SELECCIONAR UN PROPIETARIO");
                if(this.errorMostrarMsjVehiculo.length) this.errorVehiculo = 1;

                return this.errorVehiculo;

            },

            validarPlaca(placa, vehiculo_id){
                
                let me = this;

                if(me.placa != ''){

                    me.errorVehiculo = 0;
                    me.errorMostrarMsjVehiculo = [];

                    if(vehiculo_id == 0){

                        var url = '/vehiculos/unico/' + placa;

                        axios.get(url).then(function (response) {

                            var respuesta = response.data;

                            if(respuesta=="no") me.errorMostrarMsjVehiculo.push("LA PLACA " + placa + " YA EXISTE EN OTRO VEHÍCULO.");

                            if(me.errorMostrarMsjVehiculo.length){
                                
                                me.placa = '';
                                me.errorVehiculo = 1;
                                
                            } 

                            return me.errorVehiculo;

                        }).catch(function (error) {

                            console.log(error);

                        });

                    }else{

                        var url = '/vehiculos/unico/' + placa + '/' + vehiculo_id;

                        axios.get(url).then(function (response) {

                            var respuesta = response.data;

                            if(respuesta=="no") me.errorMostrarMsjVehiculo.push("LA PLACA " + placa + " YA EXISTE EN OTRO VEHÍCULO.");

                            if(me.errorMostrarMsjVehiculo.length){
                                
                                me.placa = '';
                                me.errorVehiculo = 1;
                                
                            } 

                            return me.errorVehiculo;

                        }).catch(function (error) {

                            console.log(error);

                        });

                    }

                    me.codigo_comb = '0' + me.placa;

                }else{

                    me.codigo_comb = '';

                }

            },

            abrirModal(modelo, accion, data = []){

                this.selectDistribuidora();
                this.selectTaller();

                switch (modelo) {

                    case 'vehiculo':
                        
                        {

                            switch (accion) {
                                
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'REGISTRAR VEHÍCULO'
                                        this.tipoAccion = 1;
                                        break;
                                    }

                                case 'actualizar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'ACTUALIZAR VEHÍCULO';
                                        this.vehiculo_id = data['id_vehiculo'];
                                        this.codigo_comb = data['codigo_comb'];
                                        this.placa = data['placa'];
                                        this.numero_vehiculo = data['numero_vehiculo'];
                                        this.marca = data['marca'];
                                        this.modelo = data['modelo'];
                                        this.anio = data['anio'];
                                        this.id_distribuidora = data['id_distribuidora'];
                                        this.conductor = data['conductor'];
                                        this.id_taller = data['id_taller'];
                                        this.periodo_mtto_kms = data['periodo_mtto_kms'];
                                        this.periodo_mtto_meses = data['periodo_mtto_meses'];
                                        this.ruta = data['ruta'];
                                        this.propietario = data['propietario'];
                                        this.tipoAccion = 2;
                                        break;
                                    }

                                case 'leer':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'INFORMACIÓN DEL VEHÍCULO';
                                        this.numero_vehiculo = data['numero_vehiculo'];
                                        this.marca = data['marca'];
                                        this.modelo = data['modelo'];
                                        this.anio = data['anio'];
                                        this.conductor = data['conductor'];
                                        this.ruta = data['ruta'];
                                        this.propietario = data['propietario'];
                                        this.tipoAccion = 3;
                                        break;
                                    }

                                case 'importar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'IMPORTAR VEHÍCULOS';
                                        this.tipoAccion = 4;
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
                this.vehiculo_id = 0;
                this.codigo_comb = '';
                this.placa = '';
                this.numero_vehiculo = '';
                this.modelo = '';
                this.marca = '';
                this.anio = '';
                this.id_distribuidora = 0;
                this.conductor = '';
                this.ulto_kms = '';
                this.km = 0;
                this.id_taller = 0;
                this.periodo_mtto_kms = '';
                this.periodo_mtto_meses = '';
                this.ruta = '';
                this.propietario = '';
                this.errorVehiculo = 0;
                this.arrayTaller = [];
                this.arrayDistribuidora = [];
                this.errorMostrarMsjVehiculo = [];
                this.import_file = '';
                this.$refs.import_file.value = null;
                this.estadoImport = '';
                this.arrayErroresImport = [];
            },

            cambiarDesde(){

                if(this.desde == '' && this.hasta != ''){
                    this.desde = this.hasta;
                    this.fechaMaxima = this.hasta;
                }else if(this.hasta == ''){
                    this.desde = '';
                    this.fechaActual2();
                }else{
                    this.fechaMaxima = this.hasta;
                }

                this.listarVehiculo(1, this.buscar, this.criterio, this.select_distribuidora, this.select_taller, this.desde, this.hasta, this.desde_km, this.hasta_km, this.select_estado, this.select_propietario);
                
            },

            cambiarHasta(){

                if(this.hasta == '' && this.desde != ''){

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
            
                    this.hasta = yyyy + '-' + mm + '-' + dd;

                }else if(this.desde == ''){
                    this.hasta = '';
                    this.fechaActual2();
                }

                this.listarVehiculo(1, this.buscar, this.criterio, this.select_distribuidora, this.select_taller, this.desde, this.hasta, this.desde_km, this.hasta_km, this.select_estado);
                
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

                if(this.desde_km == '' && this.hasta_km != ''){
                    this.desde_km = this.hasta_km;
                    this.maximo_km_desde = this.hasta_km;
                }else if(this.hasta_km == ''){
                    this.desde_km = '';
                }else{
                    this.maximo_km_desde = this.hasta_km;
                }

                this.listarVehiculo(1, this.buscar, this.criterio, this.select_distribuidora, this.select_taller, this.desde, this.hasta, this.desde_km, this.hasta_km, this.select_estado, this.select_propietario);
                
            },

            cambiarKmHasta(){

                if(this.hasta_km == '' && this.desde_km != ''){

                    this.hasta_km = this.desde_km;
                    this.minimo_km_hasta = this.desde_km;
                    this.maximo_km_desde = this.hasta_km;

                }else if(this.desde_km == ''){
                    this.hasta_km = '';
                }else{
                    this.maximo_km_desde = this.hasta_km;
                    this.minimo_km_hasta = this.desde_km;
                }

                this.listarVehiculo(1, this.buscar, this.criterio, this.select_distribuidora, this.select_taller, this.desde, this.hasta, this.desde_km, this.hasta_km, this.select_estado, this.select_propietario);                
            },

            exportarPDF(){
                
                let me = this;
                
                me.loading = true;

                axios.post('/reporteria/inventario-flota', {

                    'data' : me.arrayVehiculo2

                }).then(function (response) {

                    me.loading = false;

                    axios.get('/reporteria/inventario-flota-descargar', {

                        responseType: "blob"

                    }).then((response) => {
                        
                        console.log(response.data);
                        FileSaver.saveAs(response.data, 'inventario-flota.pdf');
                        
                    });

                    console.response(response);

                }).catch(function (error) {

                    console.log(error);

                });

            },

            descargarFormato(){
                
                let me = this;

                axios.get('/vehiculos/descargarFormato', {

                    responseType: "blob"

                }).then((response) => {
                    
                    console.log(response.data);
                    FileSaver.saveAs(response.data, 'formato-vehiculos.xlsx');
                    
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

                axios.post('/vehiculos/importar', formData, {

                    headers: { 'content-type': 'multipart/form-data' }
                
                }).then(function (response) {

                    console.log(response.data);
                    me.uploading = false;

                    var respuesta = response.data;
                    me.arrayErroresImport = respuesta.errores;
                    me.estadoImport = respuesta.estado;
                    
                    if(me.estadoImport === 'sin errores'){

                        me.listarVehiculo(1, '', 'codigo_comb', '', '', '', '', '', '', '', '');
                        me.cerrarModal();

                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'VEHÍCULOS IMPORTADOS CON ÉXITO!',
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

                this.errorVehiculo = 0;
                this.errorMostrarMsjVehiculo = [];

                if(!this.import_file) this.errorMostrarMsjVehiculo.push("DEBE SELECCIONAR UN ARCHIVO PARA IMPORTAR DATOS.");  
                if(this.errorMostrarMsjVehiculo.length) this.errorVehiculo = 1;

                return this.errorVehiculo;

            },

            exportarExcel(){

                let me = this;

                axios.post('/vehiculos/exportar-excel', {

                    'vehiculos': me.arrayVehiculo2

                }).then(function (response) {

                    axios.get('/vehiculos/vehiculo-descargar-excel', {

                        responseType: "blob"

                    }).then((response) => {
                        
                        console.log(response.data);
                        FileSaver.saveAs(response.data, 'inventario-flota.xlsx');
                        
                    });

                }).catch(function (error) {

                    console.log(error);

                });

            }

        },

        mounted() {

            this.listarVehiculo(1, this.buscar, this.criterio, this.select_distribuidora, this.select_taller, this.desde, this.hasta, this.desde_km, this.hasta_km, this.select_estado, this.select_propietario);

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
        width: 170px;
    }

    #lista1 li {
        display:inline;
        padding-left:3px;
        padding-right:3px;
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
