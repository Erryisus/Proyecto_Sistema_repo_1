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

    <h4 class="text-center text-secondary">Registro de productos</h4>

    <form action="{{route("productos.store")}}" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="row col-12">
            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <select name="txtcategoria" id="" class="input input__select">
                    <option value="">Seleccionar categoria...</option>
                    @foreach ($categoria as $item)
                        <option value="{{ $item->id_categoria }}">{{ $item->nombre }}</option>
                    @endforeach
                </select>
                @error('txtcategoria')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="text" class="input input__text" placeholder="Codigo del producto" name="txtcodigoproducto">
                @error('txtcodigoproducto')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>
        </div>

        <div class="row col-12">
            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="text" class="input input__text" placeholder="Nombre del producto" name="txtnombreproducto">
                @error('txtnombreproducto')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="number" class="input input__text" placeholder="Precio del producto" name="txtprecioproducto" step="0.05">
                @error('txtprecioproducto')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>
        </div>

        <div class="row col-12">
            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="number" class="input input__text" placeholder="Stock del producto" name="txtstock">
                @error('txtstock')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <textarea name="txtdescripcion" id="" cols="30" rows="10" placeholder="Descripcion"
                    class="input input__text"></textarea>
            </div>
        </div>

        <div class="row col-12">
            <label>Subir foto del producto</label>
            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="file" class="input input__text" name="txtfoto" accept=".png, .jpg, .jpeg">
                @error('txtfoto')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>          
        </div>

        <div class="text-right px-4">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>

@endsection
