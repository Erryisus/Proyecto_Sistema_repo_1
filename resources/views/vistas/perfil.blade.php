@extends('layouts/app')
@section('titulo', 'Mi perfil')
<style>
    .contenedor {
        background: white;
        padding: 15px;
        display: flex;
        justify-content: space-around;
        gap: 20px;
        align-items: center;
    }

    .img {
        width: 130px;
        height: 130px;
        border-radius: 250px;
    }

    @media screen and (max-width: 600px) {
        .contenedor {
            flex-direction: row;
            justify-content: center;
            flex-wrap: wrap;
            align-items: center;
        }
    }
</style>
@section('content')

    @if (session('mensaje'))
        <script>
            $(function notificacion() {
                new PNotify({
                    title: "CORRECTO",
                    type: "success",
                    text: "{{ session('mensaje') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            $(function notificacion() {
                new PNotify({
                    title: "INCORRECTO",
                    type: "error",
                    text: "{{ session('error') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif


    <h4 class="text-center text-secondary">MI PERFIL</h4>

{{-- Debug line removed --}}

@if (isset($usuario) || !empty($datos))
    @php $item = isset($usuario) ? $usuario : $datos[0]; @endphp
@endif

<div class="contenedor">
    <div>

        @if (isset($item->foto) && $item->foto)
            <img class="img" src="{{ asset('storage/FOTOS-PERFIL-USUARIO/' . $item->foto) }}" alt="Foto de perfil">
        @else
            <img class="img" src="{{ asset('images/img.jpg') }}" alt="Foto por defecto">
        @endif

    </div>

    <div class="">
        <h6><b>Modificar imagen</b></h6>
        <form action="{{ route('perfil.actualizarIMG') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="alert alert-secondary">Selecciona una imagen no muy pesado y en un formato válido ...!</div>
            <div>
                <input type="file" class="input form-control-file mb-3" name="foto"
                    accept=".jpg, .png, .jpeg">
                @error('foto')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-success btn-rounded">Modificar</button>
                <button type="submit" form="eliminarFoto" class="btn btn-danger btn-rounded">Eliminar foto</button>
            </div>
        </form>

        <form action="{{ route('perfil.eliminarFotoPerfil') }}" id="eliminarFoto" class="formulario-eliminar"
            method="get">

        </form>

    </div>
</div>

<form action="{{ route('perfil.actualizarDatos') }}" method="POST" class="bg-white p-3">
    <div class="row">

        @method('put')

        @csrf

        <div class="fl-flex-label col-12 col-lg-6 mb-4">
            <input type="text" class="input input__text" placeholder="Nombres" value="{{ $item->nombre ?? '' }}"
                name="nombre">
            @error('nombre')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="fl-flex-label col-12 col-lg-6 mb-4">
            <input type="text" class="input input__text" placeholder="Apellidos"value="{{ $item->apellido ?? '' }}"
                name="apellido">
            @error('apellido')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="fl-flex-label col-12 col-lg-6 mb-4">
            <input type="text" class="input input__text" placeholder="Usuario"value="{{ $item->usuario ?? '' }}"
                name="usuario">
            @error('usuario')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="fl-flex-label col-12 col-lg-6 mb-4">
            <input type="number" class="input input__text" placeholder="Telefono"value="{{ $item->telefono ?? '' }}"
                name="telefono">
        </div>

        <div class="fl-flex-label col-12 col-lg-6 mb-4">
            <input type="text" class="input input__text" placeholder="Direccion"value="{{ $item->direccion ?? '' }}"
                name="direccion">
        </div>

        <div class="fl-flex-label col-12 col-lg-6 mb-4">
            <input type="email" class="input input__text" placeholder="Correo" value="{{ $item->correo ?? '' }}"
                name="correo">
            @error('correo')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary btn-rounded">Guardar</button>
        </div>
    </div>
</form>

@endsection
