@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    {{-- Hero Section --}}
    <div class="row mb-6">
        <div class="col-12 text-center mb-5">
            <div class="hero-card">
                <h1 class="display-3 fw-bold mb-3">
                    <i class="fas fa-tachometer-alt text-primary me-3"></i>
                    Panel Principal
                </h1>
                <p class="lead text-muted fs-4">Sistema de Ventas - Dashboard Ejecutivo</p>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-xl-3 col-lg-6">
            <div class="stat-card primary">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $total_usuario }}</h3>
                    <p>Usuarios</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="stat-card success">
                <div class="stat-icon">
                    <i class="fas fa-user-friends"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $total_cliente }}</h3>
                    <p>Clientes</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="stat-card warning">
                <div class="stat-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $total_venta }}</h3>
                    <p>Ventas</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-6">
            <div class="stat-card info">
                <div class="stat-icon">
                    <i class="fas fa-boxes"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $total_producto }}</h3>
                    <p>Productos</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="card-header">
                    <h4>Ventas Mensuales {{ date('Y') }}</h4>
                </div>
                <div class="chart-container">
                    <canvas id="ventasChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="card-header">
                    <h4>Productos Top</h4>
                </div>
                <div class="chart-container">
                    <canvas id="productosChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row g-4 mt-5">
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('ventas.create') }}" class="action-card primary">
                <i class="fas fa-plus-circle fa-3x"></i>
                <h5>Nueva Venta</h5>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('productos.create') }}" class="action-card success">
                <i class="fas fa-box fa-3x"></i>
                <h5>Nuevo Producto</h5>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('venta.reporte') }}" class="action-card info">
                <i class="fas fa-chart-bar fa-3x"></i>
                <h5>Reportes</h5>
            </a>
        </div>
        <div class="col-lg-3 col-md-6">
            <a href="{{ route('usuario.index') }}" class="action-card warning">
                <i class="fas fa-users fa-3x"></i>
                <h5>Usuarios</h5>
            </a>
        </div>
    </div>
</div>

<script>
const ventasCtx = document.getElementById('ventasChart')?.getContext('2d');
if (ventasCtx) {
    new Chart(ventasCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($meses ?? []) !!},
            datasets: [{
                data: {!! json_encode($ventas ?? []) !!},
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.4,
                fill: true,
                backgroundColor: 'rgba(75, 192, 192, 0.1)'
            }]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
}
</script>

<style>
:root {
    --primary: #0d6efd;
    --success: #198754;
    --info: #0dcaf0;
}

.hero-card {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(20px);
    padding: 3rem 2rem;
    border-radius: 25px;
    border: 1px solid rgba(255,255,255,0.2);
}

.stat-card {
    display: flex;
    align-items: center;
    padding: 2rem;
    border-radius: 20px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.stat-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.stat-card.primary { background: linear-gradient(135deg, var(--primary), #0b5ed7); }
.stat-card.success { background: linear-gradient(135deg, var(--success), #157347); }
.stat-card.warning { background: linear-gradient(135deg, #ffc107, #e0a800); }
.stat-card.info { background: linear-gradient(135deg, var(--info), #31d2f2); }

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 15px;
    margin-right: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
}

.stat-content h3 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 0.25rem;
}

.chart-card {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}

.chart-container {
    position: relative;
    height: 400px;
    padding: 2rem;
}

.action-card {
    height: 150px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
    border-radius: 20px;
    transition: all 0.3s ease;
}

.action-card:hover {
    transform: scale(1.05);
    text-decoration: none;
    color: white;
}

.action-card i {
    margin-bottom: 1rem;
    transition: transform 0.3s ease;
}

.action-card:hover i {
    transform: scale(1.2) rotate(10deg);
}

@media (max-width: 768px) {
    .stat-card { margin-bottom: 1.5rem; }
    .chart-container { height: 350px !important; padding: 1rem; }
}
</style>
@endsection

