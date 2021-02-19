<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReporteRecorridoExport implements FromView, ShouldAutoSize, WithEvents, WithTitle
{
    protected $reporte_recorrido;
    protected $anio;

    public function __construct(array $reporte_recorrido, $anio)
    {
        $this->reporte_recorrido = $reporte_recorrido;
        $this->anio = $anio;
    }

    public function view(): View
    {
        return view('reportes.reporte-recorrido-excel', [
            'reporte' => $this->reporte_recorrido
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'REPORTE-RECORRIDOS-' . $this->anio;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event) {
                
                $cellRange = 'A1:R' .((string) count($this->reporte_recorrido) + 2); // All headers

                //dd($cellRange);

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

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray)->getActiveSheet()->setAutoFilter('A2:R2');
                $event->sheet->getDelegate()->getStyle('A1:R2')->getFont()->setSize(14)->setBold(true);
                
            },
        ];
    }
}
