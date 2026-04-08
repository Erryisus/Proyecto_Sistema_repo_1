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

    <h5 class="text-center text-secondary">LISTA DE PRODUCTOS
    </h5>

    <a href="{{route("productos.create")}}" class="btn btn-primary">Registrar nuevo producto</a>

    <form action="" id="formBuscar" method="POST">
        @csrf
        <div class="row col-12 p-3">
            <div class="col-12 col-md-9">
                <input type="text" name="buscar" id="buscar" class="form-control p-3"
                    placeholder="Ingrese el codigo o nombre del producto">
            </div>
            <button type="submit" class="btn btn-success col-12 col-md-3 mt-2 mt-sm-0">Buscar</button>
        </div>
    </form>

    <div class="overflow-auto">
        <table class="display table table-striped" cellspacing="0" width="100%">
            <thead class="table-primary">
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoria</th>
                    <th>Foto</th>
                    <th></th>
                </tr>

            </thead>

            <tbody id="tbody">

            </tbody>
        </table>
    </div>


    <section class="card">
        <div class="card-block">
            <table id="example2" class="display table table-striped" cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Precio</th>
                        <th>Stock</th>
                        <th>Categoria</th>
                        <th>Foto</th>
                        <th></th>
                    </tr>

                </thead>

                <tbody>
                    @foreach ($datos as $item)
                        <tr>
                            <td>{{ $item->codigo }}</td>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ $item->descripcion }}</td>
                            <td>{{ $item->precio }}</td>
                            <td>{{ $item->stock }}</td>
                            <td>{{ $item->categoria }}</td>
                            <td>
                                @if ($item->foto == '' or $item->foto == null)
                                    <a href="" data-toggle="modal" data-target="#editar{{ $item->codigo }}">Agregar
                                        foto</a>
                                @else
                                    {{-- <img style="width: 50px" src="{{ asset("storage/FOTO-PRODUCTOS/$item->foto") }}"
                                        alt=""> --}}
                                    <a href="" data-toggle="modal" data-target="#exampleModal{{ $item->codigo }}">Ver
                                        foto</a>
                                @endif
                            </td>
                            <td>
                                <a href="" class="btn btn-warning btn-sm" data-toggle="modal"
                                    data-target="#editModal{{ $item->codigo }}"><i class="fas fa-edit"></i></a>
                                {{-- <a href="" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a> --}}
                                <form action="{{route("productos.destroy",$item->codigo)}}" class="formulario-eliminar d-inline" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal de ver foto del producto -->
                        <div class="modal fade" id="exampleModal{{ $item->codigo }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                        <input type="text" value="{{ $item->id_producto }}" name="txtid" hidden>
                                        <div class="modal-body text-center">
                                            <img style="width: 50%" src="{{ asset("storage/FOTO-PRODUCTOS/$item->foto") }}"
                                                alt="">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal de editar datos del producto -->
                        <div class="modal fade" id="editModal{{ $item->codigo }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title w-100" id="exampleModalLabel">Modificar datos del producto
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('productos.update', $item->id_producto) }}" method="POST">
                                        @csrf
                                        @method('put')
                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <select required name="txtcategoria" id="" class="input input__select">
                                                <option value="">Seleccionar categoria...</option>
                                                @foreach ($categoria as $itemCat)
                                                    <option @selected($itemCat->id_categoria == $item->id_categoria)
                                                        value="{{ $itemCat->id_categoria }}">{{ $itemCat->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('txtcategoria')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input required type="text" class="input input__text"
                                                placeholder="Codigo del producto" name="txtcodigoproducto"
                                                value="{{ $item->codigo }}">
                                            @error('txtcodigoproducto')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input required type="text" class="input input__text"
                                                placeholder="Nombre del producto" name="txtnombreproducto"
                                                value="{{ $item->nombre }}">
                                            @error('txtnombreproducto')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input required type="number" class="input input__text"
                                                placeholder="Precio del producto" name="txtprecioproducto"
                                                value="{{ $item->precio }}" step="0.05">
                                            @error('txtprecioproducto')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input required type="number" class="input input__text"
                                                placeholder="Stock del producto" name="txtstock"
                                                value="{{ $item->stock }}">
                                            @error('txtstock')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <textarea name="txtdescripcion" id="" cols="30" rows="2" placeholder="Descripcion"
                                                class="input input__text">{{ $item->descripcion }}</textarea>
                                        </div>



                                        <div class="text-right p-4">
                                            <button type="submit" class="btn btn-primary">Guardar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal para subir foto del producto -->
                        <div class="modal fade" id="editar{{ $item->codigo }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                            id="guardar{{ $item->id_producto }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" value="{{ $item->id_producto }}" name="txtid">
                                            <input class="input input__text" type="file" name="foto"
                                                accept=".jpg, .png, .jpeg">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary"
                                            form="guardar{{ $item->id_producto }}">Guardar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>

            <div class="text-right">
                {{ $datos->links('pagination::bootstrap-4') }}
                Mostrando {{ $datos->firstItem() }} - {{ $datos->lastItem() }} de {{ $datos->total() }} resultados
            </div>






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
