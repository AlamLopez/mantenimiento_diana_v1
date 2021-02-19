<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class MantenimientosRealizadosExport implements FromView, ShouldAutoSize, WithTitle, WithEvents
{
    protected $mantenimientos_realizados;
    protected $distribuidora_nombre;
    protected $mes;
    protected $anio;
    protected $indicador_cumplimiento;
    protected $cant_mtto_realizados;

    public function __construct(array $mantenimientos_realizados, $distribuidora_nombre, $mes, $anio, $indicador_cumplimiento, $cant_mtto_realizados)
    {
        $this->mantenimientos_realizados = $mantenimientos_realizados;
        $this->distribuidora_nombre = $distribuidora_nombre;
        $this->mes = $mes;
        $this->anio = $anio;
        $this->indicador_cumplimiento = $indicador_cumplimiento;
        $this->cant_mtto_realizados = $cant_mtto_realizados;
    }

    public function view(): View
    {
        //dd($this);
        return view('reportes.mantenimientos-realizados-excel', [
            'reporte' => $this->mantenimientos_realizados,
            'distribuidora_nombre' => $this->distribuidora_nombre,
            'cant_mtto_realizados' => $this->cant_mtto_realizados,
            'mes' => $this->mes,
            'anio' => $this->anio,
            'indicador_cumplimiento' => $this->indicador_cumplimiento
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        //dd($this->cant_mtto_realizados);
        return $this->cant_mtto_realizados . ' MTTO-REALIZADOS';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event) {
                
                $cellRange = 'A1:D' .((string) count($this->mantenimientos_realizados) + 3); // All headers

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

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray)->getActiveSheet()->setAutoFilter('A3:D3');
                $event->sheet->getDelegate()->getStyle('A1:D3')->getFont()->setSize(14)->setBold(true);
                
            },
        ];
    }

}
