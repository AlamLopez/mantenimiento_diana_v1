<table>
    <thead>
        <tr>
            <th colspan="11" style="text-align: center;">REPORTE DE CUMPLIMIENTOS {{$distribuidora_nombre}} {{$mes}} {{$anio}}</th>
        </tr>
        <tr class="bg-danger">
            <th colspan="11" style="text-align: center;">MANTENIMIENTOS ATRASADOS ( {{$cant_mtto_atrasados}} ) </th>
        </tr>
        <tr>
            <td colspan="7" style="text-align: center;"></td>
            <td colspan="2" style="text-align: center;">ULTO MTTO</td>
            <td colspan="2" style="text-align: center;"></td>

        </tr>
        <tr class="bg-danger">
            <th style="text-align: center;">NUMERO VEHICULO</th>
            <th style="text-align: center;">PLACA</th>
            <th style="text-align: center;">CONDUCTOR</th>
            <th style="text-align: center;">TALLER</th>
            <th style="text-align: center;">PROYECCIÓN</th>
            <th style="text-align: center;">RUTINA</th>
            <th style="text-align: center;">CRITERIO CUMPLIMIENTO</th>
            <th style="text-align: center;">FECHA</th>
            <th style="text-align: center;">KILOMETRAJE</th>
            <th style="text-align: center;">KILOMETRAJE ACTUAL</th>
            <th style="text-align: center;">CRITICIDAD</th>
        </tr>
    </thead>
    <tbody>
        @for($i=0; $i<count($reporte); $i++)
            <tr>
                <td>{{$reporte[$i]['numero_vehiculo']}}</td>
                <td>{{$reporte[$i]['placa']}}</td>
                <td>{{$reporte[$i]['conductor']}}</td>
                <td>{{$reporte[$i]['taller']}}</td>
                <td>{{$reporte[$i]['proyeccion']}}</td>
                <td>{{$reporte[$i]['rutina']}}</td>
                <td>{{$reporte[$i]['criterio_cumpl']}}</td>
                <td>{{$reporte[$i]['fecha_mtto']}}</td>
                <td>{{$reporte[$i]['km_mtto']}}</td>
                <td>{{$reporte[$i]['km_actual']}}</td>
                <td>{{$reporte[$i]['criticidad']}}</td>
            </tr>
        @endfor
    </tbody>
</table>