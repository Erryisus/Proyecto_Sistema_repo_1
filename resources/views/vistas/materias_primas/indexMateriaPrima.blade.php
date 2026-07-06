@extends('layouts/app')
@section('titulo', 'Materias Primas')

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
            <h5 class="text-secondary">MATERIAS PRIMAS - INVENTARIO</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('movimientos-materia.create') }}" class="btn btn-success">+ Registrar movimiento</a>
                <a href="{{ route('materias-primas.create') }}" class="btn btn-primary">+ Nueva materia prima</a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <table id="example" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Unidad</th>
                            <th>Existencia actual</th>
                            <th>Stock mínimo</th>
                            <th>Estado</th>
                            <th style="width:180px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datos as $m)
                            <tr>
                                <td>{{ $m->codigo }}</td>
                                <td>{{ $m->nombre }}</td>
                                <td>{{ $m->unidadMedida->abreviatura ?? '-' }}</td>
                                <td>
                                    @if ((float) $m->existencia_actual < (float) $m->stock_minimo)
                                        <span
                                            class="text-danger font-weight-bold">{{ number_format($m->existencia_actual, 2) }}</span>
                                    @else
                                        {{ number_format($m->existencia_actual, 2) }}
                                    @endif
                                </td>
                                <td>{{ number_format($m->stock_minimo, 2) }}</td>
                                <td>{{ $m->estado }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a class="btn btn-warning btn-sm"
                                            href="{{ route('materias-primas.edit', $m->id_materia) }}">Editar</a>
                                        <form action="{{ route('materias-primas.destroy', $m->id_materia) }}"
                                            method="POST" onsubmit="return confirm('¿Eliminar materia prima?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" type="submit">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-2">
                    {{ $datos->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
