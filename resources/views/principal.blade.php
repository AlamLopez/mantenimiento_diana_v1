<!DOCTYPE html>
<html lang="es">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Modulo de mantenimiento preventivo y rendimiento DIANA">
        <meta name="author" content="Disatel.com">
        <meta name="keyword" content="Modulo de mantenimiento preventivo y rendimiento DIANA">
        <link rel="shortcut icon" href="img/logo-diana.png">
        <title>Módulo de mantenimiento preventivo y rendimiento - DIANA</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="css/plantilla.css" rel="stylesheet">
    </head>

    <body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
        <div id="app">
            
            <header class="app-header navbar">

                <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
            
                <a class="navbar-brand" href="#"></a>
                
                <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
            
                @if(Auth::check())
                    @if(Auth::user()->id_rol == 1)
                        <ul class="nav navbar-nav d-md-down-none">
                            <li @click="menu=17" class="nav-item px-3">
                                <a class="nav-link" href="#">USUARIOS</a>
                            </li>
                            <li @click="menu=18" class="nav-item px-3">
                                <a class="nav-link" href="#">ROLES</a>
                            </li>
                            <li @click="menu=19" class="nav-item px-3">
                                <a class="nav-link" href="#">DISTRIBUIDORAS</a>
                            </li>
                            <li @click="menu=20" class="nav-item px-3">
                                <a class="nav-link" href="#">COMBUSTIBLES</a>
                            </li>
                            <li @click="menu=21" class="nav-item px-3">
                                <a class="nav-link" href="#">TALLERES</a>
                            </li>
                        </ul>
                    @endif
                @endif
            
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="d-md-down-none">{{ Auth::user()->nombre }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-header text-center">
                                <strong>Cuenta</strong>
                            </div>
                            <a class="dropdown-item" href="{{ route('logout') }}" 
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa fa-lock"></i> 
                            Cerrar sesión</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </div>
                    </li>
                </ul>
            
            </header>

            <div class="app-body">
                
                <!-- SideBar -->
                @include('plantilla.sidebar')
                <!-- /SideBar -->

                <!-- Contenido Principal -->
                @yield('contenido')
                <!-- /Fin del contenido principal -->

            </div>

            @include('plantilla.footer')
        
        </div>

        <script src="js/app.js"></script>
        <script src="js/plantilla.js"></script>

        <script>
            $(document).ready(function(){
                
                $('.dropdown-toggle').dropdown();


                $('form').keypress(function(e){   
                    if(e == 13){
                    return false;
                    }
                });

                $('input').keypress(function(e){
                    if(e.which == 13){
                    return false;
                    }
                });
            });
        </script>

    </body>

</html>