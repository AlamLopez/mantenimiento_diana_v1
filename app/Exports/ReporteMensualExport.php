<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReporteMensualExport implements FromView, ShouldAutoSize, WithEvents, WithTitle
{

    protected $reporte_mensual;
    protected $distribuidora_nombre;
    protected $mes;
    protected $anio;
    protected $total_recorridos;
    protected $total_galones;
    protected $total_rend;
    protected $total_costo;
    protected $total_costoxkm;

    public function __construct(array $reporte_mensual, $distribuidora_nombre, $mes, $anio, $total_recorridos, $total_galones, $total_costo, $total_costoxkm, $total_rend)
    {
        $this->reporte_mensual = $reporte_mensual;
        $this->distribuidora_nombre = $distribuidora_nombre;
        $this->mes = $mes;
        $this->anio = $anio;
        $this->total_recorridos = $total_recorridos;
        $this->total_galones = $total_galones;
        $this->total_costo = $total_costo;
        $this->total_costoxkm = $total_costoxkm;
        $this->total_rend = $total_rend;
    }

    public function view(): View
    {

        return view('reportes.reporte-mensual-excel', [
            'reporte' => $this->reporte_mensual,
            'total_recorridos' => $this->total_recorridos,
            'total_galones' => $this->total_galones,
            'total_costo' => $this->total_costo,
            'total_costoxkm' => $this->total_costoxkm,
            'total_rend' => $this->total_rend
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'REPORTE-MENSUAL-' . $this->mes . $this->anio;
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event) {
                
                $cellRange = 'A1:S' .((string) count($this->reporte_mensual) + 3); // All headers

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

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray)->getActiveSheet()->setAutoFilter('A2:S2');

                $event->sheet->getDelegate()->getStyle('A1:S2')->getFont()->setSize(14)->setBold(true);
                
            },
        ];
    }

}
