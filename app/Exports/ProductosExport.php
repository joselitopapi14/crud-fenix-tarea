<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductosExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    public function collection()
    {
        return Producto::orderBy('nombre')->get();
    }

    public function headings(): array
    {
        return [
            'Código',
            'Nombre',
            'Tipo Presentación',
            'Valor Presentación',
            'Marca',
            'Costo',
            'Venta',
            'Observaciones',
            'Fecha Creación',
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

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
