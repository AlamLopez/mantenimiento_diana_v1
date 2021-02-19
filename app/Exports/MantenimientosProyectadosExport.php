<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class MantenimientosProyectadosExport implements FromView, ShouldAutoSize, WithTitle, WithEvents
{
    protected $mantenimientos_proyectados;
    protected $distribuidora_nombre;
    protected $mes;
    protected $anio;
    protected $cant_mtto_proyectados;

    public function __construct(array $mantenimientos_proyectados, $distribuidora_nombre, $mes, $anio, $cant_mtto_proyectados)
    {
        $this->mantenimientos_proyectados = $mantenimientos_proyectados;
        $this->distribuidora_nombre = $distribuidora_nombre;
        $this->mes = $mes;
        $this->anio = $anio;
        $this->cant_mtto_proyectados = $cant_mtto_proyectados;
    }

    public function view(): View
    {
        return view('reportes.mantenimientos-proyectados-excel', [
            'reporte' => $this->mantenimientos_proyectados,
            'distribuidora_nombre' => $this->distribuidora_nombre,
            'cant_mtto_proyectados' => $this->cant_mtto_proyectados,
            'mes' => $this->mes,
            'anio' => $this->anio
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->cant_mtto_proyectados . ' MTTO-PROYECTADOS';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event) {
                
                $cellRange = 'A1:K' .((string) count($this->mantenimientos_proyectados) + 4); // All headers

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
