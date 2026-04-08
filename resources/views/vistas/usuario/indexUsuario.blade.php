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

    <script>
        function EliminarFoto(){
            return confirm("¿ESTA SEGURO DE ELIMINAR LA FOTO DEL USUARIO?");
        }
    </script>

    <h5 class="text-center text-secondary">LISTA DE USUARIOS
    </h5>

    <a href="{{ route('usuario.create') }}" class="btn btn-primary mb-2">Registrar nuevo usuario</a>


    <section class="card">
        <div class="card-block">
            <table id="example" class="display table table-striped" cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Usuario</th>
                        <th>Telefono</th>
                        <th>Direccion</th>
                        <th>Correo</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>

                </thead>

                <tbody>
                    @foreach ($datos as $item)
                        <tr>
                            <td>{{ $item->id_usuario }}</td>
                            <td>{{ $item->tipo }}</td>
                            <td>{{ $item->nombre }}</td>
                            <td>{{ $item->apellido }}</td>
                            <td>{{ $item->usuario }}</td>
                            <td>{{ $item->telefono }}</td>
                            <td>{{ $item->direccion }}</td>
                            <td>{{ $item->correo }}</td>
                            <td>
                                @if ($item->foto != '' or $item->foto != null)
                                    <a href="" data-toggle="modal"
                                        data-target="#exampleModal{{ $item->id_usuario }}">Ver foto</a>
                                @else
                                    <a href="" data-toggle="modal"
                                        data-target="#editar{{ $item->id_usuario }}">Agregar foto</a>
                                @endif
                            </td>
                            <td>
                                <a href="" class="btn btn-warning btn-sm" data-toggle="modal"
                                    data-target="#editModal{{ $item->id_usuario }}"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('usuario.destroy', $item->id_usuario) }}"
                                    class="formulario-eliminar d-inline" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm" type="submit"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <!-- Modal de ver foto del usuario -->
                        <div class="modal fade" id="exampleModal{{ $item->id_usuario }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title w-100" id="exampleModalLabel">Foto del usuario</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('usuario.eliminar') }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <input type="text" value="{{ $item->id_usuario }}" name="txtid" hidden>
                                        <div class="modal-body text-center">
                                            <img style="width: 50%" src="{{ asset("storage/FOTOS-PERFIL-USUARIO/$item->foto") }}"
                                                alt="">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-danger" onclick="return EliminarFoto()">Eliminar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <!-- Modal de editar datos del producto -->
                        <div class="modal fade" id="editModal{{ $item->id_usuario }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title w-100" id="exampleModalLabel">Modificar datos del usuario
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ route('usuario.update', $item->id_usuario) }}" method="POST">
                                        @csrf
                                        @method('put')


                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <select required name="txttipo" id="" class="input input__select">
                                                <option value="">Seleccionar tipo de usuario...</option>
                                                @foreach ($tipos as $itemTipo)
                                                    <option @selected($itemTipo->id_tipo == $item->tipo_usuario)
                                                        value="{{ $itemTipo->id_tipo }}">{{ $itemTipo->tipo }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('txttipo')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        

                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input required type="text" class="input input__text"
                                                placeholder="Nombre" name="txtnombre"
                                                value="{{ $item->nombre }}">
                                            @error('txtnombre')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- apellido --}}
                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input required type="text" class="input input__text"
                                                placeholder="Apellido" name="txtapellido"
                                                value="{{ $item->apellido }}">
                                            @error('txtapellido')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- usuario --}}
                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input required type="text" class="input input__text"
                                                placeholder="Usuario" name="txtusuario"
                                                value="{{ old('txtusuario', $item->usuario) }}">
                                            @error('txtusuario')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- telefono --}}
                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input required type="text" class="input input__text"
                                                placeholder="Telefono" name="txttelefono"
                                                value="{{ $item->telefono }}">
                                            @error('txttelefono')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        
                                        {{-- direccion --}}
                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input required type="text" class="input input__text"
                                                placeholder="Direccion" name="txtdireccion"
                                                value="{{ $item->direccion }}">
                                            @error('txtdireccion')
                                                <small class="mensaje">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        {{-- correo --}}
                                        <div class="fl-flex-label col-12 mb-3 px-2">
                                            <input required type="email" class="input input__text"
                                                placeholder="Correo" name="txtcorreo"
                                                value="{{ old('txtcorreo', $item->correo) }}">
                                            @error('txtcorreo')
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


                        <!-- Modal para subir foto del usuario -->
                        <div class="modal fade" id="editar{{ $item->id_usuario }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title w-100" id="exampleModalLabel">Foto del usuario</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <form action="{{ route('usuario.registrarFotoUsuario') }}" id="guardar{{ $item->id_usuario }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="hidden" value="{{ $item->id_usuario }}" name="txtid">
                                            <input class="input input__text" type="file" name="foto"
                                                accept=".jpg, .png, .jpeg">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-dismiss="modal">Cerrar</button>
                                        <button type="submit" class="btn btn-primary" form="guardar{{ $item->id_usuario }}">Guardar</button>
                                    </div>
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
