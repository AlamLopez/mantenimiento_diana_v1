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
                    <i class="fa fa-align-justify"></i> <strong>HISTORIAL DE MANTENIMIENTOS</strong>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-group-addon bg-danger">PLACA</div>
                                <input type="text" id="texto" v-model="buscar" @keyup="listarMantenimiento(1, buscar, criterio, desde, hasta, desde_km, hasta_km, select_rutina)" class="form-control" placeholder="TEXTO A BUSCAR...">
                            </div>
                        </div>
                        <div class="col-md-8">  
                            <div class="input-group input-daterange">
                                <div class="input-group-addon bg-danger">DESDE</div>
                                <input type="date" v-model="desde" @change="cambiarHasta()" :max="fechaMaxima" class="form-control">
                                <div class="input-group-addon bg-danger">HASTA</div>
                                <input type="date" v-model="hasta" @change="cambiarDesde()" :max="fechaActual" :min="desde" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <div class="input-group input-daterange">
                                <div class="input-group-addon bg-danger">RUTINA</div>
                                <select class="form-control col-md-10" v-model="select_rutina" @change="listarMantenimiento(1, buscar, criterio, desde, hasta, desde_km, hasta_km, select_rutina)">
                                    <option value="">TODOS</option>
                                    <option value="A1">A1</option>
                                    <option value="A2">A2</option>
                                    <option value="A3">A3</option>
                                    <option value="B">B</option>
                                    <option value="A4">A4</option>
                                    <option value="A5">A5</option>
                                    <option value="C">C</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="input-group input-daterange">
                                <div class="input-group-addon bg-danger">DESDE</div>
                                <input type="number" v-model="desde_km" @change="cambiarKmHasta()" :min="minimo_km_desde" :max="maximo_km_desde" class="form-control" placeholder="KILOMETRAJE">
                                <div class="input-group-addon bg-danger">HASTA</div>
                                <input type="number" v-model="hasta_km" @change="cambiarKmDesde()" :min="minimo_km_hasta" class="form-control" placeholder="KILOMETRAJE">
                            </div>
                        </div>
                    </div>
                    <table class="table table-responsive table-bordered table-sm">
                        <thead>
                            <tr class="bg-danger">
                                <th style="text-align: center;">PLACA</th>
                                <th style="text-align: center;">FECHA</th>
                                <th style="text-align: center;">KILOMETRAJE</th>
                                <th style="text-align: center;">RUTINA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="mantenimiento in arrayMantenimiento" :key="mantenimiento.id">
                                <td style="text-align: center;" v-text="mantenimiento.placa"></td>
                                <td style="text-align: center;" v-text="mantenimiento.fecha"></td>
                                <td style="text-align: center;" v-text="mantenimiento.kilometraje"></td>
                                <td style="text-align: center;" v-text="mantenimiento.nombre_rutina_anterior"></td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <span class="page-stats">Del {{pagination.from}} al {{pagination.to}} de un total de  {{pagination.total}} registros.</span>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page - 1, buscar, criterio, desde, hasta, desde_km, hasta_km, select_rutina)">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(page, buscar, criterio, desde, hasta, desde_km, hasta_km, select_rutina)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="cambiarPagina(pagination.current_page + 1, buscar, criterio, desde, hasta, desde_km, hasta_km, select_rutina)">Sig</a>
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
    export default {

        data (){

            return {

                arrayMantenimiento : [],
                mantenimiento_id : 0,
                placa : '',
                fecha : '',
                kilometraje : '',
                rutina : '',
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
                criterio : 'placa',
                buscar : '',
                desde_km : '',
                hasta_km : '',
                desde : '',
                hasta : '',
                select_rutina : '',
                fechaMaxima : '',
                minimo_km_hasta : '',
                fechaActual : '',
                maximo_km_desde : '',
                minimo_km_desde : ''

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

            listarMantenimiento(page, buscar, criterio, desde, hasta, desde_km, hasta_km, select_rutina){

                let me = this;
                var url = '/mantenimientos?page=' + page + '&buscar=' + buscar + '&criterio=' + criterio + '&desde=' + desde + '&hasta=' + hasta + '&desde_km=' + desde_km + '&hasta_km=' + hasta_km + '&select_rutina=' + select_rutina;
                
                axios.get(url).then(function (response) {
                    var respuesta = response.data;
                    me.arrayMantenimiento = respuesta.mantenimientos.data;
                    me.pagination = respuesta.pagination;
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            },

            cambiarPagina(page, buscar, criterio, desde, hasta, desde_km, hasta_km, select_rutina){
                
                let me = this;

                me.pagination.current_page = page;
                me.listarMantenimiento(page, buscar, criterio, desde, hasta, desde_km, hasta_km, select_rutina);

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

                this.listarMantenimiento(1, this.buscar, this.criterio, this.desde, this.hasta, this.desde_km, this.hasta_km, this.select_rutina);
                
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

                this.listarMantenimiento(1, this.buscar, this.criterio, this.desde, this.hasta, this.desde_km, this.hasta_km, this.select_rutina);
                
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

                this.listarMantenimiento(1, this.buscar, this.criterio, this.desde, this.hasta, this.desde_km, this.hasta_km, this.select_rutina);
                
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

                this.listarMantenimiento(1, this.buscar, this.criterio, this.desde, this.hasta, this.desde_km, this.hasta_km, this.select_rutina);                
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

            this.listarMantenimiento(1, this.buscar, this.criterio, this.desde, this.hasta, this.desde_km, this.hasta_km, this.select_rutina);
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
