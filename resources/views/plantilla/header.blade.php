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
                    <a class="nav-link" href="#">TALLERES</a>
                </li>
                <li @click="menu=20" class="nav-item px-3">
                    <a class="nav-link" href="#">COMBUSTIBLES</a>
                </li>
                <li @click="menu=21" class="nav-item px-3">
                    <a class="nav-link" href="#">EJECUTORES</a>
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