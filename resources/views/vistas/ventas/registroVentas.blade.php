@extends('layouts/app')
@section('titulo', 'Registrar venta')

@section('content')
    @if (session('INCORRECTO'))
        <script>
            $(function() {
                new PNotify({
                    title: "INCORRECTO",
                    type: "error",
                    text: "{{ session('INCORRECTO') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif

    <h5 class="text-center text-secondary">REGISTRAR NUEVA VENTA</h5>

    @if (isset($is_edit))
        <form action="{{ route('ventas.update', $venta_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
        @else
            <form action="{{ route('ventas.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
    @endif
    <div class="row">
        <div class="col-md-6">
            <label>Código venta (Auto)</label>
            <input type="text" name="txtcodigo" id="codigoPreview" class="form-control" readonly
                placeholder="0001, 0002, etc. (auto)">
            @error('txtcodigo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label>Cliente</label>
            <div class="input-group">
                <select name="txtcliente" id="clienteSelect" class="form-control" required>
                    <option value="">Seleccionar cliente</option>
                </select>

                <button type="button" class="btn btn-success" data-toggle="modal"
                    data-target="#modalClienteBuscarCI">Buscar por C.I.</button>
            </div>
        </div>

        <div class="col-12">
            <label>Productos (Agregar múltiples)</label>
            <div id="productos-container">
                <!-- Rows dinámicas se agregan aquí -->
                <div class="row mb-3 product-row" data-index="0">
                    <div class="col-md-5">
                        <select name="productos[]" class="form-control product-select" required>
                            <option value="">Seleccionar producto</option>
                            @foreach ($productos as $p)
                                <option value="{{ $p->id_producto }}" data-precio="{{ $p->precio_bs }}">{{ $p->nombre }}
                                    ({{ number_format($p->precio_bs, 2) }} Bs.)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="cantidades[]" class="form-control qty-input" min="1" required
                            placeholder="Cantidad">
                    </div>
                    <div class="col-md-3">
                        <input type="number" name="subtotales[]" class="form-control subtotal-input" step="0.01"
                            readonly placeholder="Subtotal">
                    </div>
                    <input type="hidden" name="precios_unitarios[]" class="price-hidden">
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                    </div>
                </div>
            </div>
            <button type="button" id="add-product" class="btn btn-secondary mt-2">+ Agregar Producto</button>
        </div>
        <div class="col-md-6">
            <label>Total General</label>
            <input type="number" id="grand-total" class="form-control" step="0.01" readonly>
        </div>
        <div class="col-md-6">
            <label>Fecha</label>
            <input type="date" name="txtfecha" class="form-control @error('txtfecha') is-invalid @enderror"
                value="{{ isset($venta) ? $venta->fecha : date('Y-m-d') }}" required>
        </div>
        <div class="col-12">
            @if (isset($venta) && $venta->foto)
                <div class="mb-2">
                    <img src="{{ asset('storage/FOTO-VENTAS/' . $venta->foto) }}" alt="Foto actual"
                        style="max-width: 200px;">
                    <small>Sube nueva para reemplazar</small>
                </div>
            @endif
            <label>Foto (opcional)</label>
            <input type="file" name="txtfoto" class="form-control @error('txtfoto') is-invalid @enderror"
                accept="image/*">
        </div>
    </div>
    <div class="text-center mt-4">
        <button type="submit" class="btn btn-success">Registrar venta</button>
        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">Cancelar</a>
    </div>
    </form>

    <!-- Modal Buscar Cliente por C.I. -->
    <div class="modal fade" id="modalClienteBuscarCI" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buscar por C.I.</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form id="formClienteBuscarCI">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Cédula (DNI)</label>
                            <input type="text" name="txtdni" id="txtdniBuscar" class="form-control" required>
                        </div>

                        <div id="clienteNoEncontrado" class="alert alert-warning" style="display:none;">
                            Cliente no encontrado.
                            <div class="mt-2">
                                <button type="button" id="btnAbrirModalNuevo" class="btn btn-primary">Registrar
                                    cliente</button>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success">Buscar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Cliente (para caso no encontrado) -->
    <div class="modal fade" id="modalClienteNuevo" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nuevo Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <form id="formClienteNuevo">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>DNI</label>
                            <input type="text" name="txtdni" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Nombre</label>
                            <input type="text" name="txtnombre" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Apellido</label>
                            <input type="text" name="txtapellido" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" name="txttelefono" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Dirección</label>
                            <input type="text" name="txtdireccion" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Correo</label>
                            <input type="email" name="txtcorreo" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Aceptar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // Load clientes
            $.get('{{ route('venta.clientes') }}', function(data) {
                data.forEach(function(c) {
                    $('#clienteSelect').append(`<option value="${c.id}">${c.name}</option>`);
                });
            });
        });

        function setClienteSeleccionado(clienteId, nombreCompleto, dni) {
            const optionExists = $('#clienteSelect option[value="' + clienteId + '"]').length > 0;
            if (!optionExists) {
                $('#clienteSelect').append(
                    `<option value="${clienteId}" selected>${nombreCompleto}${dni ? ' - ' + dni : ''}</option>`
                );
            }
            $('#clienteSelect').val(clienteId).trigger('change');
        }

        // Buscar cliente AJAX por DNI
        $('#formClienteBuscarCI').submit(function(e) {
            e.preventDefault();

            const dni = $('#txtdniBuscar').val();

            $.ajax({
                url: "{{ route('venta.clienteBuscarPorDni') }}",
                type: "POST",
                data: {
                    txtdni: dni,
                    _token: $('form#formClienteBuscarCI input[name="_token"]').val()
                },
                success: function(res) {
                    if (res.success) {
                        setClienteSeleccionado(res.id, res.name, res.dni);
                        // Cerrar y asegurar que el modal quede completamente oculto
                        $('#modalClienteBuscarCI').modal('hide');
                        // Forzar cierre inmediato (muy importante con modales en AJAX)
                        setTimeout(function() {
                            try {
                                $('#modalClienteBuscarCI').removeClass('show').hide();
                                $('#modalClienteBuscarCI').attr('aria-hidden', 'true');
                                $('#modalClienteBuscarCI').css('display', 'none');
                                $('.modal-backdrop').remove();
                                $('body').removeClass('modal-open');
                                $('body').css('padding-right', '');
                            } catch (e) {}
                        }, 0);



                        new PNotify({
                            title: "Cliente encontrado",
                            type: "success"
                        });
                    }
                },

                error: function(xhr) {
                    if (xhr.status === 404) {
                        $('#clienteNoEncontrado').show();
                        return;
                    }

                    let msg = 'Error desconocido';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join(', ');
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        msg = xhr.responseJSON.error;
                    }
                    new PNotify({
                        title: "Error",
                        type: "error",
                        text: msg
                    });
                }
            });
        });

        // Registrar desde el aviso naranja
        // Nota: para el flujo pedido (crear cliente y volver a REGISTRAR NUEVA VENTA),
        // redirigimos al formulario de Nuevo Cliente con una variable en sesión.
        // Como no tenemos un endpoint para setear sesión aquí, usamos un redirect con query.
        $('#btnAbrirModalNuevo').click(function() {
            const dni = $('#txtdniBuscar').val();

            // Guardar el DNI en la URL para que el formulario de cliente pueda autocompletar si lo soportas.
            // y redirigir a clientes.create.
            window.location.href =
                `{{ route('clientes.create') }}?venta_regresar=1&venta_dni=${encodeURIComponent(dni)}`;
        });


        // Nuevo cliente AJAX (Aceptar)
        $('#formClienteNuevo').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('venta.storeCliente') }}",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(res) {
                    if (res.success) {
                        const nombre = $('input[name="txtnombre"]').val();
                        const apellido = $('input[name="txtapellido"]').val();
                        const dni = $('input[name="txtdni"]').val();
                        const nombreCompleto = `${nombre} ${apellido}`.trim();

                        setClienteSeleccionado(res.id, nombreCompleto, dni);
                        $('#modalClienteNuevo').modal('hide');
                        $('form#formClienteNuevo')[0].reset();
                        new PNotify({
                            title: "Cliente agregado",
                            type: "success"
                        });
                    }
                },
                error: function(xhr) {
                    let msg = 'Error desconocido';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        msg = Object.values(xhr.responseJSON.errors).flat().join(', ');
                    } else if (xhr.responseJSON && xhr.responseJSON.error) {
                        msg = xhr.responseJSON.error;
                    }
                    new PNotify({
                        title: "Error",
                        type: "error",
                        text: msg
                    });
                }
            });
        });





        // Calcular total
        // Multi product JS
        let productIndex = @json(isset($detalles) ? count($detalles) : 1);

        // Pre-fill if edit
        @if (isset($detalles))
            @foreach ($detalles as $index => $det)
                $('.product-row[data-index="{{ $index }}"] .product-select').val('{{ $det->id_producto }}');
                $('.product-row[data-index="{{ $index }}"] .qty-input').val('{{ $det->cantidad }}');
                $('.product-row[data-index="{{ $index }}"] .price-input').val('{{ $det->precio }}');
                $('.product-row[data-index="{{ $index }}"] .subtotal-input').val('{{ $det->subtotal }}');
            @endforeach
        @endif

        $('#add-product').click(function() {
            const newRow = $('.product-row:first').clone(true);
            newRow.find('select, input').val('').trigger('change');
            newRow.removeAttr('data-index').attr('data-index', productIndex);
            newRow.find('.remove-row').show();
            $('#productos-container').append(newRow);
            productIndex++;
            updateGrandTotal();
        });

        $(document).on('click', '.remove-row', function() {
            if ($('.product-row').length > 1) {
                $(this).closest('.product-row').remove();
                updateGrandTotal();
            }
        });

        $(document).on('change', '.product-select', function() {
            const precio = $(this).find('option:selected').data('precio') || 0;
            // precio ya viene en Bs desde backend
            $(this).closest('.product-row').find('.price-hidden').val(precio);
            updateRowSubtotal($(this).closest('.product-row'));
            updateGrandTotal();
        });

        $(document).on('input', '.qty-input', function() {
            updateRowSubtotal($(this).closest('.product-row'));
            updateGrandTotal();
        });

        function updateRowSubtotal(row) {
            const qty = parseFloat(row.find('.qty-input').val()) || 0;
            const price = parseFloat(row.find('.price-hidden').val()) || 0;
            // Precio y subtotal en Bs.
            row.find('.subtotal-input').val((qty * price).toFixed(2));
        }

        function updateGrandTotal() {
            let total = 0;
            $('.subtotal-input').each(function() {
                total += parseFloat($(this).val()) || 0;
            });
            $('#grand-total').val(total.toFixed(2));
        }

        // Initial calc
        updateGrandTotal();

        // Cliente edit
        @if (isset($venta))
            $('#clienteSelect').val('{{ $venta->id_cliente }}');
        @endif

        // Cliente recién creado desde el flujo de “Nuevo Cliente” (vía sesión desde ClienteController)
        @if (session('cliente'))
            (function() {
                const cliente = @json(session('cliente'));
                const id = cliente.id_cliente;
                const nombreCompleto = `${cliente.nombre ?? ''} ${cliente.apellido ?? ''}`.trim();

                const opt = $('#clienteSelect option[value="' + id + '"]');
                if (!opt.length) {
                    $('#clienteSelect').append(`<option value="${id}" selected>${nombreCompleto}</option>`);
                } else {
                    opt.text(nombreCompleto);
                }

                $('#clienteSelect').val(id).trigger('change');
            })();
        @endif
    </script>


@endsection
