<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Factura</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f5f5f5; }
        .header { margin-bottom: 10px; }
        .muted { color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <h3 class="text-center">FACTURA</h3>
        <p class="text-right muted"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y H:i:s') }}</p>
        <p class="muted"><strong>Cliente:</strong> {{ $venta->cliente_nombre ?? '' }}</p>
        <p class="muted"><strong>Cajero:</strong> {{ $cajero ?? '' }}</p>
        <p class="muted"><strong>Código:</strong> {{ $venta->codigo_venta ?? $venta->id_venta }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50%">Producto</th>
                <th style="width: 10%" class="text-center">Cantidad</th>
                <th style="width: 15%" class="text-right">Precio (Bs.)</th>
                <th style="width: 25%" class="text-right">Subtotal (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detalles as $d)
                <tr>
                    <td>{{ $d->producto_nombre }}</td>
                    <td class="text-center">{{ $d->cantidad }}</td>
                    <td class="text-right">Bs. {{ number_format($d->precio, 2) }}</td>
                    <td class="text-right">Bs. {{ number_format($d->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 15px;">
        <p class="text-right"><strong>Total:</strong> Bs. {{ number_format($venta->total, 2) }}</p>
    </div>
</body>
</html>

