@extends('layouts/app')
@section('titulo', 'Reporte de ventas')

@section('content')
<h5 class="text-center text-secondary">REPORTE DE VENTAS</h5>

<form method="GET" action="{{ route('venta.reporte') }}">
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-light border-0">
                    <i class="fas fa-calendar-alt text-primary"></i>
                </span>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="form-control border-0 shadow-none" placeholder="Desde">
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-light border-0">
                    <i class="fas fa-calendar-alt text-primary"></i>
                </span>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="form-control border-0 shadow-none" placeholder="Hasta">
            </div>
        </div>
        <div class="col-lg-2 col-md-6 mb-3 d-flex align-items-end">
            <a href="{{ route('venta.reporte') }}" class="btn btn-outline-secondary w-100">
                <i class="fas fa-redo me-2"></i>Limpiar
            </a>
        </div>
        <div class="col-lg-2 col-md-6 mb-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100 shadow-lg">
                <i class="fas fa-search me-2"></i>Filtrar
            </button>
        </div>
        <div class="col-lg-2 col-md-6 mb-3 d-flex align-items-end">
            <a href="{{ route('venta.reporte.pdf', ['fecha_desde' => request('fecha_desde', date('Y-m-01')), 'fecha_hasta' => request('fecha_hasta', date('Y-m-d'))]) }}" class="btn btn-success w-100 shadow-lg" target="_blank">
                <i class="fas fa-download me-2"></i>PDF
            </a>
        </div>
    </div>
</form>

{{-- Nueva Estructura Metrics --}}
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-lg-6">
        <div class="card h-100 border-0 shadow-lg hover-lift">
            <div class="card-body text-center p-4">
                <div class="metric-icon mb-3">
                    <i class="fas fa-shopping-cart fa-3x text-primary"></i>
                </div>
                <h2 class="display-5 fw-bold text-primary mb-1">{{ $total_ventas }}</h2>
                <p class="text-muted h6 mb-0">Total Ventas</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card h-100 border-0 shadow-lg hover-lift">
            <div class="card-body text-center p-4">
                <div class="metric-icon mb-3">
                    <i class="fas fa-coins fa-3x text-success"></i>
                </div>
                <h2 class="display-5 fw-bold text-success mb-1">S/. {{ number_format($total_ingresos, 2) }}</h2>
                <p class="text-muted h6 mb-0">Ingresos Totales</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card h-100 border-0 shadow-lg hover-lift">
            <div class="card-body text-center p-4">
                <div class="metric-icon mb-3">
                    <i class="fas fa-percentage fa-3x text-info"></i>
                </div>
               <h2 class="display-5 fw-bold text-info mb-1">{{ $total_ventas > 0 ? number_format($total_ingresos / $total_ventas, 2) : '0.00' }}</h2>
                <p class="text-muted h6 mb-0">Ticket Promedio</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card h-100 border-0 shadow-lg hover-lift">
            <div class="card-body text-center p-4">
                <div class="metric-icon mb-3">
                    <i class="fas fa-chart-pie fa-3x text-warning"></i>
                </div>
                <h2 class="display-5 fw-bold text-warning mb-1">{{ $top_productos->count() }}</h2>
                <p class="text-muted h6 mb-0">Productos Líder</p>
            </div>
        </div>
    </div>
</div>

{{-- Charts & Table --}}
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-xl h-100">
            <div class="card-header bg-primary text-white p-4 border-0">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Evolución de Ventas por Fecha
                </h5>
            </div>
            <div class="card-body p-4">
                <div style="position: relative; height:400px;">
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-xl h-100">
            <div class="card-header bg-success text-white p-4 border-0">
                <h5 class="mb-0">
                    <i class="fas fa-list-ol me-2"></i>
                    Top Productos
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive" style="height: 400px;">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th>Ingr.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($top_productos->slice(0, 10) as $index => $p)
                            <tr>
                                <td class="fw-bold text-primary">{{ $index + 1 }}</td>
                                <td>{{ Str::limit($p->nombre, 20) }}</td>
                                <td class="text-center fw-bold">{{ $p->cantidad }}</td>
                                <td class="text-end fw-bold">S/. {{ number_format($p->ingresos, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


    <div class="col-md-6">
        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cant. Vendida</th>
                    <th>Ingresos</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($top_productos as $p)
                <tr>
                    <td>{{ $p->nombre }}</td>
                    <td>{{ $p->cantidad }}</td>
                    <td>S/. {{ number_format($p->ingresos, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
const ctx = document.getElementById('ventasChart').getContext('2d');
const ventasChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($fechas) !!},
        datasets: [{
            label: 'Ventas',
            data: {!! json_encode($ingresos_fechas) !!},
backgroundColor: 'rgba(54, 162, 235, 0.2)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>

@endsection

