<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReporteCumplimientosExport implements WithMultipleSheets
{
    use Exportable;

    protected $mantenimientos_realizados;
    protected $mantenimientos_proyectados;
    protected $mantenimientos_atrasados;
    protected $distribuidora_nombre;
    protected $mes;
    protected $anio;
    protected $indicador_cumplimiento;
    protected $cant_mtto_realizados;
    protected $cant_mtto_proyectados;
    protected $cant_mtto_atrasados;

    public function __construct(array $mantenimientos_realizados, array $mantenimientos_proyectados, array $mantenimientos_atrasados, $distribuidora_nombre, $mes, $anio, $indicador_cumplimiento, $cant_mtto_realizados, $cant_mtto_proyectados, $cant_mtto_atrasados)
    {
        $this->mantenimientos_realizados = $mantenimientos_realizados;
        $this->mantenimientos_proyectados = $mantenimientos_proyectados;
        $this->mantenimientos_atrasados = $mantenimientos_atrasados;
        $this->distribuidora_nombre = $distribuidora_nombre;
        $this->mes = $mes;
        $this->anio = $anio;
        $this->indicador_cumplimiento = $indicador_cumplimiento;
        $this->cant_mtto_realizados = $cant_mtto_realizados;
        $this->cant_mtto_proyectados = $cant_mtto_proyectados;
        $this->cant_mtto_atrasados = $cant_mtto_atrasados;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new MantenimientosRealizadosExport($this->mantenimientos_realizados, $this->distribuidora_nombre, $this->mes, $this->anio, $this->indicador_cumplimiento, $this->cant_mtto_realizados),
            new MantenimientosProyectadosExport($this->mantenimientos_proyectados, $this->distribuidora_nombre, $this->mes, $this->anio, $this->cant_mtto_proyectados),
            new MantenimientosAtrasadosExport($this->mantenimientos_atrasados, $this->distribuidora_nombre, $this->mes, $this->anio, $this->cant_mtto_atrasados)
        ];

        return $sheets;
    }
}
