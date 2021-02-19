<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReporteRutinasExport implements FromView, ShouldAutoSize, WithTitle, WithEvents
{

    protected $reporte_rutinas;
    protected $total_reporte_rutinas;
    protected $distribuidora_nombre;
    protected $mes;
    protected $anio;

    public function __construct(array $reporte_rutinas, $total_reporte_rutinas, $distribuidora_nombre, $mes, $anio)
    {
        $this->reporte_rutinas = $reporte_rutinas;
        $this->total_reporte_rutinas = $total_reporte_rutinas;
        $this->distribuidora_nombre = $distribuidora_nombre;
        $this->mes = $mes;
        $this->anio = $anio;
    }

    public function view(): View
    {
        return view('reportes.costo-rutina-excel', [
            'reporte' => $this->reporte_rutinas,
            'total' => $this->total_reporte_rutinas,
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
        return 'REPORTE DE COSTOS DE RUTINAS';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event) {
                
                $cellRange = 'A1:G' .((string) count($this->reporte_rutinas) + 3); // All headers

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

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray)->getActiveSheet()->setAutoFilter('A2:G2');
                $event->sheet->getDelegate()->getStyle('A1:G2')->getFont()->setSize(14)->setBold(true);
                
            },
        ];
    }
}
