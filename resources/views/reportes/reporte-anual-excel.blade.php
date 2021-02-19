<table>
    <thead>
        <tr>
            <th colspan="43" style="text-align: center;">REPORTE ANUAL DE RENDIMIENTO PROMEDIO MENSUAL</th>
        </tr>
        <tr class="bg-danger">
            <th colspan="7" style="text-align: center;" class="">VEHÍCULO</th>
            <th colspan="3" style="text-align: center;">ENERO</th>
            <th colspan="3" style="text-align: center;">FEBRERO</th>
            <th colspan="3" style="text-align: center;">MARZO</th>
            <th colspan="3" style="text-align: center;">ABRIL</th>
            <th colspan="3" style="text-align: center;">MAYO</th>
            <th colspan="3" style="text-align: center;">JUNIO</th>
            <th colspan="3" style="text-align: center;">JULIO</th>
            <th colspan="3" style="text-align: center;">AGOSTO</th>
            <th colspan="3" style="text-align: center;">SEPTIEMBRE</th>
            <th colspan="3" style="text-align: center;">OCTUBRE</th>
            <th colspan="3" style="text-align: center;">NOVIEMBRE</th>
            <th colspan="3" style="text-align: center;">DICIEMBRE</th>
        </tr>
        <tr class="bg-danger">
            <th style="text-align: center;">PLACA</th>
            <th style="text-align: center;">MODELO</th>
            <th style="text-align: center;">AÑO</th>
            <th style="text-align: center;">KM</th>
            <th style="text-align: center;">CONDUCTOR</th>
            <th style="text-align: center;">RUTA</th>
            <th style="text-align: center;">REND META</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">CST/KM</th>
            <th style="text-align: center;">REND</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">CST/KM</th>
            <th style="text-align: center;">REND</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">CST/KM</th>
            <th style="text-align: center;">REND</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">CST/KM</th>
            <th style="text-align: center;">REND</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">CST/KM</th>
            <th style="text-align: center;">REND</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">CST/KM</th>
            <th style="text-align: center;">REND</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">CST/KM</th>
            <th style="text-align: center;">REND</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">CST/KM</th>
            <th style="text-align: center;">REND</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">CST/KM</th>
            <th style="text-align: center;">REND</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">CST/KM</th>
            <th style="text-align: center;">REND</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">CST/KM</th>
            <th style="text-align: center;">REND</th>
            <th style="text-align: center;">REC</th>
            <th style="text-align: center;">CST/KM</th>
            <th style="text-align: center;">REND</th>
        </tr>
    </thead>
    <tbody>
        @for($i=0; $i<count($reporte); $i++)
            <tr>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["placa"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["modelo"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["anio"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["km"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["conductor"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["ruta"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_meta"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_enero"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm_enero"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_enero"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_febrero"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm_febrero"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_febrero"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_marzo"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm_marzo"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_marzo"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_abril"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm_abril"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_abril"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_mayo"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm_mayo"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_mayo"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_junio"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm_junio"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_junio"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_julio"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm_julio"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_julio"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_agosto"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm_agosto"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_agosto"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_septiembre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm_septiembre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_septiembre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_octubre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm_octubre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_octubre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_noviembre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm_noviembre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_noviembre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rec_diciembre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["costoxkm_diciembre"] }}</td>
                <td class="align-middle" style="text-align: center;">{{ $reporte[$i]["rend_diciembre"] }}</td>
            </tr>
        @endfor
    </tbody>
</table>
        

