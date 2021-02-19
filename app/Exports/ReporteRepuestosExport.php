<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReporteRepuestosExport implements FromView, ShouldAutoSize, WithTitle, WithEvents
{
    protected $reporte_repuestos;
    protected $total_reporte_repuestos;
    protected $distribuidora_nombre;
    protected $mes;
    protected $anio;

    public function __construct(array $reporte_repuestos, $total_reporte_repuestos, $distribuidora_nombre, $mes, $anio)
    {
        $this->reporte_repuestos = $reporte_repuestos;
        $this->total_reporte_repuestos = $total_reporte_repuestos;
        $this->distribuidora_nombre = $distribuidora_nombre;
        $this->mes = $mes;
        $this->anio = $anio;
    }

    public function view(): View
    {
        return view('reportes.costo-repuesto-excel', [
            'reporte' => $this->reporte_repuestos,
            'total' => $this->total_reporte_repuestos,
            'distribuidora_nombre' => $this->distribuidora_nombre,
            'mes' => $this->mes,
            'anio' => $this->anio
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'REPORTE DE COSTOS DE REPUESTOS';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event) {
                
                $cellRange = 'A1:F' .((string) count($this->reporte_repuestos) + 3); // All headers

                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],

                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
    
                    ],
                ];

                $styleArray2 = [

                    'font' => [
                        'bold' => true,
                    ],
                    
                ];

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray)->getActiveSheet()->setAutoFilter('A2:F2');
                $event->sheet->getDelegate()->getStyle('A1:F2')->getFont()->setSize(14)->setBold(true);
                
            },
        ];
    }

}
