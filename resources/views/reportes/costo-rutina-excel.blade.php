<table>
    <thead>
        <tr>
            <th colspan="7" style="text-align: center;">REPORTE DE COSTOS DE RUTINAS {{$distribuidora_nombre}} {{$mes}} {{$anio}}</th>
        </tr>
        <tr class="bg-danger">
            <th style="text-align: center;">PLACA</th>
            <th style="text-align: center;">MODELO</th>
            <th style="text-align: center;">CONDUCTOR</th>
            <th style="text-align: center;">RUTINA</th>
            <th style="text-align: center;">TALLER</th>
            <th style="text-align: center;">FECHA MAS PROX.</th>
            <th style="text-align: center;">COSTO</th>
        </tr>
    </thead>
    <tbody>
        @for($i=0; $i<count($reporte); $i++)
            <tr>
                <td>{{$reporte[$i]['placa']}}</td>
                <td>{{$reporte[$i]['modelo']}}</td>
                <td>{{$reporte[$i]['conductor']}}</td>
                <td>{{$reporte[$i]['rutina']}}</td>
                <td>{{$reporte[$i]['taller']}}</td>
                <td>{{$reporte[$i]['fecha_mas_prox']}}</td>
                <td>{{$reporte[$i]['costo']}}</td>
            </tr>
        @endfor
    </tbody>
    <tfoot>
        <tr>
            <td colspan="6" style="text-align: center;">TOTAL:</td>
            <td>{{$total}}</td>
        </tr>
    </tfoot>
</table>