@extends('layouts/app')
@section('titulo', 'Lista de ventas')

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

    @if (session('ERROR'))
        <script>
            $(function notificacion() {
                new PNotify({
                    title: "ERROR",
                    type: "error",
                    text: "{{ session('ERROR') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif

    <h5 class="text-center text-secondary">LISTA DE VENTAS</h5>

    <a href="{{route('ventas.create')}}" class="btn btn-primary">Registrar nueva venta</a>

    <form action="" id="formBuscar" method="POST">
        @csrf
        <div class="row col-12 p-3">
            <div class="col-12 col-md-9">
                <input type="text" name="buscar" id="buscar" class="form-control p-3"
placeholder="Buscar por cliente o cajero">
            </div>
            <button type="submit" class="btn btn-success col-12 col-md-3 mt-2 mt-sm-0">Buscar</button>
        </div>
    </form>

    <div class="overflow-auto">
        <table class="display table table-striped" cellspacing="0" width="100%">
            <thead class="table-primary">
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Productos</th>
                        <th># Ítems</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
            </thead>
            <tbody id="tbody"></tbody>
        </table>
    </div>

    <section class="card">
        <div class="card-block">
            <table id="example2" class="display table table-striped" cellspacing="0" width="100%">
                <thead class="table-primary">
                    <tr>
                        <th>Código</th>
                        <th>Cliente</th>
                        <th>Productos</th>
                        <th># Ítems</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Foto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datos as $item)
                        <tr>
<td><strong>{{ $item->codigo_venta ?: $item->id_venta }}</strong></td>
                            <td>{{ $item->cliente }}</td>
                            <td title="{{ $item->productos_list ?? 'N/A' }}">{{ Str::limit($item->productos_list ?? 'N/A', 20) }}</td>
                            <td>{{ $item->num_productos ?? 0 }}</td>
                            <td>{{ $item->total }}</td>
<td>{{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}</td>
                            <td>{{ $item->estado }}</td>
                            <td>
                                @if ($item->foto == '' or $item->foto == null)
                                    <a href="" data-toggle="modal" data-target="#editar{{ $item->id_venta }}">Agregar foto</a>
                                @else
                                    <a href="" data-toggle="modal" data-target="#exampleModal{{ $item->id_venta }}">Ver foto</a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('ventas.edit', $item->id_venta) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <form action="{{route('ventas.destroy', $item->id_venta)}}" class="formulario-eliminar d-inline" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm" type="submit"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <!-- Modal ver foto -->
                        <div class="modal fade" id="exampleModal{{ $item->id_venta }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Foto de la venta</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form action="{{ route('venta.eliminarFoto') }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <input type="hidden" name="txtid" value="{{ $item->id_venta }}">
                                        <div class="modal-body text-center">
                                            <img style="width: 50%" src="{{ asset('storage/FOTO-VENTAS/' . $item->foto) }}" alt="">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

{{-- Modal editar removido: Usa página completa /edit --}}

                        <!-- Modal agregar foto -->
                        <div class="modal fade" id="editar{{ $item->id_venta }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Agregar foto</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form action="{{ route('venta.registrarFotoVenta') }}" method="POST" enctype="multipart/form-data" id="formFoto{{ $item->id_venta }}">
                                        @csrf
                                        <input type="hidden" name="txtid" value="{{ $item->id_venta }}">
                                        <div class="modal-body">
                                            <input type="file" name="foto" class="form-control" accept=".png,.jpg,.jpeg" required>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Subir</button>
                                        </div>
                                    </form>
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
        let formBuscar = document.getElementById("formBuscar");
        formBuscar.addEventListener("submit", buscarDatos);
        formBuscar.addEventListener("keyup", buscarDatos);

        function buscarDatos(e) {
            e.preventDefault();
            let datos = $(this).serialize();
            $.ajax({
                url: "{{ route('venta.buscar') }}",
                type: "post",
                data: datos,
                success: function(res) {
                    let tbody = document.getElementById("tbody");
                    let tr = "";
                    res.dato.forEach(function(item) {
        tr += `
                            <tr>
                                <td><strong>\${item.codigo_venta || item.id_venta}</strong></td>
                                <td>\${item.cliente}</td>
                                <td title="\${item.productos_list || 'N/A'}">\${Str.limit(item.productos_list || 'N/A', 20)}</td>
                                <td>\${item.num_productos || 0}</td>
                                <td>\${item.total}</td>
<td>\${new Date(item.fecha).toLocaleDateString('es-PE')}</td>
                                <td>\${item.estado}</td>
                                <td>\${item.foto || ''}</td>
                                <td>
                                    <a href="/ventas/\${item.id_venta}" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                                    <a href="/ventas/\${item.id_venta}/edit" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                </td>
                            </tr>
                        `;
                    });
                    tbody.innerHTML = tr;
                },
                error: function() {
                    document.getElementById("tbody").innerHTML = "";
                }
            });
        }
    </script>

@endsection
