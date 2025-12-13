<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ProductosExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithDrawings, WithEvents
{
    public function collection()
    {
        return Producto::orderBy('nombre')->get();
    }

    public function headings(): array
    {
        return [
            ['Fenix BG S.A.S - I+D+I TIC'],
            ['Reporte de Productos - Sistema de Gestión de Inventario'],
            ['Fecha: ' . now()->format('d/m/Y H:i:s')],
            [],
            [
                'Código',
                'Nombre',
                'Tipo Presentación',
                'Valor Presentación',
                'Marca',
                'Costo',
                'Venta',
                'Observaciones',
                'Fecha Creación',
            ]
        ];
    }

    public function map($producto): array
    {
        return [
            $producto->codigo,
            $producto->nombre,
            ucfirst($producto->presentacion_tipo),
            $producto->presentacion_valor ?? '-',
            $producto->marca ?? '-',
            '$' . number_format($producto->valor_costo, 0, ',', '.'),
            '$' . number_format($producto->valor_venta, 0, ',', '.'),
            $producto->observaciones ?? '-',
            $producto->created_at->format('d/m/Y'),
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo Fenix');
        $drawing->setDescription('Logo Fenix BG S.A.S');
        $drawing->setPath(public_path('images/logo-fenix.png'));
        $drawing->setHeight(50);
        $drawing->setCoordinates('A1');

        return $drawing;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FF6B35']],
            ],
            2 => [
                'font' => ['bold' => true, 'size' => 12],
            ],
            3 => [
                'font' => ['size' => 10, 'color' => ['rgb' => '666666']],
            ],
            5 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F0F0F0']
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Auto-size columns
                foreach(range('A','I') as $col) {
                    $event->sheet->getDelegate()->getColumnDimension($col)->setAutoSize(true);
                }
                
                // Merge cells for title
                $event->sheet->getDelegate()->mergeCells('B1:I1');
                $event->sheet->getDelegate()->mergeCells('B2:I2');
                $event->sheet->getDelegate()->mergeCells('B3:I3');
                
                // Set row heights
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(40);
                $event->sheet->getDelegate()->getRowDimension(2)->setRowHeight(20);
                $event->sheet->getDelegate()->getRowDimension(3)->setRowHeight(15);
            },
        ];
    }
}
