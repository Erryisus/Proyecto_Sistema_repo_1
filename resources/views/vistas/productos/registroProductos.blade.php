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
                <select name="txtcategoria" class="input input__select">
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
                <input type="hidden" name="txtcodigoproducto" value="">
                <small class="text-muted">El código del producto se genera automáticamente.</small>
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
                <input type="number" class="input input__text" placeholder="Precio de venta" name="txtprecioproducto" step="0.05">
                @error('txtprecioproducto')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>
        </div>

        <div class="row col-12">
            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <input type="number" class="input input__text" placeholder="Precio de compra" name="txtpreciocompra" step="0.05">
                @error('txtpreciocompra')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <select name="txtunidadmedida" class="input input__select">
                    <option value="">Unidad de medida</option>
                    <option value="Gramos">Gramos</option>
                    <option value="Mililitros">Mililitros</option>
                    <option value="Unidades">Unidades</option>
                    <option value="Cajas">Cajas</option>
                </select>
                @error('txtunidadmedida')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>
        </div>

        <div class="row col-12">
            <div class="fl-flex-label col-12 col-md-4 mb-3 px-2">
                <input type="number" class="input input__text" placeholder="Stock" name="txtstock">
                @error('txtstock')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-4 mb-3 px-2">
                <input type="number" class="input input__text" placeholder="Stock mínimo" name="txtstockminimo" step="1">
                @error('txtstockminimo')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>

            <div class="fl-flex-label col-12 col-md-4 mb-3 px-2">
                <input type="number" class="input input__text" placeholder="Stock máximo" name="txtstockmaximo" step="1">
                @error('txtstockmaximo')
                    <small class="mensaje">{{$message}}</small>
                @enderror
            </div>
        </div>

        <div class="row col-12">
            <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                <textarea name="txtdescripcion" cols="30" rows="6" placeholder="Descripción" class="input input__text"></textarea>
            </div>
        </div>



        <div class="text-right px-4">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>

@endsection
