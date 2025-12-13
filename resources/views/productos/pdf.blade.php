<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 14px;
            color: #666;
        }
        
        .metadata {
            margin-bottom: 20px;
            font-size: 11px;
            color: #666;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: left;
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        
        td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .observaciones {
            max-width: 200px;
            word-wrap: break-word;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%; border: none;">
            <tr>
                <td style="width: 200px; border: none; padding: 0;">
                    <img src="{{ public_path('images/logo-fenix.png') }}" alt="Fenix Logo" style="height: 60px;">
                </td>
                <td style="border: none; text-align: center; padding: 0;">
                    <h1>Reporte de Productos</h1>
                    <p>Fenix BG S.A.S - I+D+I TIC</p>
                    <p style="font-size: 10px; margin-top: 5px;">Sistema de Gestión de Inventario</p>
                </td>
                <td style="width: 200px; border: none; text-align: right; padding: 0; font-size: 10px; color: #666;">
                    <strong>Fecha:</strong> {{ now()->format('d/m/Y') }}<br>
                    <strong>Hora:</strong> {{ now()->format('H:i:s') }}<br>
                    <strong>Total:</strong> {{ count($productos) }} productos
                </td>
            </tr>
        </table>
    </div>
    
    <div class="metadata">
        <strong>Generado por:</strong> CRUD Fenix v1.0
    </div>
    
    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Nombre</th>
                <th>Presentación</th>
                <th>Marca</th>
                <th class="text-right">Costo</th>
                <th class="text-right">Venta</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productos as $producto)
            <tr>
                <td>{{ $producto->codigo }}</td>
                <td>{{ $producto->nombre }}</td>
                <td>
                    {{ ucfirst($producto->presentacion_tipo) }}
                    @if($producto->presentacion_valor)
                        - {{ $producto->presentacion_valor }}
                    @endif
                </td>
                <td>{{ $producto->marca ?? '-' }}</td>
                <td class="text-right">${{ number_format($producto->valor_costo, 0, ',', '.') }}</td>
                <td class="text-right">${{ number_format($producto->valor_venta, 0, ',', '.') }}</td>
                <td class="observaciones">{{ $producto->observaciones ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p><strong>Fenix BG S.A.S</strong> - I+D+I TIC</p>
        <p>Sistema de Gestión de Inventario - CRUD Fenix v1.0</p>
        <p>Documento generado el {{ now()->format('d/m/Y') }} a las {{ now()->format('H:i:s') }}</p>
    </div>
</body>
</html>
