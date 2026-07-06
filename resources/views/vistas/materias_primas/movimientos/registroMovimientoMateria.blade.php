@extends('layouts/app')
@section('titulo', 'Registrar movimiento de materia prima')

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

        <h5 class="text-center text-secondary mt-2">REGISTRAR MOVIMIENTO DE MATERIA PRIMA</h5>

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                <form action="{{ route('movimientos-materia.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <label>Materia prima</label>
                            <select name="txtmateria" class="form-control" required>
                                <option value="">Seleccionar...</option>
                                @foreach ($materias as $m)
                                    <option value="{{ $m->id_materia }}"
                                        {{ old('txtmateria') == $m->id_materia ? 'selected' : '' }}>
                                        {{ $m->nombre }} ({{ $m->codigo }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>Tipo movimiento</label>
                            <select name="txttipoMovimiento" class="form-control" required>
                                <option value="">Seleccionar...</option>
                                @foreach ($tipos as $t)
                                    <option value="{{ $t->id_tipo_movimiento }}"
                                        {{ old('txttipoMovimiento') == $t->id_tipo_movimiento ? 'selected' : '' }}>
                                        {{ $t->nombre }} ({{ $t->afecta_stock }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label>Cantidad</label>
                            <input type="number" name="txtcantidad" step="0.01" class="form-control" required
                                value="{{ old('txtcantidad') }}">
                        </div>

                        <div class="col-md-8 mt-3">
                            <label>Razón (opcional)</label>
                            <input type="text" name="txtrazon" class="form-control" maxlength="255"
                                value="{{ old('txtrazon') }}">
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success">Guardar movimiento</button>
                        <a href="{{ route('movimientos-materia.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
