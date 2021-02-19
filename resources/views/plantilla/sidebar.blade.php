<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li @click="menu=0" class="nav-item">
                <a class="nav-link active" href="main.html"><i class="fa fa-desktop"></i> ESCRITORIO</a>
            </li>
            <li class="nav-title">
                Rendimiento - Región
            </li>
            <li @click="menu=1" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-truck"></i> Inventario de Flota</a>
            </li>
            <li @click="menu=2" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-database"></i> Base de Datos</a>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-file-excel-o"></i> Reportes</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=3" class="nav-item">
                        <a class="nav-link" href="#"> Reporte Mensual</a>
                    </li>
                    <li @click="menu=4" class="nav-item">
                        <a class="nav-link" href="#"> Reporte Anual</a>
                    </li>
                    <li @click="menu=5" class="nav-item">
                        <a class="nav-link" href="#"> Reporte Recorridos</a>
                    </li>
                    <li @click="menu=6" class="nav-item">
                        <a class="nav-link" href="#"> Reporte de Gráficos</a>
                    </li>
                    <li @click="menu=7" class="nav-item">
                        <a class="nav-link" href="#"> Indicadores Globales</a>
                    </li>
                </ul>
            </li>
            <li class="nav-title">
                Mantenimiento Preventivo
            </li>
            <li @click="menu=8" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-truck"></i> Semáforo</a>
            </li>
            <li @click="menu=9" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-money"></i> Presupuesto</a>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-file-excel-o"></i> Costos</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=10" class="nav-item">
                        <a class="nav-link" href="#"> Costo Rutinas</a>
                    </li>
                    <li @click="menu=22" class="nav-item">
                        <a class="nav-link" href="#"> Costo Repuestos</a>
                    </li>
                </ul>
            </li>
            <li class="nav-item nav-dropdown">
                <a class="nav-link nav-dropdown-toggle" href="#"><i class="fa fa-file-excel-o"></i> Reportes</a>
                <ul class="nav-dropdown-items">
                    <li @click="menu=11" class="nav-item">
                        <a class="nav-link" href="#"> Reporte de Cumplimiento</a>
                    </li>
                    <!--li @click="menu=12" class="nav-item">
                        <a class="nav-link" href="#"> Hoja de Rutina</a>
                    </li-->
                </ul>
            </li>
            <li @click="menu=13" class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-bars"></i> Mantenimientos</a>
            </li>
            <li class="nav-title">
                Anexo
            </li>
            @if(Auth::check())
                @if(Auth::user()->id_rol == 1)
                    <li @click="menu=14" class="nav-item">
                        <a class="nav-link" href="#"><i class="fa fa-history"></i> Historial</a>
                    </li>
                @endif
            @endif
                <!--li @click="menu=15" class="nav-item">
                <a class="nav-link" href="main.html"><i class="fa fa-book"></i> Ayuda <span class="badge badge-danger">PDF</span></a>
            </li>
            <li @click="menu=16" class="nav-item">
                <a class="nav-link" href="main.html"><i class="fa fa-info-circle"></i> Acerca de...<span class="badge badge-info">IT</span></a>
            </li-->
        </ul>
    </nav>
    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>