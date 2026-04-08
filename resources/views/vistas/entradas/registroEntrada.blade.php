@extends('layouts/app');
@section('titulo', 'Registro de Productos');
<style>
    textarea {
        field-sizing: content;
    }
    .mensaje{
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

    <h4 class="text-center text-secondary">Registro de categorias</h4>

    <form action="{{route("categoria.store")}}" method="POST">

        @csrf


        <div class="row col-12">
            <div class="fl-flex-label col-12 mb-3 px-2">
                <input type="text" class="input input__text" placeholder="Nombre de la categoria" name="txtnombrecategoria"
                    value="{{old('txtnombrecategoria')}}" required>
                @error('txtnombrecategoria')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>
            
        </div>    

       

        <div class="text-right px-4">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>

@endsection
