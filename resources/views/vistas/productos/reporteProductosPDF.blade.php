<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Reporte de Productos - PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .header p {
            margin: 4px 0 0;
            color: #444;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }

        .summary-title {
            font-weight: bold;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 14px;
        }

        .products-table th,
        .products-table td {
            border: 1px solid #ddd;
            padding: 6px;
        }

        .products-table th {
            background: #f5f5f5;
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .muted {
            color: #555;
        }

        .footer-note {
            margin-top: 12px;
            font-size: 10.5px;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="header">
        <h2>REPORTE DE PRODUCTOS</h2>
        <p class="muted">Filtrado: Bajo Stock < {{ $bajo_stock }}</p>
                <p class="muted">Fecha: {{ date('Y-m-d') }}</p>
    </div>

    <table class="summary-table">
        <tr>
            <td style="width: 33%;">
                <div class="summary-title">Total Productos</div>
                <div class="summary-value">{{ $total_productos }}</div>
            </td>
            <td style="width: 33%;">
                <div class="summary-title">Bajo Stock</div>
                <div class="summary-value">{{ $bajo_stock_count }}</div>
            </td>
            <td style="width: 34%;">
                <div class="summary-title">Valor Total Inventario</div>
                <div class="summary-value">Bs. {{ number_format((float) $total_value_bs, 2) }}</div>
                <div class="muted">USD: {{ number_format((float) $total_value_usd, 2) }}</div>
            </td>
        </tr>
    </table>

    <table class="products-table">
        <thead>
            <tr>
                <th style="width: 14%;">Código</th>
                <th style="width: 32%;">Producto</th>
                <th style="width: 18%;">Categoría</th>
                <th style="width: 10%;" class="text-center">Stock</th>
                <th style="width: 13%;" class="text-end">Precio (USD)</th>
                <th style="width: 13%;" class="text-end">Valor (USD)</th>
                <th style="width: 13%;" class="text-end">Valor (Bs.)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($productos as $p)
                <tr>
                    <td>{{ $p->codigo }}</td>
                    <td>{{ $p->nombre }}</td>
                    <td>{{ $p->categoria ?? '-' }}</td>
                    <td class="text-center">{{ $p->stock }}</td>
                    <td class="text-end">{{ number_format((float) $p->precio, 2) }}</td>
                    <td class="text-end">{{ number_format((float) $p->valor_usd, 2) }}</td>
                    <td class="text-end">{{ number_format((float) $p->valor_bs, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-note">
        Conversión USD -> Bs: tasa = {{ number_format((float) $tasaCambiaria, 4) }}
    </div>

</body>

</html>
