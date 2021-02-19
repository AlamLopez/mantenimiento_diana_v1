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
                    <i class="fa fa-align-justify"></i> <strong>REGISTRO DE ACTIVIDADES DEL SISTEMA</strong>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-5" v-model="criterio">
                                    <option value="usuario">USUARIO</option>
                                    <option value="nombre_accion">ACCIÓN</option>
                                    <option value="descripcion">DESCRIPCIÓN</option>
                                </select>
                                <input type="text" v-model="buscar" @keyup="listarHistorial(1, buscar, criterio, desde, hasta)" class="form-control" placeholder="TEXTO A BUSCAR">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-daterange">
                                <div class="input-group-addon">DESDE</div>
                                <input type="date" v-model="desde" @change="cambiarHasta()" :max="fechaMaxima" class="form-control">
                                <div class="input-group-addon">HASTA</div>
                                <input type="date" v-model="hasta" @change="cambiarDesde()" :max="fechaActual" :min="desde" class="form-control">
                            </div>
                        </div>
                    </div>
                    <table class="table table-responsive table-bordered table-sm">
                        <thead>
                            <tr class="bg-danger">
                                <th style="text-align: center;">USUARIO</th>
                                <th style="text-align: center;">ACCIÓN</th>
                                <th style="text-align: center;">DESCRIPCIÓN</th>
                                <th style="text-align: center;">FECHA Y HORA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="historial in arrayHistorial" :key="historial.id">
                                <td style="text-align: center;" v-text="historial.usuario"></td>
                                <td style="text-align: center;" v-text="historial.nombre_accion"></td>
                                <td style="text-align: center;" v-text="historial.descripcion"></td>
                                <td style="text-align: center;" v-text="historial.fecha"></td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <span class="page-stats">Del {{pagination.from}} al {{pagination.to}} de un total de  {{pagination.total}} registros.</span>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio, desde, hasta)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio, desde, hasta)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio, desde, hasta)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
    </main>
</template>

<script>

    import DatePicker from 'vue2-datepicker';

    export default {

        components: {
            
            DatePicker

        },

        data (){

            return {

                arrayHistorial : [],
                fechaActual : '',
                fechaMaxima : '',
                desde : '',
                hasta : '',
                nombre_accion : '',
                descripcion : '',
                user_id : 0,
                created_at : '',
                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,
                criterio : 'nombre_accion',
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

            listarHistorial(page, buscar, criterio, desde, hasta){

                let me = this;
                var url = '/historial?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&desde=' + desde + '&hasta=' + hasta;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayHistorial = respuesta.historiales.data;
                    me.pagination = respuesta.pagination;
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            cambiarPagina(page, buscar, criterio, desde, hasta){
                
                let me = this;

                me.pagination.current_page = page;
                me.listarHistorial(page, buscar, criterio, desde, hasta);

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

                this.listarHistorial(1, this.buscar, this.criterio, this.desde, this.hasta);
                
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

                this.listarHistorial(1, this.buscar, this.criterio, this.desde, this.hasta);
                
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

            }
        
        },

        mounted() {

            this.listarHistorial(1, this.buscar, this.criterio, this.desde, this.hasta);

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

</style>
