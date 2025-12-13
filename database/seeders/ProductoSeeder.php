<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            [
                'codigo' => 'PROD001',
                'nombre' => 'Arroz Diana 500g',
                'presentacion_tipo' => 'peso',
                'presentacion_valor' => '500g',
                'valor_costo' => 2500.00,
                'valor_venta' => 3500.00,
                'marca' => 'Diana',
                'observaciones' => 'Arroz de alta calidad',
            ],
            [
                'codigo' => 'PROD002',
                'nombre' => 'Aceite Girasol 1L',
                'presentacion_tipo' => 'peso',
                'presentacion_valor' => '1L',
                'valor_costo' => 8000.00,
                'valor_venta' => 11000.00,
                'marca' => 'Gourmet',
                'observaciones' => 'Aceite vegetal',
            ],
            [
                'codigo' => 'PROD003',
                'nombre' => 'Leche Entera Alpina',
                'presentacion_tipo' => 'peso',
                'presentacion_valor' => '1L',
                'valor_costo' => 3200.00,
                'valor_venta' => 4500.00,
                'marca' => 'Alpina',
                'observaciones' => 'Leche pasteurizada',
            ],
            [
                'codigo' => 'PROD004',
                'nombre' => 'Pan Tajado Bimbo',
                'presentacion_tipo' => 'unidad',
                'presentacion_valor' => '450g',
                'valor_costo' => 4500.00,
                'valor_venta' => 6200.00,
                'marca' => 'Bimbo',
                'observaciones' => 'Pan de molde',
            ],
            [
                'codigo' => 'PROD005',
                'nombre' => 'Huevos AA x30',
                'presentacion_tipo' => 'unidad',
                'presentacion_valor' => '30 unidades',
                'valor_costo' => 12000.00,
                'valor_venta' => 16000.00,
                'marca' => 'Santa Reyes',
                'observaciones' => 'Huevos frescos',
            ],
        ];

        foreach ($productos as $producto) {
            Producto::create($producto);
        }
    }
}
