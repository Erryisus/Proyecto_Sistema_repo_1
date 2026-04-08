@extends('layouts/app')
@section('titulo', 'Lista de categorias')

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

    <h5 class="text-center text-secondary">LISTA DE CATEGORIAS
    </h5>

    <a href="{{ route('categoria.create') }}" class="btn btn-primary mb-2">Registrar nueva categoria</a>


    <section class="card">
        <div class="card-block">
            <table id="example" class="display table table-striped" cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th></th>
                    </tr>

                </thead>

                <tbody>
                    @foreach ($categorias as $item)
                        <tr>
                            <td>{{ $item->id_categoria }}</td>
                            <td>{{ $item->nombre }}</td>

                            <td>
                                <a href="" class="btn btn-warning btn-sm" data-toggle="modal"
                                    data-target="#editModal{{ $item->id_categoria }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('categoria.destroy', $item->id_categoria) }}"
                                    class="formulario-eliminar d-inline" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm" type="submit"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal de editar datos de la categoria -->
                        <div class="modal fade" id="editModal{{ $item->id_categoria }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title w-100" id="exampleModalLabel">Modificar datos de la categoria
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('categoria.update', $item->id_categoria) }}" method="POST">
                                        @csrf
                                        @method('put')

                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input type="text" class="input input__text"
                                                placeholder="Nombre de la categoria"
                                                name="txtnombrecategoria"value="{{ $item->nombre }}" required>
                                            @error('txtnombrecategoria')
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
