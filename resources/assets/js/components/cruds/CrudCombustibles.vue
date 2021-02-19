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
                    <i class="fa fa-align-justify"></i> <strong>GESTIÓN DE COMBUSTIBLES</strong>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criterio">
                                    <option value="nombre">NOMBRE</option>
                                </select>
                                <input type="text" id="texto" v-model="buscar" @keyup="listarCombustible(1, buscar, criterio)" class="form-control" placeholder="TEXTO A BUSCAR...">
                                <button type="submit" @click="listarCombustible(1, buscar, criterio)" class="btn btn-warning text-white" title="BUSCAR"><i class="fa fa-search"></i> BUSCAR</button>
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-2">
                            <button type="button" @click="abrirModal('combustible', 'registrar')" class="btn btn-success" data-toggle="modal" data-target="#modalNuevo" title="NUEVO COMBUSTIBLE">
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
                            <tr v-for="combustible in arrayCombustible" :key="combustible.id">
                                <td style="text-align: center;">
                                    <button type="button" @click="abrirModal('combustible', 'actualizar', combustible)" class="btn btn-warning text-white btn-sm" data-toggle="modal" data-target="#modalNuevo">
                                        <i class="fa fa-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="combustible.condicion">
                                        <button type="button" class="btn btn-danger btn-sm" @click="desactivarCombustible(combustible.id)" title="DESACTIVAR COMBUSTIBLE">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" class="btn btn-success btn-sm" @click="activarCombustible(combustible.id)" title="ACTIVAR COMBUSTIBLE">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </template>
                                </td>
                                <td style="text-align: center;" v-text="combustible.nombre"></td>
                                <td style="text-align: center;">
                                    <div v-if="combustible.condicion">
                                        <span class="badge badge-success">ACTIVO</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">DESACTIVADO</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <span class="page-stats">{{pagination.from}} - {{pagination.to}} of {{pagination.total}}</span>
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
                                <label class="col-md-3 form-control-label" for="text-input">TIPO DE COMBUSTIBLE:</label>
                                <div class="col-md-9">
                                    <input type="text" v-model="nombre" class="form-control" placeholder="TIPO DE COMBUSTIBLE" autofocus>
                                </div>
                            </div>
                            <div v-show="errorCombustible" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in errorMostrarMsjCombustible" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" @click="cerrarModal()">CERRAR</button>
                        <button type="button" v-if="tipoAccion==1" @click="registrarCombustible()" class="btn btn-warning text-white">GUARDAR</button>
                        <button type="button" v-if="tipoAccion==2" @click="actualizarCombustible()" class="btn btn-warning text-white">ACTUALIZAR</button>
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

                arrayCombustible : [],
                combustible_id : 0,
                nombre : '',
                condicion : true,
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorCombustible : 0,
                errorMostrarMsjCombustible : [],
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

            listarCombustible(page, buscar, criterio){

                let me = this;
                var url = '/combustibles?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                
                axios.get(url).then(function (response) {

                    var respuesta = response.data;
                    me.arrayCombustible = respuesta.combustibles.data;
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
                me.listarCombustible(page, buscar, criterio);

            },

            registrarCombustible(){

                if(this.validarCombustible()){

                    return;
                    
                }

                let me = this;

                axios.post('/combustibles/registrar', {

                    'nombre': this.nombre

                }).then(function (response) {

                    me.cerrarModal();
                    me.listarCombustible(1, '', 'nombre');

                }).catch(function (error) {

                    console.log(error);

                });

            },

            actualizarCombustible(){

                if(this.validarCombustible()){

                    return;
                    
                }

                let me = this;

                axios.put('/combustibles/actualizar', {

                    'nombre': this.nombre,
                    'id': this.combustible_id

                }).then(function (response) {

                    me.cerrarModal();
                    me.listarCombustible(1, '', 'nombre');

                }).catch(function (error) {

                    console.log(error);

                });

            },

            desactivarCombustible(id){

                const swalWithBootstrapButtons = Swal.mixin({

                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },

                    buttonsStyling: false

                    })

                    swalWithBootstrapButtons.fire({
                    
                        title: 'ESTÁ SEGURO DE DESACTIVAR A ESTE TIPO DE COMBUSTIBLE?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'SÍ, DESACTÍVALO!',
                        cancelButtonText: 'NO, CANCELAR!',
                        reverseButtons: true

                    }).then((result) => {

                        if (result.value) {

                            let me = this;

                            axios.put('/combustibles/desactivar', {

                                'id': id

                            }).then(function (response) {

                                me.listarCombustible(1, '', 'nombre');
                                swalWithBootstrapButtons.fire(
                                    'DESACTIVADO!',
                                    'EL TIPO DE COMBUSTIBLE HA SIDO DESACTIVADO.',
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

            activarCombustible(id){

                const swalWithBootstrapButtons = Swal.mixin({

                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },

                    buttonsStyling: false

                    })

                    swalWithBootstrapButtons.fire({
                    
                        title: 'ESTÁ SEGURO DE ACTIVAR A ESTE TIPO DE COMBUSTIBLE?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'SÍ, ACTÍVALO!',
                        cancelButtonText: 'NO, CANCELAR!',
                        reverseButtons: true

                    }).then((result) => {

                        if (result.value) {

                            let me = this;

                            axios.put('/combustibles/activar', {

                                'id': id

                            }).then(function (response) {

                                me.listarCombustible(1, '', 'nombre');
                                swalWithBootstrapButtons.fire(
                                    'ACTIVADO!',
                                    'EL TIPO DE COMBUSTIBLE HA SIDO ACTIVADO.',
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

            validarCombustible(){
                
                this.errorCombustible = 0;
                this.errorMostrarMsjCombustible = [];

                if(!this.nombre) this.errorMostrarMsjCombustible.push("EL TIPO DE COMBUSTIBLE NO PUEDE IR VACÍO.");
                if(this.errorMostrarMsjCombustible.length) this.errorCombustible = 1;

                return this.errorCombustible;

            },

            abrirModal(modelo, accion, data = []){

                switch (modelo) {

                    case 'combustible':
                        
                        {

                            switch (accion) {
                                
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'REGISTRAR COMBUSTIBLE'
                                        this.nombre = '';
                                        this.tipoAccion = 1;
                                        break;
                                    }

                                case 'actualizar':
                                    {
                                         //console.log(data);
                                         this.modal = 1;
                                         this.tituloModal = 'ACTUALIZAR COMBUSTIBLE';
                                         this.tipoAccion = 2;
                                         this.combustible_id = data['id'];
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

            this.listarCombustible(1, this.buscar, this.criterio);

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
