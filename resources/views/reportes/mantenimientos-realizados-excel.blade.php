<table>
    <thead>
        <tr>
            <th colspan="4" style="text-align: center;">REPORTE DE CUMPLIMIENTOS {{$distribuidora_nombre}} {{$mes}} {{$anio}}</th>
        </tr>
        <tr class="bg-danger">
            <th colspan="3" style="text-align: center;">MANTENIMIENTOS REALIZADOS ( {{$cant_mtto_realizados}} ) </th>
            <th style="text-align: center;">INDICADOR DE CUMPLIMIENTO ( {{$indicador_cumplimiento}} % )</th>
        </tr>
        <tr class="bg-danger">
            <th style="text-align: center;">NUMERO VEHICULO</th>
            <th style="text-align: center;">PLACA</th>
            <th style="text-align: center;">FECHA</th>
            <th style="text-align: center;">RUTINA</th>
        </tr>
    </thead>
    <tbody>
        @for($i=0; $i<count($reporte); $i++)
            <tr>
                <td>{{$reporte[$i]['numero_vehiculo']}}</td>
                <td>{{$reporte[$i]['placa']}}</td>
                <td>{{$reporte[$i]['fecha']}}</td>
                <td>{{$reporte[$i]['nombre_rutina_anterior']}}</td>
            </tr>
        @endfor
    </tbody>
</table>