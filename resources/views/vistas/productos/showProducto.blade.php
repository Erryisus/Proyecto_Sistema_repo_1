@extends('layouts/app')
@section('titulo', 'Lista de productos')

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

    <h5 class="text-center text-secondary">DATOS DEL PRODUCTO
    </h5>

    <a href="{{route("productos.create")}}" class="btn btn-primary">Registrar nuevo producto</a>

    @foreach ($datos as $producto)
        <div class="p-2">
            <form action="{{ route('productos.update', $producto->id_producto) }}" method="POST"
                enctype="multipart/form-data">

                @csrf

                @method('put')

                <div class="row col-12">
                    <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                        <select name="txtcategoria" id="" class="input input__select">
                            <option value="">Seleccionar categoria...</option>
                            @foreach ($categoria as $item)
                                <option {{ $producto->id_categoria == $item->id_categoria ? 'selected' : '' }}
                                    value="{{ $item->id_categoria }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                        @error('txtcategoria')
                            <small class="mensaje">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                        <input type="text" class="input input__text" placeholder="Codigo del producto"
                            name="txtcodigoproducto" value="{{ $producto->codigo }}">
                        @error('txtcodigoproducto')
                            <small class="mensaje">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row col-12">
                    <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                        <input type="text" class="input input__text" placeholder="Nombre del producto"
                            name="txtnombreproducto" value="{{ $producto->nombre }}">
                        @error('txtnombreproducto')
                            <small class="mensaje">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                        <input type="number" class="input input__text" placeholder="Precio del producto"
                            name="txtprecioproducto" step="0.05" value="{{ $producto->precio }}">
                        @error('txtprecioproducto')
                            <small class="mensaje">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row col-12">
                    <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                        <input type="number" class="input input__text" placeholder="Stock del producto" name="txtstock"
                            value="{{ $producto->stock }}">
                        @error('txtstock')
                            <small class="mensaje">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="fl-flex-label col-12 col-md-6 mb-3 px-2">
                        <textarea name="txtdescripcion" id="" cols="1" rows="1" placeholder="Descripcion"
                            class="input input__text">{{ $producto->descripcion }}</textarea>
                    </div>
                </div>



                @if ($producto->foto == '' or $producto->foto == null)
                    <a href="" data-toggle="modal" data-target="#editar{{ $producto->codigo }}">Agregar
                        foto</a>
                @else
                    {{-- <img style="width: 50px" src="{{ asset("storage/FOTO-PRODUCTOS/$item->foto") }}"
                                        alt=""> --}}
                    <a href="" data-toggle="modal" data-target="#exampleModal{{ $producto->codigo }}">Ver
                        foto</a>
                @endif


                <div class="text-right px-4">
                    <button type="submit" class="btn btn-primary">Modificar</button>
                    <button class="btn btn-danger" type="submit" form="eliminar"><i class="fas fa-trash"></i> Eliminar</button>
                </div>
            </form>

            <form action="{{ route('productos.destroy', $producto->codigo) }}" class="formulario-eliminar d-inline" id="eliminar"
                method="POST">
                @csrf
                @method('delete')                
            </form>
        </div>



        <!-- Modal de ver foto del producto -->
        <div class="modal fade" id="exampleModal{{ $producto->codigo }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title w-100" id="exampleModalLabel">Foto del producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('producto.eliminar') }}" method="POST">
                        @csrf
                        @method('delete')
                        <input type="text" value="{{ $producto->id_producto }}" name="txtid" hidden>
                        <div class="modal-body text-center">
                            <img style="width: 50%" src="{{ asset("storage/FOTO-PRODUCTOS/$producto->foto") }}"
                                alt="">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modal para subir foto del producto -->
        <div class="modal fade" id="editar{{ $producto->codigo }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title w-100" id="exampleModalLabel">Foto del producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <form action="{{ route('producto.registrarFotoProducto') }}"
                            id="guardar{{ $producto->id_producto }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{ $producto->id_producto }}" name="txtid">
                            <input class="input input__text" type="file" name="foto" accept=".jpg, .png, .jpeg">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary"
                            form="guardar{{ $producto->id_producto }}">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach


@endsection
