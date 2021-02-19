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
            <p style="font-size: 12pt;">BASE DE DATOS</p>
        </header>

        <br><br>

        <main class="main">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr class="bg-danger text-white">
                        <th class="align-middle" style="text-align: center;">No.</th>
                        <th class="align-middle" style="text-align: center;">CÓDIGO PLACA</th>
                        <th class="align-middle" style="text-align: center;">ID DATA (No. AUTORIZACIÓN)</th>
                        <th class="align-middle" style="text-align: center;">COMBUSTIBLE</th>
                        <th class="align-middle" style="text-align: center;">KILOMETRAJE</th>
                        <th class="align-middle" style="text-align: center;">CANTIDAD DE GALONES</th>
                        <th class="align-middle" style="text-align: center;">VALOR</th>
                        <th class="align-middle" style="text-align: center;">RECORRIDO</th>
                        <th class="align-middle" style="text-align: center;">RENDIMIENTO</th>
                        <th class="align-middle" style="text-align: center;">STATUS</th>
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
                            <td class="align-middle" style="text-align: center;">{{ $item["id_data"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["nombre_combustible"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["kilometraje"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["cantidad_galones"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["valor"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["recorrido"] }}</td>
                            <td class="align-middle" style="text-align: center;">{{ $item["rendimiento"] }}</td>
                            @if($item["status"] == "OK" || $item["status"] == "OK - 1")
                                <td class="bg-success align-middle" style="text-align: center;">{{ $item["status"] }}</td>
                            @elseif($item["status"] == "OK - ILOGICO")
                                <td class="bg-warning align-middle" style="text-align: center;">{{ $item["status"] }}</td>
                            @elseif($item["status"] == "SIN LECTURA")
                                <td class="bg-danger align-middle" style="text-align: center;">{{ $item["status"] }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </main>

        <script src="js/plantilla.js"></script>
    </body>
</html>

