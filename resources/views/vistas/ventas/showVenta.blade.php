@extends('layouts/app')
@section('titulo', 'Detalle venta')

@section('content')

    @if (session('INCORRECTO'))
        <script>
            $(function() {
                new PNotify({
                    title: "INCORRECTO",
                    type: "error",
                    text: "{{ session('INCORRECTO') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif

    <h5 class="text-center text-secondary">DETALLE DE LA VENTA #{{ $venta->codigo_venta }}</h5>

    <div class="row mb-4">
        <div class="col-md-3"><strong>Cliente:</strong> {{ $venta->cliente }}</div>
        <div class="col-md-3"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($venta->fecha)->format('d/m/Y') }}</div>
        <div class="col-md-3"><strong>Total:</strong> S/. {{ number_format($venta->total, 2) }}</div>
        <div class="col-md-3"><strong>Estado:</strong> {{ $venta->estado ? 'Activa' : 'Anulada' }}</div>
    </div>

    @if ($venta->foto)
        <div class="text-center mb-4">
            <img src="{{ asset('storage/FOTO-VENTAS/' . $venta->foto) }}" alt="Foto venta" style="max-width: 300px;">
        </div>
    @endif

    <h6>Productos:</h6>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="table-primary">
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->producto_nombre }}</td>
                        <td>{{ $detalle->cantidad }}</td>
                        <td>S/. {{ number_format($detalle->precio, 2) }}</td>
                        <td>S/. {{ number_format($detalle->subtotal, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Sin productos en esta venta</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Volver a Lista</a>
        <a href="{{ route('ventas.edit', $venta->id_venta) }}" class="btn btn-warning">Editar Venta</a>
    </div>

@endsection
