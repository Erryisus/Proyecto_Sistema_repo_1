@extends('layouts.app')
@section('titulo', 'Reporte de productos')

@section('content')
<h5 class="text-center text-secondary mb-4">REPORTE DE PRODUCTOS</h5>

<form method="GET" action="{{ route('producto.reporte') }}">
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-light border-0">
                    <i class="fas fa-warehouse text-primary"></i>
                </span>
                <input type="number" name="bajo_stock" value="{{ $bajo_stock }}" min="0" class="form-control border-0 shadow-none" placeholder="Stock mínimo">
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100 shadow-lg">
                <i class="fas fa-search me-2"></i>Filtrar
            </button>
        </div>
        <div class="col-lg-3 col-md-6 mb-3 d-flex align-items-end">
            <a href="{{ route('producto.reporte') }}" class="btn btn-outline-secondary w-100">
                <i class="fas fa-redo me-2"></i>Limpiar
            </a>
        </div>
    </div>
</form>

{{-- Metrics --}}
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-lg-6">
        <div class="card h-100 border-0 shadow-lg hover-lift">
            <div class="card-body text-center p-4">
                <div class="metric-icon mb-3">
                    <i class="fas fa-boxes fa-3x text-primary"></i>
                </div>
                <h2 class="display-5 fw-bold text-primary mb-1">{{ number_format($total_productos) }}</h2>
                <p class="text-muted h6 mb-0">Total Productos</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card h-100 border-0 shadow-lg hover-lift">
            <div class="card-body text-center p-4">
                <div class="metric-icon mb-3">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                </div>
                <h2 class="display-5 fw-bold text-warning mb-1">{{ number_format($bajo_stock_count) }}</h2>
                <p class="text-muted h6 mb-0">Bajo Stock (<{{ $bajo_stock }})</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card h-100 border-0 shadow-lg hover-lift">
            <div class="card-body text-center p-4">
                <div class="metric-icon mb-3">
                    <i class="fas fa-coins fa-3x text-success"></i>
                </div>
                <h2 class="display-5 fw-bold text-success mb-1">S/. {{ number_format($total_value, 2) }}</h2>
                <p class="text-muted h6 mb-0">Valor Total Inventario</p>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-lg-6">
        <div class="card h-100 border-0 shadow-lg hover-lift">
            <div class="card-body text-center p-4">
                <div class="metric-icon mb-3">
                    <i class="fas fa-tag fa-3x text-info"></i>
                </div>
                <h2 class="display-5 fw-bold text-info mb-1">S/. {{ number_format($avg_price, 2) }}</h2>
                <p class="text-muted h6 mb-0">Precio Promedio</p>
            </div>
        </div>
    </div>
</div>

{{-- Charts & Tables --}}
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-xl h-100">
            <div class="card-header bg-primary text-white p-4 border-0">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Top 10 Productos por Valor
                </h5>
            </div>
            <div class="card-body p-4">
                <div style="position: relative; height:400px;">
                    <canvas id="productosChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-xl h-100">
            <div class="card-header bg-success text-white p-4 border-0">
                <h5 class="mb-0">
                    <i class="fas fa-list-ol me-2"></i>
                    Productos por Categoría
                </h5>
            </div>
            <div class="card-body p-3">
                <div class="table-responsive" style="height: 400px;">
                    <table class="table table-hover table-sm mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Categoría</th>
                                <th>Prod.</th>
                                <th>Valor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($productos_categoria as $index => $cat)
                            <tr>
                                <td>{{ Str::limit($cat->nombre, 20) }}</td>
                                <td class="text-center fw-bold">{{ $cat->total }}</td>
                                <td class="text-end fw-bold">S/. {{ number_format($cat->valor_total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('productosChart').getContext('2d');
const productosChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($top_productos->pluck('nombre')),
        datasets: [{
            label: 'Valor Total (S/.)',
            data: @json($top_productos->pluck('valor_total')),
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

