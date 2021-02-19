<html>
    <head>
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
        <style>
            @page {
                font-family: Arial;
            }
        
            body {
                font-family: Arial;
                font-size: 8pt;
            }
        </style>
    </head>
    <body>

        <header style="height: 100px;">        
            <img src="img/logo-diana.png" class="h-75 d-inline-block">
            <p style="font-size: 12pt;">INVENTARIO DE FLOTA</p>
        </header>

        <br><br>

        <main class="main">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr class="bg-danger text-white">
                        <th class="align-middle" style="text-align: center;">No.</th>
                        <th class="align-middle" style="text-align: center;">CÓDIGO COMB.</th>
                        <th class="align-middle" style="text-align: center;">PLACA</th>
                        <th class="align-middle" style="text-align: center;">MARCA</th>
                        <th class="align-middle" style="text-align: center;">MODELO</th>
                        <th class="align-middle" style="text-align: center;">AÑO</th>
                        <th class="align-middle" style="text-align: center;">DISTRIB.</th>
                        <th class="align-middle" style="text-align: center;">CONDUCTOR</th>
                        <th class="align-middle" style="text-align: center;">FECHA ULTO KM</th>
                        <th class="align-middle" style="text-align: center;">KM</th>
                        <th class="align-middle" style="text-align: center;">ESTADO</th>
                        <th class="align-middle" style="text-align: center;">TALLER</th>
                        <th class="align-middle" style="text-align: center;">PERIODO MTTO KMS</th>
                        <th class="align-middle" style="text-align: center;">PERIODO MTTO MESES</th>
                        <th class="align-middle" style="text-align: center;">RUTA</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=0
                    @endphp
                    @foreach($data as $item)
                        @php $i ++ @endphp
                        <tr>
                            <td class="align-middle" style="text-align: center;">{{ $i }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["codigo_comb"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["placa"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["marca"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["modelo"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["anio"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["nombre_distribuidora"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["conductor"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["ulto_km"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["km"] }}</td>
                            <td class="align-middle" style="text-align: center;">
                                @if($item["condicion"])
                                    <span>ACTIVO</span>
                                @else
                                    <span>INACTIVO</span>
                                @endif
                            </td>
                            <td class="align-middle" style="text-align: center;">{{ $item["nombre_taller"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["periodo_mtto_kms"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["periodo_mtto_meses"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["ruta"] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </main>

        <script src="js/plantilla.js"></script>
    </body>
</html>

