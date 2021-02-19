<table>
    <thead>
        <tr>
            <th colspan="18" style="text-align: center;">REPORTE DE RECORRIDOS MENSUALES</th>
        </tr>
        <tr class="bg-danger">
            <th style="text-align: center;">PLACA</th>
            <th style="text-align: center;">MARCA</th>
            <th style="text-align: center;">MODELO</th>
            <th style="text-align: center;">AÑO</th>
            <th style="text-align: center;">KM</th>
            <th style="text-align: center;">DISTRIBUIDORA</th>
            <th style="text-align: center;">ENERO</th>
            <th style="text-align: center;">FEBRERO</th>
            <th style="text-align: center;">MARZO</th>
            <th style="text-align: center;">ABRIL</th>
            <th style="text-align: center;">MAYO</th>
            <th style="text-align: center;">JUNIO</th>
            <th style="text-align: center;">JULIO</th>
            <th style="text-align: center;">AGOSTO</th>
            <th style="text-align: center;">SEPTIEMBRE</th>
            <th style="text-align: center;">OCTUBRE</th>
            <th style="text-align: center;">NOVIEMBRE</th>
            <th style="text-align: center;">DICIEMBRE</th>
        </tr>
    </thead>
    <tbody>
        @for($i=0; $i<count($reporte); $i++)
            <tr>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["placa"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["marca"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["modelo"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["anio"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["km"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["distribuidora_nombre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_enero"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_febrero"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_marzo"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_abril"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_mayo"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_junio"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_julio"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_agosto"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_septiembre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_octubre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_noviembre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_diciembre"] }}</td>
            </tr>
        @endfor
    </tbody>
</table>