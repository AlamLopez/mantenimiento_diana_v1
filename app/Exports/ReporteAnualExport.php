<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReporteAnualExport implements FromView, ShouldAutoSize, WithEvents, WithTitle
{

    protected $reporte_anual;
    protected $distribuidora_nombre;
    protected $anio;

    public function __construct(array $reporte_anual, $distribuidora_nombre, $anio)
    {
        $this->reporte_anual = $reporte_anual;
        $this->distribuidora_nombre = $distribuidora_nombre;
        $this->anio = $anio;
    }

    public function view(): View
    {
        return view('reportes.reporte-anual-excel', [
            'reporte' => $this->reporte_anual
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'REPORTE-ANUAL-' . $this->distribuidora_nombre . '-' . $this->anio;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event) {
                
                $cellRange = 'A1:AQ' .((string) count($this->reporte_anual) + 3); // All headers

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

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray)->getActiveSheet()->setAutoFilter('A3:AQ3');
                $event->sheet->getDelegate()->getStyle('A1:AQ3')->getFont()->setSize(14)->setBold(true);
                
            },
        ];
    }

}
