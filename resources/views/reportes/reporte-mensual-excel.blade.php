<table class="table table-responsive table-bordered table-striped table-sm">
    <thead>
        <tr>
            <th colspan="18" style="text-align: center;">PROMEDIO MENSUAL DE RENDIMIENTO</th>
        </tr>
        <tr class="bg-danger">
            <th style="text-align: center;">PLACA</th>
            <th style="text-align: center;">MARCA</th>
            <th style="text-align: center;">MODELO</th>
            <th style="text-align: center;">AÑO</th>
            <th style="text-align: center;">CONDUCTOR</th>
            <th style="text-align: center;">RUTA</th>
            <th style="text-align: center;">FECHA 1</th>
            <th style="text-align: center;">FECHA 2</th>
            <th style="text-align: center;">KM 1</th>
            <th style="text-align: center;">KM 2</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">GAL</th>
            <th style="text-align: center;">COSTO</th>
            <th style="text-align: center;">REND</th>
            <th style="text-align: center;">COSTO/KM</th>
            <th style="text-align: center;">VALIDOS</th>
            <th style="text-align: center;">INVALIDOS</th>
            <th style="text-align: center;">STATUS</th>
        </tr>
    </thead>
    <tbody>
        @for($i=0; $i<count($reporte); $i++)
            <tr>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["placa"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["marca"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["modelo"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["anio"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["conductor"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["ruta"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["fecha1"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["fecha2"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["km1"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["km2"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["gal"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costo"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["validos"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["invalidos"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["status"] }}</td>
            </tr>
        @endfor
    </tbody>
    <tfoot>
        <tr>
            <td style="text-align: center;" class="align-middle" colspan="10">GLOBAL:</td>
            <td style="text-align: center;" class="align-middle">{{ $total_recorridos }}</td>
            <td style="text-align: center;" class="align-middle">{{ $total_galones }}</td>
            <td style="text-align: center;" class="align-middle">{{ $total_costo }}</td>
            <td style="text-align: center;" class="align-middle">{{ $total_rend }}</td>
            <td style="text-align: center;" class="align-middle">{{ $total_costoxkm }}</td>
            <td colspan="3"></td>
        </tr>
    </tfoot>
</table>