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
                        <i class="fa fa-align-justify"></i><strong>GESTIÓN DE USUARIOS</strong>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <select class="form-control col-md-3" v-model="criterio">
                                      <option value="nombre">NOMBRE</option>
                                      <option value="nombre_completo">NOMBRE COMPLETO</option>
                                      <option value="email">EMAIL</option>
                                    </select>
                                    <input type="text" v-model="buscar" @keyup="listarUsuario(1, buscar, criterio)" class="form-control" placeholder="TEXTO A BUSCAR">
                                    <button type="submit" @click="listarUsuario(1, buscar, criterio)" class="btn btn-warning text-white"><i class="fa fa-search"></i> BUSCAR</button>
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-2">
                                <button type="button" @click="abrirModal('usuario', 'registrar')" class="btn btn-success" data-toggle="modal" data-target="#modalNuevo" title="NUEVO USUARIO">
                                    <i class="fa fa-plus-circle"></i>&nbsp;NUEVO
                                </button>
                            </div>
                        </div>
                        <table class="table table-responsive table-bordered table-striped table-sm">
                            <thead>
                                <tr class="bg-danger">
                                    <th style="text-align: center;" class="align-middle"><div class="sizeOpcion">OPCIONES</div></th>
                                    <th style="text-align: center;" class="align-middle">NOMBRE DE USUARIO</th>
                                    <th style="text-align: center;" class="align-middle">NOMBRE COMPLETO</th>
                                    <th style="text-align: center;" class="align-middle">EMAIL</th>
                                    <th style="text-align: center;" class="align-middle">ROL</th>
                                    <th style="text-align: center;" class="align-middle">DISTRIBUIDORAS</th>
                                    <th style="text-align: center;" class="align-middle">ULTIMO LOGIN</th>
                                    <th style="text-align: center;" class="align-middle">ESTADO</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="usuario in arrayUsuario" :key="usuario.id">
                                    <td style="text-align: center;">
                                        <button type="button" @click="abrirModal('usuario', 'actualizar', usuario)" class="btn btn-warning text-white btn-sm" data-toggle="modal" data-target="#modalNuevo" title="ACTUALIZAR USUARIO">
                                        <i class="fa fa-pencil"></i>
                                    </button> &nbsp;
                                    <template v-if="usuario.condicion">
                                        <button type="button" @click="desactivarUsuario(usuario.id)" class="btn btn-danger btn-sm" title="DESACTIVAR USUARIO">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </template>
                                    <template v-else>
                                        <button type="button" @click="activarUsuario(usuario.id)" class="btn btn-success btn-sm" title="ACTIVAR USUARIO">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </template>
                                    </td>
                                    <td style="text-align: center;" class="align-middle" v-text="usuario.nombre"></td>
                                    <td style="text-align: center;" class="align-middle" v-text="usuario.nombre_completo"></td>
                                    <td style="text-align: center;" class="align-middle" v-text="usuario.email"></td>
                                    <td style="text-align: center;" class="align-middle" v-text="usuario.rol"></td>
                                    <td style="text-align: center;" class="align-middle" v-text="usuario.distribuidoras"></td>
                                    <td style="text-align: center;" class="align-middle" v-text="usuario.ultimo_login"></td>
                                    <td style="text-align: center;" class="align-middle">
                                        <div v-if="usuario.condicion">
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
                                    <label class="col-md-3 form-control-label" for="text-input">NOMBRE DE USUARIO (*):</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre" @blur="validarNombre(nombre, user_id)" class="form-control" placeholder="NOMBRE DE USUARIO" autofocus>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">NOMBRE COMPLETO (*):</label>
                                    <div class="col-md-9">
                                        <input type="text" v-model="nombre_completo" class="form-control" placeholder="NOMBRE COMPLETO DEL USUARIO" autofocus>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="text-input">EMAIL:</label>
                                    <div class="col-md-9">
                                        <input type="email" v-model="email" @blur="validarEmail(email, user_id)" class="form-control" placeholder="EMAIL DEL USUARIO">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">PASSWORD (*):</label>
                                    <div class="col-md-9">
                                        <input type="password" v-model="password" class="form-control" placeholder="PASSWORD DE ACCESO">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label" for="email-input">ROL (*):</label>
                                    <div class="col-md-9">
                                        <select class="form-control" v-model="id_rol">
                                            <option value="0">SELECCIONE UN ROL</option>
                                            <option v-for="rol in arrayRol" :key="rol.id" :value="rol.id" v-text="rol.nombre"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
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
                                <div v-show="errorUsuario" class="form-group row div-error">
                                    <div class="text-center text-error">
                                        <div v-for="error in errorMostrarMsjUsuario" :key="error" v-text="error">

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" @click="cerrarModal()">CERRAR</button>
                            <button type="button" v-if="tipoAccion==1" @click="registrarUsuario()" class="btn btn-warning text-white">GUARDAR</button>
                            <button type="button" v-if="tipoAccion==2" @click="actualizarUsuario()" class="btn btn-warning text-white">ACTUALIZAR</button>
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

                user_id: 0,
                nombre: 0,
                nombre_completo : '',
                ultimo_login : '',
                email : '',
                arrayDistribuidora : [],
                distribuidora: {
                    id : 0,
                    nombre : ''
                },
                distribuidoras : [],
                password : '',
                id_rol: 0,
                arrayUsuario : [],
                arrayRol : [],
                modal : 0,
                tituloModal : '',
                tipoAccion: 0,
                errorUsuario : 0,
                errorMostrarMsjUsuario : [],
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

            listarUsuario(page, buscar, criterio){

                let me = this;
                var url = '/usuarios?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayUsuario = respuesta.usuarios.data;
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
                me.listarUsuario(page, buscar, criterio);

            },

            selectRol(){

                let me = this;
                var url = '/roles/selectRol';
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayRol = respuesta.roles;
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            registrarUsuario(){

                if(this.validarUsuario()){

                    return;
                    
                }

                let me = this;

                axios.post('/usuarios/registrar', {

                    'nombre': this.nombre,
                    'nombre_completo' : this.nombre_completo,
                    'email' : this.email,
                    'password' : this.password,
                    'id_rol' : this.id_rol,
                    'distribuidoras' : this.distribuidoras 

                }).then(function (response) {

                    me.cerrarModal();
                    me.listarUsuario(1, '', 'nombre');

                }).catch(function (error) {

                    console.log(error);

                });

            },

            actualizarUsuario(){

                if(this.validarUsuario()){

                    return;
                    
                }

                let me = this;

                axios.put('/usuarios/actualizar', {

                    'id': this.user_id,
                    'nombre': this.nombre,
                    'nombre_completo': this.nombre_completo,
                    'email' : this.email,
                    'password': this.password,
                    'id_rol': this.id_rol,
                    'distribuidoras' : this.distribuidoras

                }).then(function (response) {

                    me.cerrarModal();
                    me.listarUsuario(1, '', 'usuario');

                }).catch(function (error) {

                    console.log(error);

                });

            },

            desactivarUsuario(id){

                const swalWithBootstrapButtons = Swal.mixin({

                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },

                    buttonsStyling: false

                    })

                    swalWithBootstrapButtons.fire({
                    
                        title: 'ESTÁ SEGURO DE DESACTIVAR A ESTE USUARIO?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'SÍ, DESACTÍVALO!',
                        cancelButtonText: 'NO, CANCELAR!',
                        reverseButtons: true

                    }).then((result) => {

                        if (result.value) {

                            let me = this;

                            axios.put('/usuarios/desactivar', {

                                'id': id

                            }).then(function (response) {

                                me.listarUsuario(1, '', 'usuario');
                                swalWithBootstrapButtons.fire(
                                    'DESACTIVADO!',
                                    'EL USUARIO HA SIDO DESACTIVADO.',
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

            activarUsuario(id){

                const swalWithBootstrapButtons = Swal.mixin({

                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },

                    buttonsStyling: false

                    })

                    swalWithBootstrapButtons.fire({
                    
                        title: 'ESTÁ SEGURO DE ACTIVAR A ESTE USUARIO?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'SÍ, ACTÍVALO!',
                        cancelButtonText: 'NO, CANCELAR!',
                        reverseButtons: true

                    }).then((result) => {

                        if (result.value) {

                            let me = this;

                            axios.put('/usuarios/activar', {

                                'id': id

                            }).then(function (response) {

                                me.listarUsuario(1, '', 'usuario');
                                swalWithBootstrapButtons.fire(
                                    'ACTIVADO!',
                                    'EL USUARIO HA SIDO ACTIVADO.',
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

            validarUsuario(){
                
                this.errorUsuario = 0;
                this.errorMostrarMsjUsuario = [];

                if(!this.nombre) this.errorMostrarMsjUsuario.push("EL NOMBRE DE USUARIO NO PUEDE IR VACÍO.");  
                if(!this.nombre_completo) this.errorMostrarMsjUsuario.push("EL NOMBRE COMPLETO DEL USUARIO NO PUEDE IR VACÍO.");
                //if(!this.email) this.errorMostrarMsjUsuario.push("EL EMAIL DE USUARIO NO PUEDE IR VACÍO.");
                if(!this.password && this.user_id == 0) this.errorMostrarMsjUsuario.push("LA CONTRASEÑA NO PUEDE IR VACÍA.");
                if(!this.id_rol) this.errorMostrarMsjUsuario.push("DEBE SELECCIONAR UN ROL PARA EL USUARIO.");
                if(!this.distribuidoras.length) this.errorMostrarMsjUsuario.push("DEBE SELECCIONAR AL MENOS UNA DISTRIBUIDORA PARA EL USUARIO."); 
 
                if(this.errorMostrarMsjUsuario.length) this.errorUsuario = 1;

                return this.errorUsuario;

            },

            validarNombre(nombre, user_id){
                
                let me = this;
                me.errorUsuario = 0;
                me.errorMostrarMsjUsuario = [];
                console.log(user_id);

                if(user_id == 0){

                    var url = '/usuarios/unico/' + nombre;

                    axios.get(url).then(function (response) {

                        var respuesta = response.data;

                        if(respuesta=="no") me.errorMostrarMsjUsuario.push("EL NOMBRE DE USUARIO " + nombre + " YA EXISTE.");

                        if(me.errorMostrarMsjUsuario.length){
                            
                            me.nombre = '';
                            me.errorUsuario = 1;
                            
                        } 

                        return me.errorUsuario;

                    }).catch(function (error) {

                        console.log(error);

                    });
                }else{

                    var url = '/usuarios/unico/' + nombre + '/' + user_id;

                    axios.get(url).then(function (response) {

                        var respuesta = response.data;

                        if(respuesta=="no") me.errorMostrarMsjUsuario.push("EL NOMBRE DE USUARIO " + nombre + " YA EXISTE.");

                        if(me.errorMostrarMsjUsuario.length){
                            
                            me.nombre = '';
                            me.errorUsuario = 1;
                            
                        } 

                        return me.errorUsuario;

                    }).catch(function (error) {

                        console.log(error);

                    });

                }

            },

            validarEmail(email, user_id){
                
                let me = this;
                me.errorUsuario = 0;
                me.errorMostrarMsjUsuario = [];

                if(user_id == 0){

                    var url = '/usuarios/unicoEmail/' + email;

                    axios.get(url).then(function (response) {

                        var respuesta = response.data;

                        if(respuesta=="no") me.errorMostrarMsjUsuario.push("EL EMAIL " + email + " YA HA SIDO REGISTRADO.");

                        if(me.errorMostrarMsjUsuario.length){
                            
                            me.email = '';
                            me.errorUsuario = 1;
                            
                        } 

                        return me.errorUsuario;

                    }).catch(function (error) {

                        console.log(error);

                    });
                }else{

                    var url = '/usuarios/unicoEmail/' + email + '/' + user_id;

                    axios.get(url).then(function (response) {

                        var respuesta = response.data;

                        if(respuesta=="no") me.errorMostrarMsjUsuario.push("EL EMAIL " + email + " YA HA SIDO REGISTRADO.");

                        if(me.errorMostrarMsjUsuario.length){
                            
                            me.email = '';
                            me.errorUsuario = 1;
                            
                        } 

                        return me.errorUsuario;

                    }).catch(function (error) {

                        console.log(error);

                    });

                }

            },

            abrirModal(modelo, accion, data = []){

                this.selectRol();

                switch (modelo) {

                    case 'usuario':
                        
                        {

                            switch (accion) {
                                
                                case 'registrar':
                                    {
                                        this.modal = 1;
                                        this.tituloModal = 'REGISTRAR USUARIO'
                                        this.nombre = '';
                                        this.email = '';
                                        this.nombre_completo = '';
                                        this.password = '';
                                        this.tipoAccion = 1;
                                        break;
                                    }

                                case 'actualizar':
                                    {
                                         //console.log(data);
                                         this.modal = 1;
                                         this.tituloModal = 'ACTUALIZAR USUARIO';
                                         this.tipoAccion = 2;
                                         this.user_id = data['id'];
                                         this.nombre = data['nombre'];
                                         this.nombre_completo = data['nombre_completo'];
                                         this.email = data['email'];
                                         this.password = data['password'];
                                         this.id_rol = data['id_rol'];
                                         this.distribuidoras = data['distribuidoras2'];
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
                this.nombre_completo = '';
                this.email = '';
                this.id_rol = 0;
                this.password = '';
                this.user_id = 0;
                this.distribuidora = {
                    id : 0,
                    nombre : ''
                };
                this.arrayRol = [];
                this.arrayDistribuidora = [];
                this.distribuidoras = [];
                this.errorUsuario = 0;
                this.errorMostrarMsjUsuario = [];
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
                
            }

        },

        mounted() {

            this.listarUsuario(1, this.buscar, this.criterio);

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
        width: 170px;
    }

</style>
