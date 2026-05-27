@extends('layouts/app')
@section('titulo', 'Configuración de Tasa de Cambio')

@section('content')
    @if (session('CORRECTO'))
        <script>
            $(function notificacion() {
                new PNotify({
                    title: "CORRECTO",
                    type: "success",
                    text: "{{ session('CORRECTO') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif

    @if (session('INCORRECTO'))
        <script>
            $(function notificacion() {
                new PNotify({
                    title: "INCORRECTO",
                    type: "error",
                    text: "{{ session('INCORRECTO') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif

    <div class="container">
        <h5 class="text-center text-secondary mb-3">CONFIGURACIÓN DE TASA DE CAMBIO (USD → VES)</h5>

        <div class="row">
            <div class="col-12 col-lg-5 mb-3">
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Tasa actual activa</strong>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="text-muted">Valor</div>
                            <div class="h4 mb-0">
                                {{ $valorActual !== null ? $valorActual : '—' }}
                            </div>
                        </div>
                        <div>
                            <div class="text-muted">Vigente desde</div>
                            <div class="mb-0">
                                {{ $fechaActual ? $fechaActual : '—' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-7">
                <div class="card">
                    <div class="card-header bg-light">
                        <strong>Registrar nueva tasa</strong>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('tasa.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="valor_conversion">Valor de 1 USD en VES</label>
                                <input type="number" name="valor_conversion" id="valor_conversion"
                                    class="form-control @error('valor_conversion') is-invalid @enderror" step="0.0001"
                                    min="0" required value="{{ old('valor_conversion') }}" placeholder="Ej: 45.5000">

                                @error('valor_conversion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-right">
                                <button type="submit" class="btn btn-success">Guardar tasa</button>
                                <a href="{{ route('productos.index') }}" class="btn btn-secondary">Volver</a>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
