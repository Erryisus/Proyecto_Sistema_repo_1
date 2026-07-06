@extends('layouts/app')
@section('titulo', 'Movimientos de Materia Prima')

@section('content')
    <div class="container-fluid">
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
        @if (session('CORRECTO'))
            <script>
                $(function() {
                    new PNotify({
                        title: "CORRECTO",
                        type: "success",
                        text: "{{ session('CORRECTO') }}",
                        styling: "bootstrap3"
                    });
                });
            </script>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="text-secondary">HISTORIAL DE MOVIMIENTOS</h5>
            <a href="{{ route('movimientos-materia.create') }}" class="btn btn-success">+ Registrar movimiento</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Materia prima</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                            <th>Existencia anterior</th>
                            <th>Existencia nueva</th>
                            <th>Razón</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($movimientos as $mov)
                            <tr>
                                <td>{{ $mov->fecha ? \Carbon\Carbon::parse($mov->fecha)->format('Y-m-d H:i') : '-' }}</td>
                                <td>
                                    {{ $mov->materiaPrima->nombre ?? '-' }}
                                    <small class="text-muted d-block">{{ $mov->materiaPrima->codigo ?? '' }}</small>
                                </td>
                                <td>
                                    {{ $mov->tipoMovimiento->nombre ?? '-' }}
                                    <small class="text-muted d-block">{{ $mov->tipoMovimiento->afecta_stock ?? '' }}</small>
                                </td>
                                <td>{{ number_format($mov->cantidad, 2) }}</td>
                                <td>{{ number_format($mov->existencia_anterior, 2) }}</td>
                                <td>{{ number_format($mov->existencia_nueva, 2) }}</td>
                                <td style="max-width:220px;">{{ $mov->razon ?? '-' }}</td>
                                <td>{{ $mov->usuario ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-2">
                    {{ $movimientos->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
