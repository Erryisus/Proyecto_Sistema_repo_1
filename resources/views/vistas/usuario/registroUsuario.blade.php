@extends('layouts/app');
@section('titulo', 'Registro de usuarios');
<style>
    textarea {
        field-sizing: content;
    }

    .mensaje {
        color: red;
        font-size: 13px;
        padding: 5px;
    }
</style>
@section('content')

    {{-- notificaciones --}}


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

    <h4 class="text-center text-secondary">Registro de usuarios</h4>

    <form action="{{ route('usuario.store') }}" method="POST" enctype="multipart/form-data">

        @csrf


        <div class="row col-12 mx-0">
            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <select name="tipo_usuario" class="input input__text" id="">
                    <option value="">Seleccionar tipo de usuario...</option>
                    @foreach ($tipos as $item)
                        <option value="{{ $item->id_tipo }}">{{ $item->tipo }}</option>
                    @endforeach
                </select>
                @error('tipo_usuario')
                    <small class="mensaje">{{ $message }}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="text" name="nombre" class="input input__text" placeholder="Nombre">
                @error('nombre')
                    <small class="mensaje">{{ $message }}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="text" name="apellido" class="input input__text" placeholder="Apellido">
                @error('apellido')
                    <small class="mensaje">{{ $message }}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="text" name="usuario" class="input input__text" placeholder="Usuario">
                @error('usuario')
                    <small class="mensaje">{{ $message }}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="password" name="password" class="input input__text" placeholder="Contraseña">
                @error('password')
                    <small class="mensaje">{{ $message }}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="text" name="telefono" class="input input__text" placeholder="Teléfono">
                @error('telefono')
                    <small class="mensaje">{{ $message }}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="text" name="direccion" class="input input__text" placeholder="Dirección">
                @error('direccion')
                    <small class="mensaje">{{ $message }}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="email" name="correo" class="input input__text" placeholder="Correo electrónico">
                @error('correo')
                    <small class="mensaje">{{ $message }}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="file" name="foto" class="input input__text" placeholder="Foto">
                @error('foto')
                    <small class="mensaje">{{ $message }}</small>
                @enderror
            </div>

        </div>



        <div class="text-right px-4">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>

@endsection
