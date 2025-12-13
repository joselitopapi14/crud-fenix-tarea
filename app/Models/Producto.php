<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'presentacion_tipo',
        'presentacion_valor',
        'imagen',
        'valor_costo',
        'valor_venta',
        'marca',
        'observaciones',
    ];

    protected $casts = [
        'valor_costo' => 'decimal:2',
        'valor_venta' => 'decimal:2',
    ];
}
