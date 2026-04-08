@extends('layouts/app')
@section('titulo', 'Lista de entradas')

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

    <h5 class="text-center text-secondary">LISTA DE ENTRADAS
    </h5>

    <a href="{{ route('categoria.create') }}" class="btn btn-primary mb-2">Registrar nueva entrada</a>


    <section class="card">
        <div class="card-block">
            <table id="example" class="display table table-striped" cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Producto</th>
                        <th>Proveedor</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>

                </thead>

                <tbody>
                    @foreach ($datos as $item)
                        <tr>
                            <td>{{ $item->id_entrada }}</td>
                            <td>{{ $item->nomProducto }}</td>
                            <td>{{ $item->nomProveedor }} {{ $item->apellido }}</td>
                            <td>{{ $item->cantidad }}</td>
                            <td>{{ $item->precio }}</td>
                            <td>{{ $item->fecha }}</td>

                            <td>
                                <a href="" class="btn btn-warning btn-sm" data-toggle="modal"
                                    data-target="#editModal{{ $item->id_entrada }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('categoria.destroy', $item->id_entrada) }}"
                                    class="formulario-eliminar d-inline" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm" type="submit"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal de editar datos de la categoria -->
                        <div class="modal fade" id="editModal{{ $item->id_entrada }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title w-100" id="exampleModalLabel">Modificar datos de la entrada
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('entradas.update', $item->id_entrada) }}" method="POST">
                                        @csrf
                                        @method('put')

                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <label for="id_producto">Producto</label>
                                            <select name="id_producto" id="id_producto" class="input input__select">
                                                @foreach ($producto as $prod)
                                                    <option {{ $item->id_producto == $prod->id_producto ? 'selected' : '' }} value="{{ $prod->id_producto }}">{{ $prod->nombre }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_producto')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <label for="">Provedor</label>
                                            <select name="id_proveedor" id="id_proveedor" class="input input__select">
                                                @foreach ($proveedor as $prov)
                                                    <option {{ $item->id_proveedor == $prov->id_proveedor ? 'selected' : '' }} value="{{ $prov->id_proveedor }}">{{ $prov->nombre }} {{ $prov->apellido }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_proveedor')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- CANTIDAD --}}

                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input type="number" class="input input__text"
                                                placeholder="Cantidad de la entrada"
                                                name="cantidad"value="{{ $item->cantidad }}" required>
                                            @error('cantidad')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- PRECIO --}}

                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input type="number" class="input input__text"
                                                placeholder="Precio de la entrada"
                                                name="precio"value="{{ $item->precio }}" required>
                                            @error('precio')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- FECHA --}}
                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input type="date" class="input input__text"
                                                placeholder="Fecha de la entrada"
                                                name="fecha"value="{{ $item->fechaEntrada }}" required>
                                            @error('fecha')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>


                                        <div class="text-right p-4">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>








        </div>
    </section>

    <script>
        let formBuscar = document.getElementById("formBuscar")
        formBuscar.addEventListener("submit", buscarDatos)
        formBuscar.addEventListener("keyup", buscarDatos)
        formBuscar.addEventListener("blur", buscarDatos)

        function buscarDatos(e) {
            e.preventDefault();
            let datos = $(this).serialize();
            $.ajax({
                url: "{{ route('producto.buscar') }}",
                type: "post",
                data: datos,
                success: function(res) {
                    let tbody = document.getElementById("tbody")
                    let tr = "";
                    res.dato.forEach(function(item, index) {
                        tr += `
                        <tr>
                            <td>${item.codigo}</td>
                            <td>${item.nombre}</td>
                            <td>${item.descripcion}</td>
                            <td>${item.precio}</td>
                            <td>${item.stock}</td>
                            <td>${item.cate}</td>
                            <td>${item.foto}</td>
                            <td><a href="productos/${item.id_producto}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> Ver</a></td>
                        </tr>
                        `
                    });
                    tbody.innerHTML = tr;

                },
                error: function() {
                    let tbody = document.getElementById("tbody")
                    tbody.innerHTML = ""
                }
            })
            console.log(datos)
        }
    </script>

@endsection
