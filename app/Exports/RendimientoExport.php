<?php

namespace App\Exports;

use App\Rendimiento;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class RendimientoExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user_distribuidoras = explode(',', Auth::user()->distribuidoras);
        
        return Rendimiento::select('rendimientos.fecha', 'vehiculos.codigo_comb as vehiculo', 'rendimientos.id_data',
                                   'combustibles.nombre as combustible', 'rendimientos.kilometraje',
                                   'rendimientos.cantidad_galones', 'rendimientos.valor', 'rendimientos.recorrido',
                                   'rendimientos.rendimiento', 'rendimientos.status')
                            ->join('combustibles', 'combustibles.id', '=', 'rendimientos.id_combustible')
                            ->join('vehiculos', 'vehiculos.id', '=', 'rendimientos.id_vehiculo')
                            ->whereIn('vehiculos.id_distribuidora', $user_distribuidoras)
                            ->get();
    }

    public function headings(): array
    {
        return [
            'FECHA',
            'VEHICULO',
            'No AUTORIZACION',
            'COMBUSTIBLE',
            'KILOMETRAJE',
            'CANTIDAD GALONES',
            'VALOR',
            'RECORRIDO',
            'RENDIMIENTO',
            'STATUS'
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class    => function(AfterSheet $event) {
                
                $cellRange = 'A1:J' .((string) count(Rendimiento::all()) + 1); // All headers

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

                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray)->getActiveSheet()->setAutoFilter($cellRange);

                $event->sheet->getDelegate()->getStyle('A1:J1')->getFont()->setSize(14)->setBold(true);
            },
        ];
    }
}
