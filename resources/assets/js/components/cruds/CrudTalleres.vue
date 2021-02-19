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
                    <i class="fa fa-align-justify"></i> <strong>GESTIÓN DE TALLERES</strong>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">NOMBRE</option>
                                </select>
                                <input type="text" id="texto" v-model="buscar" @keyup="listarTaller(1, buscar, criterio)" class="form-control" placeholder="TEXTO A BUSCAR...">
                                <button type="submit" @click="listarTaller(1, buscar, criterio)" class="btn btn-warning text-white" title="BUSCAR"><i class="fa fa-search"></i> BUSCAR</button>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-2">
                            <button type="button" @click="abrirModal('taller', 'registrar')" class="btn btn-success" data-toggle="modal" data-target="#modalNuevo" title="NUEVO TALLER">
                                <i class="fa fa-plus-circle"></i>&nbsp;NUEVO
                            </button>
                        </div>
                    </div>
                    <table class="table table-responsive table-bordered table-sm">
                        <thead>
                            <tr class="bg-danger">
                                <th style="text-align: center;">OPCIONES</th>
                                <th style="text-align: center;">NOMBRE</th>
                                <th style="text-align: center;">ESTADO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="taller in arrayTaller" :key="taller.id">
                                <td style="text-align: center;">
                                    <button type="button" @click="abrirModal('taller', 'actualizar', taller)" class="btn btn-warning text-white btn-sm" data-toggle="modal" data-target="#modalNuevo">
                                        <i class="fa fa-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="taller.condicion">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarTaller(taller.id)" title="DESACTIVAR TALLER">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-success btn-sm" @click="activarTaller(taller.id)" title="ACTIVAR TALLER">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td style="text-align: center;" v-text="taller.nombre"></td>
                                <td style="text-align: center;">
                                    <div v-if="taller.condicion">
                                        <span class="badge badge-success">ACTIVO</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">INACTIVO</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <span class="page-stats">Del {{pagination.from}} al {{pagination.to}} de un total de  {{pagination.total}} registros.</span>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio)">Sig</a>
                            </li>
                        </ul>
                    </nav>
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
                                <label class="col-md-3 form-control-label" for="text-input">NOMBRE DEL TALLER (*):</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="NOMBRE DEL TALLER" autofocus>
                                </div>
                            </div>
                            <div v-show="errorTaller" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjTaller" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" @click="cerrarModal()">CERRAR</button>
                        <button type="button" v-if="tipoAccion==1" @click="registrarTaller()" class="btn btn-warning text-white">GUARDAR</button>
                        <button type="button" v-if="tipoAccion==2" @click="actualizarTaller()" class="btn btn-warning text-white">ACTUALIZAR</button>
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
    export default {

        data (){

            return {

                arrayTaller : [],
                taller_id : 0,
                nombre : '',
                condicion : true,
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorTaller : 0,
                errorMostrarMsjTaller : [],
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre',
                buscar : ''

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

            listarTaller(page, buscar, criterio){

                let me = this;
                var url = '/talleres?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                
                axios.get(url).then(function (response) {

                    var respuesta = response.data;
                    me.arrayTaller = respuesta.talleres.data;
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
                me.listarTaller(page, buscar, criterio);

            },

            registrarTaller(){

                if(this.validarTaller()){

                    return;
                    
                }

                let me = this;

                axios.post('/talleres/registrar', {

                    'nombre': this.nombre

                }).then(function (response) {

                    me.cerrarModal();
                    me.listarTaller(1, '', 'nombre');

                }).catch(function (error) {

                    console.log(error);

                });

            },

            actualizarTaller(){

                if(this.validarTaller()){

                    return;
                    
                }

                let me = this;

                axios.put('/talleres/actualizar', {

                    'nombre': this.nombre,
                    'id': this.ejecutor_id

                }).then(function (response) {

                    me.cerrarModal();
                    me.listarTaller(1, '', 'nombre');

                }).catch(function (error) {

                    console.log(error);

                });

            },

            desactivarTaller(id){

                const swalWithBootstrapButtons = Swal.mixin({

                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },

                    buttonsStyling: false

                    })

                    swalWithBootstrapButtons.fire({
                    
                        title: 'ESTÁ SEGURO DE DESACTIVAR A ESTE TALLER?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'SÍ, DESACTÍVALO!',
                        cancelButtonText: 'NO, CANCELAR!',
                        reverseButtons: true

                    }).then((result) => {

                        if (result.value) {

                            let me = this;

                            axios.put('/talleres/desactivar', {

                                'id': id

                            }).then(function (response) {

                                me.listarTaller(1, '', 'nombre');
                                swalWithBootstrapButtons.fire(
                                    'DESACTIVADO!',
                                    'EL TALLER HA SIDO DESACTIVADO.',
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

            activarTaller(id){

                const swalWithBootstrapButtons = Swal.mixin({

                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },

                    buttonsStyling: false

                    })

                    swalWithBootstrapButtons.fire({
                    
                        title: 'ESTÁ SEGURO DE ACTIVAR A ESTE TALLER?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'SÍ, ACTÍVALO!',
                        cancelButtonText: 'NO, CANCELAR!',
                        reverseButtons: true

                    }).then((result) => {

                        if (result.value) {

                            let me = this;

                            axios.put('/talleres/activar', {

                                'id': id

                            }).then(function (response) {

                                me.listarTaller(1, '', 'nombre');
                                swalWithBootstrapButtons.fire(
                                    'ACTIVADO!',
                                    'EL TALLER HA SIDO ACTIVADO.',
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

            validarTaller(){
                
                this.errorTaller = 0;
                this.errorMostrarMsjTaller = [];

                if(!this.nombre) this.errorMostrarMsjTaller.push("EL NOMBRE DEL TALLER NO PUEDE IR VACÍO.");
                if(this.errorMostrarMsjTaller.length) this.errorTaller = 1;

                return this.errorTaller;

            },

            abrirModal(modelo, accion, data = []){

                switch (modelo) {

                    case 'taller':
                        
                        {

                            switch (accion) {
                                
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'REGISTRAR TALLER'
                                        this.nombre = '';
                                        this.tipoAccion = 1;
                                        break;
                                    }

                                case 'actualizar':
                                    {
                                         //console.log(data);
                                         this.modal = 1;
                                         this.tituloModal = 'ACTUALIZAR TALLER';
                                         this.tipoAccion = 2;
                                         this.ejecutor_id = data['id'];
                                         this.nombre = data['nombre'];
                                         break;
                                    }
                            
                            }

                        }
                        
                }
            },

            cerrarModal(){

                this.modal = 0;
                this.tituloModal = '';
                this.nombre = '';

            }

        },

        mounted() {

            this.listarTaller(1, this.buscar, this.criterio);

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

</style>
