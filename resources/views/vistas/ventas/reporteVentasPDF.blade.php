<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Ventas - {{ $fecha_desde }} al {{ $fecha_hasta }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
        .summary { margin-bottom: 30px; }
        .metric { display: inline-block; width: 24%; text-align: center; margin: 5px; padding: 10px; background: #f8f9fa; border: 1px solid #dee2e6; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #007bff; color: white; font-weight: bold; }
        .venta-details { margin-top: 10px; font-size: 11px; }
        .total-row { font-weight: bold; background-color: #e9ecef; }
        .footer { margin-top: 40px; text-align: center; font-size: 10px; color: #666; }
        h1 { color: #333; }
        h3 { color: #007bff; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sistema de Ventas</h1>
        <h3>Reporte de Ventas</h3>
        <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($fecha_desde)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($fecha_hasta)->format('d/m/Y') }}</p>
        <p><strong>Generado:</strong> {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <h4>Resumen Ejecutivo</h4>
        <div class="metric">
            <strong>{{ $total_ventas }}</strong><br>
            Total Ventas
        </div>
        <div class="metric">
            <strong>Bs. {{ number_format($total_ingresos, 2) }}</strong><br>
            Ingresos Totales
        </div>
        <div class="metric">
            <strong>Bs. {{ number_format($total_ventas > 0 ? $total_ingresos / $total_ventas : 0, 2) }}</strong><br>
            Ticket Promedio
        </div>
        <div class="metric">
            <strong>{{ $top_productos->count() }}</strong><br>
            Productos Líder
        </div>
    </div>

    <h4>Detalle de Ventas</h4>
    @php
        $ventas = DB::table('venta')
            ->join('cliente', 'venta.id_cliente', '=', 'cliente.id_cliente')
            ->leftJoin('usuario', 'venta.id_usuario', '=', 'usuario.id_usuario')
            ->select('venta.*', 'cliente.nombre as cliente_nombre', 'cliente.apellido as cliente_apellido', 'usuario.nombre as cajero_nombre')
            ->whereBetween('venta.fecha', [$fecha_desde, $fecha_hasta])
            ->orderBy('venta.fecha', 'desc')
            ->orderBy('venta.id_venta', 'desc')
            ->get();
    @endphp

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Cajero</th>
                <th>Items</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->codigo_venta ?? $venta->id_venta }}</td>
                <td>{{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</td>
                <td>{{ $venta->cliente_nombre }} {{ $venta->cliente_apellido }}</td>
                <td>{{ $venta->cajero_nombre }}</td>
                <td>
                    @php
                        $detalles = DB::table('venta_detalle')
                            ->join('producto', 'venta_detalle.id_producto', '=', 'producto.id_producto')
                            ->select('producto.nombre', 'venta_detalle.cantidad', 'venta_detalle.precio', 'venta_detalle.subtotal')
                            ->where('venta_detalle.id_venta', $venta->id_venta)
                            ->get();
                    @endphp
                    {{ count($detalles) }}
                </td>
                <td class="text-right">Bs. {{ number_format($venta->total, 2) }}</td>
                <td>{{ $venta->estado ? 'Activa' : 'Anulada' }}</td>
            </tr>
            @if(count($detalles) > 0)
            @foreach($detalles as $det)
            <tr class="venta-details">
                <td colspan="3"></td>
                <td colspan="2"> &nbsp;&nbsp;&nbsp; {{ $det->nombre }} (x{{ $det->cantidad }})</td>
                <td class="text-right">Bs. {{ number_format($det->subtotal, 2) }}</td>
                <td></td>
            </tr>
            @endforeach
            @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5"></td>
                <td class="text-right"><strong>Bs. {{ number_format($total_ingresos, 2) }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Reporte generado automáticamente por el Sistema de Ventas.</p>
    </div>
</body>
</html>
