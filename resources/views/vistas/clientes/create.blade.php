@extends('layouts/app')
@section('titulo', 'Nuevo Cliente')

@section('content')
    @if (session('CORRECTO'))
        <script>
            $(function() {
                new PNotify({
                    title: "ÉXITO",
                    type: "success",
                    text: "{{ session('CORRECTO') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Nuevo Cliente</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('clientes.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>C.I <span class="text-danger">*</span></label>
                                <input type="text" name="cedula"
                                    class="form-control @error('cedula') is-invalid @enderror" value="{{ old('cedula') }}"
                                    required>
                                @error('cedula')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Nombre <span class="text-danger">*</span></label>
                                <input type="text" name="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}"
                                    required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Apellido <span class="text-danger">*</span></label>
                                <input type="text" name="apellido"
                                    class="form-control @error('apellido') is-invalid @enderror"
                                    value="{{ old('apellido') }}" required>
                                @error('apellido')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Teléfono <span class="text-danger">*</span></label>
                                <input type="text" name="telefono"
                                    class="form-control @error('telefono') is-invalid @enderror"
                                    value="{{ old('telefono') }}" required>
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Correo <small>(opcional)</small></label>
                                <input type="email" name="correo"
                                    class="form-control @error('correo') is-invalid @enderror" value="{{ old('correo') }}">
                                @error('correo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Dirección <small>(opcional)</small></label>
                                <input type="text" name="direccion"
                                    class="form-control @error('direccion') is-invalid @enderror"
                                    value="{{ old('direccion') }}">
                                @error('direccion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Guardar Cliente</button>
                        </div>

                        {{-- Pasar a vuelta a ventas.create luego de guardar, desde el flujo de REGISTRAR NUEVA VENTA --}}
                        @if (request()->query('venta_regresar') == '1')
                            <input type="hidden" name="venta_regresar" value="1">
                            <input type="hidden" name="venta_cedula" value="{{ request()->query('venta_cedula') }}">
                        @endif


                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
