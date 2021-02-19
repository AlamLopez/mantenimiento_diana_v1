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
                        <i class="fa fa-align-justify"></i><strong>COSTOS PLANES PREVENTIVOS</strong>
                    </div>
                    <div class="card-body">
                        <div class="col-md-12 mb-4">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#home4" role="tab" aria-controls="home">
                                        <i class="icon-calculator"></i> COSTOS DE RUTINAS
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#profile4" role="tab" aria-controls="profile">
                                        <i class="icon-basket-loaded"></i> REPUESTOS
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="home4" role="tabpanel">
                                    <table class="table table-responsive table-bordered table-striped table-sm">
                                        <thead>
                                            <tr class="bg-danger">
                                                <th style="text-align: center;" class="align-middle">OPCIONES</th>
                                                <th style="text-align: center;" class="align-middle">MODELO</th>
                                                <th style="text-align: center" class="align-middle">RUTINA</th>
                                                <th style="text-align: center;" class="align-middle">COSTO</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                <div class="tab-pane" id="profile4" role="tabpanel">
                                    <table class="table table-responsive table-bordered table-striped table-sm">
                                        <thead>
                                            <tr class="bg-danger">
                                                <th style="text-align: center;" class="align-middle">OPCIONES</th>
                                                <th style="text-align: center;" class="align-middle">MODELO</th>
                                                <th style="text-align: center" class="align-middle">RUTINAS</th>
                                                <th style="text-align: center" class="align-middle">MATERIAL</th>
                                                <th style="text-align: center" class="align-middle">REPUESTO</th>
                                                <th style="text-align: center" class="align-middle">DESCRIPCIÓN</th>
                                                <th style="text-align: center" class="align-middle">CANTIDAD</th>
                                                <th style="text-align: center;" class="align-middle">COSTO</th> 
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col-->
                        </div>
                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
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

                pagination : {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset : 3,

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

        },

        mounted() {


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
