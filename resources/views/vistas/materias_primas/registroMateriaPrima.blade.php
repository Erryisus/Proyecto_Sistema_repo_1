@extends('layouts/app')
@section('titulo', isset($materia) ? 'Editar materia prima' : 'Registrar materia prima')

@section('content')
    <div class="container-fluid">
        <h5 class="text-center text-secondary mt-2">
            {{ isset($materia) ? 'EDITAR MATERIA PRIMA' : 'REGISTRAR MATERIA PRIMA' }}
        </h5>

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

        <div class="card shadow-sm mt-3">
            <div class="card-body">
                @php
                    $fechaVenc =
                        isset($materia) && $materia->fecha_vencimiento
                            ? \Carbon\Carbon::parse($materia->fecha_vencimiento)->format('Y-m-d')
                            : '';
                @endphp

                <form
                    action="{{ isset($materia) ? route('materias-primas.update', $materia->id_materia) : route('materias-primas.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($materia))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <label>Código</label>
                            <input type="text" name="txtcodigo" class="form-control"
                                value="{{ old('txtcodigo', $materia->codigo ?? '') }}" required>
                        </div>

                        <div class="col-md-6">
                            <label>Nombre</label>
                            <input type="text" name="txtnombre" class="form-control"
                                value="{{ old('txtnombre', $materia->nombre ?? '') }}" required>
                        </div>

                        <div class="col-md-2">
                            <label>Estado</label>
                            <select name="txtestado" class="form-control" required>
                                <option value="ACTIVO"
                                    {{ old('txtestado', $materia->estado ?? 'ACTIVO') === 'ACTIVO' ? 'selected' : '' }}>
                                    ACTIVO</option>
                                <option value="INACTIVO"
                                    {{ old('txtestado', $materia->estado ?? '') === 'INACTIVO' ? 'selected' : '' }}>INACTIVO
                                </option>
                            </select>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label>Unidad de medida</label>
                            <select name="txtunidadmedida" class="form-control" required>
                                @foreach ($unidades as $u)
                                    <option value="{{ $u->id_unidad }}"
                                        {{ old('txtunidadmedida', $materia->id_unidad ?? '') == $u->id_unidad ? 'selected' : '' }}>
                                        {{ $u->nombre }} ({{ $u->abreviatura }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label>Existencia actual</label>
                            <input type="number" step="0.01" name="txtexistencia" class="form-control"
                                value="{{ old('txtexistencia', $materia->existencia_actual ?? 0) }}" required>
                        </div>

                        <div class="col-md-4 mt-3">
                            <label>Stock mínimo</label>
                            <input type="number" step="0.01" name="txtstockminimo" class="form-control"
                                value="{{ old('txtstockminimo', $materia->stock_minimo ?? 0) }}" required>
                        </div>

                        <div class="col-md-6 mt-3">
                            <label>Fecha vencimiento (opcional)</label>
                            <input type="date" name="txtfechavencimiento" class="form-control"
                                value="{{ old('txtfechavencimiento', $fechaVenc) }}">
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit"
                            class="btn btn-success">{{ isset($materia) ? 'Actualizar' : 'Registrar' }}</button>
                        <a href="{{ route('materias-primas.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
