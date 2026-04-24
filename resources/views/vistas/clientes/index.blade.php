@extends('layouts/app')
@section('titulo', 'Lista de Clientes')

@section('content')
    @if (session('CORRECTO'))
        <script>
            $(function() {
                new PNotify({
                    title: "ÉXITO",
                    type: "success",
                    text: "{{ session('CORRECTO') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif

    @if (session('INCORRECTO'))
        <script>
            $(function() {
                new PNotify({
                    title: "ERROR",
                    type: "error",
                    text: "{{ session('INCORRECTO') }}",
                    styling: "bootstrap3"
                });
            });
        </script>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Lista de Clientes</h4>
        <a href="{{ route('clientes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nuevo Cliente
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th style="color: black;">C.I</th>
                    <th style="color: black;">Nombre Completo</th>
                    <th style="color: black;">Teléfono</th>
                    <th style="color: black;">Correo</th>
                    <th style="color: black;">Dirección</th>
                    <th style="color: black;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $cliente)
                    <tr>
                        <td><strong>{{ $cliente->dni }}</strong></td>
                        <td>{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                        <td>{{ $cliente->telefono }}</td>
                        <td>{{ $cliente->correo ?? 'N/A' }}</td>
                        <td>{{ Str::limit($cliente->direccion ?? 'N/A', 30) }}</td>
                        <td>
                            <a href="{{ route('clientes.edit', $cliente->id_cliente) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('clientes.destroy', $cliente->id_cliente) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar este cliente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No hay clientes registrados</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $clientes->links('pagination::bootstrap-4') }}
    </div>
@endsection
