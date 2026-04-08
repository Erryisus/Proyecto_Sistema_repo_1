@extends('layouts/app')
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
@section('titulo', 'empresa')
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

    <h4 class="text-center text-secondary">ACTUALIZAR CONTRASEÑA</h4>

    <div class="mb-0 col-12 bg-white p-5">
            <form action="{{route("usuario.actualizarClave")}}" method="POST">
                @csrf
                <div class="row">
                    <div class="fl-flex-label mb-4 col-12">
                        <input type="password" name="claveActual" class="input input__text" placeholder="Ingrese la clave actual"
                            >

                    </div>
                    <div class="fl-flex-label mb-4 col-12">
                        <input type="password" name="claveNueva" class="input input__text" 
                            placeholder="Ingrese la nueva clave">
                    </div>
                    

                    <div class="text-right mt-0">
                        <button type="submit" class="btn btn-rounded btn-primary">Guardar</button>
                    </div>
                </div>

            </form>
    </div>

@endsection
