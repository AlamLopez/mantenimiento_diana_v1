<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class MantenimientosAtrasadosExport implements FromView, ShouldAutoSize, WithTitle, WithEvents
{
    protected $mantenimientos_atrasados;
    protected $distribuidora_nombre;
    protected $mes;
    protected $anio;
    protected $cant_mtto_atrasados;

    public function __construct(array $mantenimientos_atrasados, $distribuidora_nombre, $mes, $anio, $cant_mtto_atrasados)
    {
        $this->mantenimientos_atrasados = $mantenimientos_atrasados;
        $this->distribuidora_nombre = $distribuidora_nombre;
        $this->mes = $mes;
        $this->anio = $anio;
        $this->cant_mtto_atrasados = $cant_mtto_atrasados;
    }

    public function view(): View
    {
        return view('reportes.mantenimientos-atrasados-excel', [
            'reporte' => $this->mantenimientos_atrasados,
            'distribuidora_nombre' => $this->distribuidora_nombre,
            'cant_mtto_atrasados' => $this->cant_mtto_atrasados,
            'mes' => $this->mes,
            'anio' => $this->anio
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->cant_mtto_atrasados . ' MTTO-ATRASADOS';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event) {
                
                $cellRange = 'A1:K' .((string) count($this->mantenimientos_atrasados) + 4); // All headers

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

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray)->getActiveSheet()->setAutoFilter('A4:K4');
                $event->sheet->getDelegate()->getStyle('A1:K4')->getFont()->setSize(14)->setBold(true);
                
            },
        ];
    }
}
