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
                        <i class="fa fa-align-justify"></i><strong>COSTOS DE RUTINAS</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-2">
                                <!--button type="button" @click="abrirModal('costorutina', 'registrar')" class="btn btn-success" data-toggle="modal" data-target="#modalNuevo" title="NUEVO COSTO">
                                    <i class="fa fa-plus-circle"></i>&nbsp;NUEVO
                                </button-->
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item" v-for="costo in arrayCostoRutina" :key="costo.correlativo">
                                    <a class="nav-link" data-toggle="tab" :href="'#' + costo.nombre_distribuidora" role="tab" :aria-controls="costo.nombre_distribuidora">
                                        <i class="icon-calculator"></i><span v-text="costo.nombre_distribuidora"></span>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div v-for="costo in arrayCostoRutina" :key="costo.correlativo" class="tab-pane" :id="costo.nombre_distribuidora" role="tabpanel">
                                    <div class="table-container-costo">
                                        <table class="table table-responsive table-bordered table-striped table-sm">
                                            <thead>
                                                <tr class="bg-danger">
                                                    <th colspan="4" style="text-align: center;" class="align-middle" v-text="costo.nombre_distribuidora"></th> 
                                                </tr>
                                                <tr class="bg-danger">
                                                    <th v-if="rol_id != 1" style="text-align: center;" class="align-middle">OPCIONES</th>
                                                    <th style="text-align: center;" class="align-middle">MODELO</th>
                                                    <th style="text-align: center" class="align-middle">RUTINA</th>
                                                    <th style="text-align: center;" class="align-middle">COSTO</th> 
                                                </tr>
                                            </thead>
                                            <tbody v-for="tabla in costo.costos_rutinas" :key="tabla.id">
                                                <td v-if="rol_id != 1" style="text-align: center;">
                                                    <button type="button" @click="abrirModal('costorutina', 'actualizar', tabla)" class="btn btn-warning text-white btn-sm" data-toggle="modal" data-target="#modalNuevo" title="ACTUALIZAR REGISTRO">
                                                        <i class="fa fa-pencil"></i>
                                                    </button> &nbsp;
                                                </td>
                                                <td class="align-middle" style="text-align: center;" v-text="tabla.modelo"></td>
                                                <td class="align-middle" style="text-align: center;" v-text="tabla.rutina"></td>
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
                                    <label class="col-md-3 form-control-label" for="text-input">MODELO:</label>
                                    <div class="col-md-9">
                                        <label class="form-control-label" v-text="modelo"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">RUTINA:</label>
                                    <div class="col-md-9">
                                        <label class="form-control-label" v-text="rutina"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">COSTO:</label>
                                    <div class="col-md-9">
                                        <input type="number" class="form-control" v-model="costo" min="0" placeholder="COSTO">
                                    </div>
                                </div>
                                <div v-show="errorCostoRutina" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjCostoRutina" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal()">CERRAR</button>
                            <button type="button" v-if="tipoAccion==1" class="btn btn-warning text-white">GUARDAR</button>
                            <button type="button" v-if="tipoAccion==2" @click="actualizarCostoRutina()" class="btn btn-warning text-white">ACTUALIZAR</button>
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

                rol_id : 0,
                arrayCostoRutina : [],
                arrayDistribuidora : [],
                arrayModelo : [],
                distribuidoras : [],
                id_modelo : 0,
                id_costorutina : 0,
                id_distribuidora : 0,
                modelo : '',
                rutina : '',
                costo : '',
                errorMostrarMsjCostoRutina : [],
                errorCostoRutina : 0,
                modal : 0,
                tituloModal : '',
                tipoAccion : 0

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

            listarCostoRutina(){

                let me = this;
                var url = '/costosrutinas';
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayCostoRutina = respuesta.costosrutinas;
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

                    case 'costorutina':
                        
                        {

                            switch (accion) {
                                
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'REGISTRAR NUEVO COSTO DE RUTINA'
                                        this.modelo = '';
                                        this.rutina = '';
                                        this.costo = '';
                                        this.distribuidoras = '';
                                        this.tipoAccion = 1;
                                        break;
                                    }

                                case 'actualizar':
                                    {
                                         //console.log(data);
                                         this.modal = 1;
                                         this.tituloModal = 'ACTUALIZAR COSTO DE RUTINA';
                                         this.tipoAccion = 2;
                                         this.id_costorutina = data['id'];
                                         this.id_distribuidora = data['id_distribuidora'];
                                         this.modelo = data['modelo'];
                                         this.rutina = data['rutina'];
                                         this.costo = data['costo'];
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
                this.id_costorutina = 0;
                this.id_distribuidora = 0;
                this.modelo = '';
                this.rutina = '';
                this.costo = '';
                this.distribuidoras = [];
                this.arrayDistribuidora = [];
                this.errorCostoRutina = 0;
                this.errorMostrarMsjCostoRutina = [];
            },

            selectDistribuidora(search, loading){

                // Buscar los clientes que coincidan con el campo search

                let me = this;
                loading(true);

                var url = '/distribuidoras/selectDistribuidoraUsuario?filtro='+search;
                
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

            actualizarCostoRutina(){

                if(this.validarCostoRutina()){

                    return;
                    
                }

                let me = this;

                axios.put('/costosrutinas/actualizar', {

                    'id': this.id_costorutina,
                    'costo': this.costo,
                    'id_distribuidora': this.id_distribuidora,

                }).then(function (response) {

                    me.cerrarModal();

                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'COSTO DE RUTINA ACTUALIZADO',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    me.listarCostoRutina();
                    

                }).catch(function (error) {

                    console.log(error);

                });

            },

            validarCostoRutina(){
                
                this.errorCostoRutina = 0;
                this.errorMostrarMsjCostoRutina = [];

                if(!this.costo) this.errorMostrarMsjCostoRutina.push("EL COSTO NO PUEDE IR VACÍO.");  
                
                if(this.errorMostrarMsjCostoRutina.length) this.errorCostoRutina = 1;

                return this.errorCostoRutina;

            },

        },

        mounted() {

            this.listarCostoRutina();

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
