<table>
    <thead>
        <tr>
            <th colspan="6" style="text-align: center;">REPORTE DE COSTOS DE REPUESTO {{$distribuidora_nombre}} {{$mes}} {{$anio}}</th>
        </tr>
        <tr class="bg-danger">
            <th style="text-align: center;">MATERIAL</th>
            <th style="text-align: center;">REPUESTO</th>
            <th style="text-align: center;">DESCRIPCIÓN</th>
            <th style="text-align: center;">MODELO</th>
            <th style="text-align: center;">CANTIDAD</th>
            <th style="text-align: center;">COSTO</th>
        </tr>
    </thead>
    <tbody>
        @for($i=0; $i<count($reporte); $i++)
            <tr>
                <td>{{$reporte[$i]['material']}}</td>
                <td>{{$reporte[$i]['repuesto']}}</td>
                <td>{{$reporte[$i]['descripcion']}}</td>
                <td>{{$reporte[$i]['modelo']}}</td>
                <td>{{$reporte[$i]['cantidad']}}</td>
                <td>{{$reporte[$i]['costo']}}</td>
            </tr>
        @endfor
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5" style="text-align: center;">TOTAL:</td>
            <td>{{$total}}</td>
        </tr>
    </tfoot>
</table>