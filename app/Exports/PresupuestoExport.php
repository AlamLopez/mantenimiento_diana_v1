<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class PresupuestoExport implements WithMultipleSheets
{

    use Exportable;

    protected $reporte_rutinas;
    protected $reporte_repuestos;
    protected $total_reporte_rutinas;
    protected $total_reporte_repuestos;
    protected $distribuidora_nombre;
    protected $mes;
    protected $anio;

    public function __construct(array $reporte_rutinas, array $reporte_repuestos, $total_reporte_rutinas, $total_reporte_repuestos, $distribuidora_nombre, $mes, $anio)
    {
        $this->reporte_rutinas = $reporte_rutinas;
        $this->reporte_repuestos = $reporte_repuestos;
        $this->total_reporte_rutinas = $total_reporte_rutinas;
        $this->total_reporte_repuestos = $total_reporte_repuestos;
        $this->distribuidora_nombre = $distribuidora_nombre;
        $this->mes = $mes;
        $this->anio = $anio;
        
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [
            new ReporteRutinasExport($this->reporte_rutinas, $this->total_reporte_rutinas, $this->distribuidora_nombre, $this->mes, $this->anio),
            new ReporteRepuestosExport($this->reporte_repuestos, $this->total_reporte_repuestos, $this->distribuidora_nombre, $this->mes, $this->anio),
        ];

        return $sheets;
    }
}
