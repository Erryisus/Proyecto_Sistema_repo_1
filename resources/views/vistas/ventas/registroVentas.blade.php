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
                    <div class="col-md-5" style="position:relative;">
                        <input type="text" class="form-control product-search"
                            placeholder="Buscar por nombre o código..." autocomplete="off" required>
                        <input type="hidden" name="productos[]" class="product-id-hidden" required>
                        <div class="search-results"
                            style="position:absolute; left:0; right:0; top:100%; z-index:9999; background:#fff; border:1px solid #ddd; display:none; max-height:240px; overflow:auto;">
                        </div>
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
        @php
            $fechaDefault =
                isset($venta) && !empty($venta->fecha)
                    ? \Carbon\Carbon::parse($venta->fecha)->format('Y-m-d')
                    : \Carbon\Carbon::now()->format('Y-m-d');
        @endphp

        <div class="col-md-6">
            <label>Fecha</label>
            <input type="date" name="txtfecha" id="txtfecha"
                class="form-control @error('txtfecha') is-invalid @enderror" value="{{ $fechaDefault }}" required readonly
                tabindex="-1" style="pointer-events: none; background-color: #e9ecef; color: #495057;">
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
                            <input type="text" name="txtcedula" id="txtcedulaBuscar" class="form-control" required>
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
                            <input type="text" name="txtcedula" class="form-control" required>
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

        function setClienteSeleccionado(clienteId, nombreCompleto, cedula) {
            const optionExists = $('#clienteSelect option[value="' + clienteId + '"]').length > 0;
            if (!optionExists) {
                $('#clienteSelect').append(
                    `<option value="${clienteId}" selected>${nombreCompleto}${cedula ? ' - ' + cedula : ''}</option>`
                );
            }
            $('#clienteSelect').val(clienteId).trigger('change');
        }

        // Buscar cliente AJAX por Cédula
        $('#formClienteBuscarCI').submit(function(e) {
            e.preventDefault();

            const cedula = $('#txtcedulaBuscar').val();

            $.ajax({
                url: "{{ route('venta.clienteBuscarPorDni') }}",
                type: "POST",
                data: {
                    txtcedula: cedula,
                    _token: $('form#formClienteBuscarCI input[name="_token"]').val()
                },
                success: function(res) {
                    if (res.success) {
                        setClienteSeleccionado(res.id, res.name, res.cedula);

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
            const cedula = $('#txtcedulaBuscar').val();

            // Guardar la cédula en la URL para que el formulario de cliente pueda autocompletar si lo soportas.
            window.location.href =
                `{{ route('clientes.create') }}?venta_regresar=1&venta_cedula=${encodeURIComponent(cedula)}`;
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
                        const cedula = $('input[name="txtcedula"]').val();
                        const nombreCompleto = `${nombre} ${apellido}`.trim();

                        setClienteSeleccionado(res.id, nombreCompleto, cedula);
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
        // Pre-fill if edit (ajustado al nuevo buscador)
        @if (isset($detalles))
            @foreach ($detalles as $index => $det)
                @php
                    $detPrecioBs = $det->precio ?? ($det->precio_unitario ?? 0);
                @endphp
                const prefillRow{{ $index }} = $('.product-row[data-index="{{ $index }}"]');
                prefillRow{{ $index }}.find('.product-id-hidden').val('{{ $det->id_producto }}');
                prefillRow{{ $index }}.find('.product-search').val(
                    '{{ $det->producto_nombre ?? ($det->nombre ?? '') }}');
                prefillRow{{ $index }}.find('.price-hidden').val('{{ $det->precio ?? 0 }}');
                prefillRow{{ $index }}.find('.qty-input').val('{{ $det->cantidad }}');
                prefillRow{{ $index }}.find('.subtotal-input').val('{{ $det->subtotal }}');
            @endforeach
        @endif

        $('#add-product').click(function() {
            const newRow = $('.product-row:first').clone(true);

            // Limpiar buscador/valores del producto clonado
            newRow.find('.product-search').val('');
            newRow.find('.product-id-hidden').val('');
            newRow.find('.price-hidden').val('');
            newRow.find('.qty-input').val('1');
            newRow.find('.subtotal-input').val('');

            newRow.removeAttr('data-index').attr('data-index', productIndex);
            newRow.find('.remove-row').show();

            // Ocultar resultados del dropdown al clonar
            newRow.find('.search-results').hide().html('');

            $('#productos-container').append(newRow);
            productIndex++;
            updateGrandTotal();
        });

        $(document).on('click', '.remove-row', function() {
            const row = $(this).closest('.product-row');
            const allRows = $('.product-row');

            // Si hay más de una fila, eliminarla completamente.
            if (allRows.length > 1) {
                row.remove();
                updateGrandTotal();
                return;
            }

            // Si es la última fila, NO eliminar: resetear/limpiar inputs internos.
            row.find('.product-id-hidden').val('');
            row.find('.product-search').val('');
            row.find('.price-hidden').val('');
            row.find('.qty-input').val('');
            row.find('.subtotal-input').val('');

            // Asegurar que el dropdown de resultados quede limpio.
            row.find('.search-results').hide().html('');

            updateGrandTotal();
        });

        // Buscar producto AJAX (nombre o codigo) y seleccionar
        // usando endpoint existente: producto.buscar
        const tasaCambiariaBs = @json(isset($tasaCambiaria) ? $tasaCambiaria : 1);

        function debounce(fn, wait) {
            let t;
            return function(...args) {
                clearTimeout(t);
                t = setTimeout(() => fn.apply(this, args), wait);
            };
        }

        function renderResults(container, items) {
            if (!items || items.length === 0) {
                container.html('<div style="padding:8px;color:#777;">Sin resultados</div>');
                container.show();
                return;
            }

            const html = items.map(p => {
                const codigo = p.codigo ?? '';
                const nombre = p.nombre ?? '';

                // Endpoint retorna precio (USD según el código del sistema). Convertimos a Bs.
                const precioUsd = parseFloat(p.precio ?? 0) || 0;
                const precioBs = precioUsd * parseFloat(tasaCambiariaBs || 1);
                const precioLabel =
                    `(${precioBs.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })} Bs.)`;

                return `
                    <div class="product-result-item"
                        style="padding:8px; cursor:pointer; border-bottom:1px solid #eee;"
                        data-id="${p.id_producto}"
                        data-precio-bs="${precioBs}"
                        data-nombre="${nombre.replace(/"/g,'"')}"
                        data-codigo="${codigo}">
                        <div style="font-weight:600;">${nombre} <small style="color:#666;">(${codigo})</small></div>
                        <div style="color:#333;">${precioLabel}</div>
                    </div>
                `;
            }).join('');

            container.html(html);
            container.show();
        }

        function hideResults(container) {
            container.hide();
            container.html('');
        }

        $(document).on('input', '.product-search', debounce(function() {
            const row = $(this).closest('.product-row');
            const container = row.find('.search-results');

            const txt = ($(this).val() || '').trim();
            if (txt.length < 1) {
                hideResults(container);
                return;
            }

            const token = $('meta[name="csrf-token"]').attr('content') || $('input[name="_token"]').first()
                .val();

            $.ajax({
                url: "{{ route('producto.buscar') }}",
                method: 'POST',
                data: {
                    buscar: txt,
                    _token: token
                },
                success: function(res) {
                    if (res && res.success) {
                        renderResults(container, res.dato);
                    } else {
                        renderResults(container, []);
                    }
                },
                error: function() {
                    renderResults(container, []);
                }
            });
        }, 250));

        $(document).on('click', '.product-result-item', function() {
            const item = $(this);
            const pickedRow = item.closest('.product-row');

            const idProducto = item.data('id');
            const precioBs = parseFloat(item.data('precio-bs')) || 0;
            const nombre = item.data('nombre') || '';
            const codigo = item.data('codigo') || '';

            // 1) Verificar si el producto ya existe en alguna fila actual.
            //    Si existe, en vez de llenar una fila nueva, incrementamos la cantidad de la fila existente.
            const matchingRow = $(
                `.product-row .product-id-hidden[value="${String(idProducto).replace(/\\/g, '\\\\').replace(/\"/g, '\\"')}"]`
            ).closest('.product-row');

            if (matchingRow && matchingRow.length > 0 && matchingRow.is('.product-row')) {
                // Incrementar por 1 (o por la cantidad seleccionada si la había en la fila pulsada).
                const qtyToAdd = (() => {
                    const v = parseFloat(pickedRow.find('.qty-input').val());
                    return (!pickedRow.find('.qty-input').val() || Number.isNaN(v) || v <= 0) ? 1 : v;
                })();

                const prevQty = parseFloat(matchingRow.find('.qty-input').val());
                const newQty = (Number.isNaN(prevQty) || prevQty <= 0 ? 0 : prevQty) + qtyToAdd;

                matchingRow.find('.qty-input').val(newQty);
                // Asegurar que precio y subtotal estén coherentes
                matchingRow.find('.price-hidden').val(precioBs);
                updateRowSubtotal(matchingRow);
                updateGrandTotal();

                // Limpiar dropdown de la fila actual
                hideResults(pickedRow.find('.search-results'));
                return;
            }

            // 2) Si no existe, se aplica la selección en la fila donde se hizo click.
            pickedRow.find('.product-id-hidden').val(idProducto);
            pickedRow.find('.product-search').val(`${nombre} (${codigo})`);
            pickedRow.find('.price-hidden').val(precioBs);

            // Si la cantidad viene vacía (o inválida) por defecto asignar 1 para facturación rápida
            const currentQty = parseFloat(pickedRow.find('.qty-input').val());
            if (!pickedRow.find('.qty-input').val() || Number.isNaN(currentQty) || currentQty <= 0) {
                pickedRow.find('.qty-input').val('1');
            }

            updateRowSubtotal(pickedRow);
            updateGrandTotal();

            hideResults(pickedRow.find('.search-results'));
        });

        $(document).on('input', '.qty-input', function() {
            updateRowSubtotal($(this).closest('.product-row'));
            updateGrandTotal();
        });

        // Cerrar dropdown si se hace click fuera
        $(document).on('click', function(e) {
            if ($(e.target).closest('.product-row').length === 0) {
                $('.search-results').hide().html('');
            }
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
