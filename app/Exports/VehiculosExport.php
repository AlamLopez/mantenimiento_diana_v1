<?php

namespace App\Exports;

use Auth;
use App\Vehiculo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class VehiculosExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user_distribuidoras = explode(',', Auth::user()->distribuidoras);

        return Vehiculo::select('vehiculos.codigo_comb', 'vehiculos.placa', 'vehiculos.numero_vehiculo', 'vehiculos.marca', 'vehiculos.modelo',
                                'vehiculos.anio', 'distribuidoras.nombre as distribuidora', 'vehiculos.conductor',
                                'vehiculos.ulto_km', 'vehiculos.km', 'vehiculos.condicion', 'talleres.nombre as taller',
                                'vehiculos.periodo_mtto_kms', 'vehiculos.periodo_mtto_meses', 'vehiculos.ruta', 'vehiculos.propietario')
                        ->join('distribuidoras', 'distribuidoras.id', '=', 'vehiculos.id_distribuidora')
                        ->join('talleres', 'talleres.id', '=', 'vehiculos.id_taller')
                        ->whereIn('distribuidoras.id', $user_distribuidoras)
                        ->get();
    }

    public function headings(): array
    {
        return [
            'CODIGO_COMB',
            'PLACA',
            'NUMERO VEHICULO',
            'MARCA',
            'MODELO',
            'ANIO',
            'DISTRIBUIDORA',
            'CONDUCTOR',
            'ULTO KM',
            'KM',
            'CONDICION',
            'TALLER',
            'PERIODO MTTO KMS',
            'PERIODO MTTO MESES',
            'RUTA',
            'PROPIETARIO'
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [

            AfterSheet::class => function(AfterSheet $event) {
                
                $cellRange = 'A1:P' .((string) count(Vehiculo::all()) + 1); // All headers

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

                $event->sheet->getDelegate()->getStyle('A1:P1')->getFont()->setSize(14)->setBold(true);
                
            },
        ];
    }
}
