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
                        <i class="fa fa-align-justify"></i><strong>COSTOS DE REPUESTOS</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-2">
                                <button v-if="rol_id != 1" type="button" @click="abrirModal('costorepuesto', 'registrar')" class="btn btn-success" data-toggle="modal" data-target="#modalNuevo" title="NUEVO COSTO">
                                    <i class="fa fa-plus-circle"></i>&nbsp;NUEVO
                                </button>
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" v-for="costo in arrayCostoRepuestos" :key="costo.correlativo">
                                    <a class="nav-link" data-toggle="tab" :href="'#' + costo.nombre_distribuidora" role="tab" :aria-controls="costo.nombre_distribuidora">
                                        <i class="icon-calculator"></i><span v-text="costo.nombre_distribuidora"></span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div v-for="costo in arrayCostoRepuestos" :key="costo.correlativo" class="tab-pane" :id="costo.nombre_distribuidora" role="tabpanel">
                                    <div class="table-container-costo">
                                        <table class="table table-responsive table-bordered table-striped table-sm">
                                            <thead>
                                                <tr class="bg-danger">
                                                    <th colspan="8" style="text-align: center;" class="align-middle" v-text="costo.nombre_distribuidora"></th> 
                                                </tr>
                                                <tr class="bg-danger">
                                                    <th v-if="rol_id != 1" style="text-align: center;" class="align-middle">OPCIONES</th>
                                                    <th style="text-align: center;" class="align-middle">MODELO</th>
                                                    <th style="text-align: center" class="align-middle">RUTINAS</th>
                                                    <th style="text-align: center" class="align-middle">MATERIAL</th>
                                                    <th style="text-align: center" class="align-middle">REPUESTOS</th>
                                                    <th style="text-align: center" class="align-middle">DESCRIPCIÓN</th>
                                                    <th style="text-align: center" class="align-middle">CANTIDAD</th>
                                                    <th style="text-align: center;" class="align-middle">COSTO</th> 
                                                </tr>
                                            </thead>
                                            <tbody v-for="tabla in costo.costos_repuestos" :key="tabla.id">
                                                <td v-if="rol_id != 1" style="text-align: center;">
                                                    <button type="button" @click="abrirModal('costorepuesto', 'actualizar', tabla)" class="btn btn-warning text-white btn-sm" data-toggle="modal" data-target="#modalNuevo" title="ACTUALIZAR REGISTRO">
                                                        <i class="fa fa-pencil"></i>
                                                    </button> &nbsp;
                                                    <button type="button" @click="eliminarCostoRepuesto(tabla.id)" class="btn btn-danger text-white btn-sm" data-toggle="modal" data-target="#modalNuevo" title="ELIMINAR REGISTRO">
                                                        <i class="fa fa-close"></i>
                                                    </button> &nbsp;
                                                </td>
                                                <td class="align-middle" style="text-align: center;" v-text="tabla.modelo"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="tabla.rutinas"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="tabla.material"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="tabla.repuesto"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="tabla.descripcion"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="tabla.cantidad"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="tabla.costo"></td>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <!-- /.col-->
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
                                    <label class="col-md-3 form-control-label" for="email-input">MODELO (*):</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="modelo">
                                            <option value="">SELECCIONE UN MODELO</option>
                                            <option value="TODOS">TODOS</option>
                                            <option v-for="modelo in arrayModelo" :key="modelo.correlativo" :value="modelo.modelo" v-text="modelo.modelo"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">RUTINAS (*):</label>
                                    <div class="col-md-9">
                                        <v-select
                                            multiple
                                            v-model="rutinas"
                                            :options="arrayRutinas"
                                            placeholder="BUSCAR RUTINAS..."
                                        >
                                        </v-select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">MATERIAL:</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="material" class="form-control" placeholder="MATERIAL">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">REPUESTO (*):</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="repuesto" class="form-control" placeholder="REPUESTO">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">DESCRIPCIÓN (*):</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="descripcion" class="form-control" placeholder="DESCRIPCIÓN">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">CANTIDAD (*):</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" v-model="cantidad" min="0" placeholder="CANTIDAD">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">COSTO (*):</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" v-model="costo" min="0" placeholder="COSTO">
                                    </div>
                                </div>
                                <div class="form-group row" v-if="tipoAccion == 1">
                                    <label class="col-md-3 form-control-label" for="email-input">DISTRIBUIDORAS (*):</label>
                                    <div class="col-md-9">
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
                                </div>
                                <div v-show="errorCostoRepuesto" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCostoRepuesto" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal()">CERRAR</button>
                            <button type="button" v-if="tipoAccion==1" @click="registrarCostoRepuesto()" class="btn btn-warning text-white">GUARDAR</button>
                            <button type="button" v-if="tipoAccion==2" @click="actualizarCostoRepuesto()" class="btn btn-warning text-white">ACTUALIZAR</button>
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

    import 'vue-select/dist/vue-select.css';

    Vue.component('v-select', vSelect);

    export default {

        components: {

            vSelect

        },

        data (){

            return {

                costorepuesto_id : 0,
                rol_id : 0,
                arrayCostoRepuestos : [],
                arrayDistribuidora : [],
                distribuidoras : [],
                arrayModelo : [],
                arrayRutinas : ['A', 'B', 'C', 'D'],
                modelo : '',
                rutinas : '',
                material : '',
                repuesto : '',
                descripcion : '',
                cantidad : 0,
                costo : 0,
                errorMostrarMsjCostoRepuesto : [],
                errorCostoRepuesto : 0,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0,
                distribuidora : {
                    id : 0,
                    nombre : '',
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

            }

        },

        methods : {

            selectModelo(){

                let me = this;
                var url = '/vehiculos/selectModelo';
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayModelo = respuesta.modelos;
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            listarCostoRepuesto(){

                let me = this;
                var url = '/costosrepuestos';
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCostoRepuestos = respuesta.costosrepuestos;
                    me.rol_id = respuesta.rol;
                    me.pagination = respuesta.pagination;
                    console.log(response);
                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            abrirModal(modelo, accion, data = []){

                this.selectModelo();

                switch (modelo) {

                    case 'costorepuesto':
                        
                        {

                            switch (accion) {
                                
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'REGISTRAR NUEVO COSTO DE REPUESTO'
                                        this.modelo = '';
                                        this.rutinas = '';
                                        this.material = '';
                                        this.repuesto = '';
                                        this.descripcion = '';
                                        this.distribuidoras = '';
                                        this.cantidad = '';
                                        this.costo = '';
                                        this.tipoAccion = 1;
                                        break;
                                    }

                                case 'actualizar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'EDITAR COSTO DE REPUESTO';
                                        this.costorepuesto_id = data['id'];
                                        this.modelo = data['modelo'];
                                        this.rutinas = data['rutinas'].split(',');
                                        this.material = data['material'];
                                        this.repuesto = data['repuesto'];
                                        this.descripcion = data['descripcion'];
                                        this.distribuidoras = data['id_distribuidora'];
                                        this.cantidad = data['cantidad'];
                                        this.costo = data['costo'];
                                        this.tipoAccion = 2;
                                        break;
                                    }
                            
                            }

                        }
                        
                }
            },

            cerrarModal(){

                this.modal = 0;
                this.tituloModal = '';
                this.modelo = '';
                this.rutinas = '';
                this.material = '';
                this.repuesto = '';
                this.descripcion = '';
                this.cantidad = 0;
                this.costo = 0;
                this.arrayModelo = [],
                this.distribuidoras = [];
                this.arrayDistribuidora = [];
                this.arrayRutinas = ['A', 'B', 'C', 'D'];
                this.tipoAccion = 0;
                this.distribuidora = {
                    id : 0,
                    nombre : '',
                }
                this.errorCostoRepuesto = 0;
                this.errorMostrarMsjCostoRepuesto = [];
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

            registrarCostoRepuesto(){

                if(this.validarCostoRepuesto()){

                    return;
                    
                }

                let me = this;

                axios.post('/costosrepuestos/registrar', {

                    'modelo' : this.modelo,
                    'rutinas' : this.rutinas,
                    'material' : this.material,
                    'repuesto' : this.repuesto,
                    'descripcion' : this.descripcion,
                    'cantidad' : this.cantidad,
                    'costo' : this.costo,
                    'distribuidoras' : this.distribuidoras

                }).then(function (response) {

                    me.listarCostoRepuesto();
                    me.cerrarModal();
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'COSTO DE REPUESTO INGRESADO',
                        showConfirmButton: false,
                        timer: 1500
                    });

                }).catch(function (error) {

                    console.log(error);

                });

            },

            actualizarCostoRepuesto(){

                if(this.validarCostoRepuesto()){

                    return;
                    
                }

                let me = this;

                axios.put('/costosrepuestos/actualizar', {

                    'id_costorepuesto' : this.costorepuesto_id,
                    'modelo' : this.modelo,
                    'rutinas' : this.rutinas,
                    'material' : this.material,
                    'repuesto' : this.repuesto,
                    'descripcion' : this.descripcion,
                    'cantidad' : this.cantidad,
                    'costo' : this.costo,

                }).then(function (response) {

                    me.listarCostoRepuesto();
                    me.cerrarModal();
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'COSTO DE REPUESTO ACTUALIZADO',
                        showConfirmButton: false,
                        timer: 1500
                    });

                }).catch(function (error) {

                    console.log(error);

                });

            },

            validarCostoRepuesto(){
                
                this.errorCostoRepuesto = 0;
                this.errorMostrarMsjCostoRepuesto = [];

                if(!this.modelo) this.errorMostrarMsjCostoRepuesto.push("DEBE SELECCIONAR UN MODELO");
                if(!this.rutinas.length) this.errorMostrarMsjCostoRepuesto.push("DEBE SELECCIONAR AL MENOS UNA RUTINA");
                if(!this.repuesto) this.errorMostrarMsjCostoRepuesto.push("EL REPUESTO NO PUEDE IR VACÍO.");
                if(!this.descripcion) this.errorMostrarMsjCostoRepuesto.push("LA DESCRIPCIÓN NO PUEDE IR VACÍA.");
                if(!this.cantidad) this.errorMostrarMsjCostoRepuesto.push("LA CANTIDAD NO PUEDE IR VACÍA.");
                if(!this.costo) this.errorMostrarMsjCostoRepuesto.push("EL COSTO NO PUEDE IR VACÍO.");
                if(!this.distribuidoras.length && this.tipoAccion == 1) this.errorMostrarMsjCostoRepuesto.push("DEBE SELECCIONAR AL MENOS UNA DISTRIBUIDORA");
    
                if(this.errorMostrarMsjCostoRepuesto.length) this.errorCostoRepuesto = 1;

                return this.errorCostoRepuesto;

            },

            eliminarCostoRepuesto(id){

                let me = this;

                const swalWithBootstrapButtons = Swal.mixin({

                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },

                    buttonsStyling: false

                    })

                    swalWithBootstrapButtons.fire({
                    
                        title: 'ESTÁ SEGURO DE ELIMINAR ESTE REGISTRO DE COSTO DE REPUESTO?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'SÍ, ELIMÍNALO!',
                        cancelButtonText: 'NO, CANCELAR!',
                        reverseButtons: true

                    }).then((result) => {

                        if (result.value) {

                            axios.delete('/costosrepuestos/eliminar?id=' + id).then(function (response) {

                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'COSTO DE REPUESTO ELIMINADO',
                                    showConfirmButton: false,
                                    timer: 1500
                                });

                                me.listarCostoRepuesto();

                            });

                            
                        } else if (
                            /* Read more about handling dismissals below */
                            result.dismiss === Swal.DismissReason.cancel
                        ) {
                            
                        }

                });

                

            }

        },

        mounted() {

            this.listarCostoRepuesto();

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
