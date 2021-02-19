<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item">HOME</li>
            <li class="breadcrumb-item"><a href="#">ADMIN</a></li>
            <li class="breadcrumb-item active">DASHBOARD</li>
        </ol>
        <div class="container-fluid">
            <div class="container-fluid">
                <div class="animated fadeIn">
                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body pb-0">
                                    <div class="text-value display-4" v-text="cantidadCombustibles"></div>
                                    <div>COMBUSTIBLES</div>
                                </div>
                                <div class="chart-wrapper mt-3 mx-3" style="height:40px;">
                                    <canvas class="chart" id="card-chart1" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card text-white bg-success">
                                <div class="card-body pb-0">
                                    <div class="text-value display-4" v-text="cantidadVehiculos"></div>
                                    <div>VEHÍCULOS</div>
                                </div>
                                <div class="chart-wrapper mt-3 mx-3" style="height:40px;">
                                    <canvas class="chart" id="card-chart2" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body pb-0">
                                    <div class="text-value display-4" v-text="cantidadTalleres"></div>
                                    <div>TALLERES</div>
                                </div>
                                <div class="chart-wrapper mt-3" style="height:40px;">
                                    <canvas class="chart" id="card-chart3" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card text-white bg-danger">
                            <div class="card-body pb-0">
                                <div class="text-value display-4" v-text="cantidadDistribuidoras"></div>
                                <div>DISTRIBUIDORAS</div>
                            </div>
                            <div class="chart-wrapper mt-3 mx-3" style="height:40px;">
                                <canvas class="chart" id="card-chart4" height="40"></canvas>
                            </div>
                            </div>
                        </div>
                        <!-- /.col-->
                    </div>
                    <!-- /.row-->
                    <div class="row">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body pb-0">
                                    <div class="text-value display-4" v-text="cantidadRendimientos"></div>
                                    <div>RENDIMIENTOS</div>
                                </div>
                                <div class="chart-wrapper mt-3 mx-3" style="height:40px;">
                                    <canvas class="chart" id="card-chart1" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card text-white bg-success">
                                <div class="card-body pb-0">
                                    <div class="text-value display-4" v-text="cantidadUsuarios"></div>
                                    <div>USUARIOS</div>
                                </div>
                                <div class="chart-wrapper mt-3 mx-3" style="height:40px;">
                                    <canvas class="chart" id="card-chart2" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                        <div class="col-sm-6 col-lg-3">
                            <div class="card text-white bg-warning">
                                <div class="card-body pb-0">
                                    <div class="text-value display-4" v-text="cantidadMantenimientos"></div>
                                    <div>MANTENIMIENTOS</div>
                                </div>
                                <div class="chart-wrapper mt-3" style="height:40px;">
                                    <canvas class="chart" id="card-chart3" height="40"></canvas>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-->
                    </div>
                    <!-- /.row-->
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    export default {

        data (){

            return {

                cantidadCombustibles : 0,
                cantidadVehiculos : 0,
                cantidadTalleres : 0,
                cantidadDistribuidoras : 0,
                cantidadRendimientos : 0,
                cantidadUsuarios : 0,
                cantidadMantenimientos: 0,

            }

        },

        methods : {

            contar(){

                let me = this;
                var url = '/conteo';
                
                axios.get(url).then(function (response) {
                    
                    var respuesta = response.data;

                    me.cantidadCombustibles = respuesta.cantidadCombustibles;
                    me.cantidadVehiculos = respuesta.cantidadVehiculos;
                    me.cantidadTalleres = respuesta.cantidadTalleres;
                    me.cantidadDistribuidoras = respuesta.cantidadDistribuidoras;
                    me.cantidadRendimientos = respuesta.cantidadRendimientos;
                    me.cantidadUsuarios = respuesta.cantidadUsuarios;
                    me.cantidadMantenimientos = respuesta.cantidadMantenimientos;
                    
                    console.log(response);

                })
                .catch(function (error) {
                    
                    console.log(error);

                })

            }

        },

        mounted() {

            this.contar();

        }

    }
</script>