@extends('layouts.admin.layout')

@section('title', 'Dashboard RH-INFOSI')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Dashboard</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
    </ol>
    <p>Bem-vindo, {{ Auth::user()->employee->fullName ?? Auth::user()->email }}</p>

    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'director')
        <!-- Cards -->
        <div class="row">
            <!-- Total de Funcionários -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        Total de Funcionários: {{ $totalEmployees }}
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('employeee.index') }}">Ver Detalhes</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Funcionários Ativos -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        Funcionários Ativos: {{ $activeEmployees }}
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('employeee.filterByStatus', ['status' => 'active']) }}">Ver Detalhes</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Funcionários Destacados -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        Funcionários Destacados: {{ $highlightedEmployees }}
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('secondment.index') }}">Ver Detalhes</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>

            <!-- Funcionários Reformados -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">
                        Funcionários Reformados: {{ $retiredEmployees }}
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="{{ route('retirements.index') }}">Ver Detalhes</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row">
            <!-- Gráfico de Área (Line Chart) -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-area me-1"></i> Gráfico de Área
                    </div>
                    <div class="card-body">
                        <canvas id="myAreaChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
            <!-- Gráfico de Barras -->
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-bar me-1"></i> Gráfico de Barras
                    </div>
                    <div class="card-body">
                        <canvas id="myBarChart" width="100%" height="40"></canvas>
                    </div>
                </div>
            </div>
            <!-- Gráfico de Pizza -->
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-1"></i> Gráfico de Pizza
                    </div>
                    <div class="card-body">
                        <canvas id="myPieChart" width="100%" height="50"></canvas>
                    </div>
                    <div class="card-footer small text-muted">Atualizado recentemente</div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <strong>Atenção:</strong> Você tem acesso aos menus gerais do sistema, porém não pode visualizar os dados estatísticos do dashboard.
        </div>
    @endif
</div>
@endsection

@section('scripts')

@if(Auth::user()->role === 'admin' || Auth::user()->role === 'director')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Dados dos cards
    var totalEmployees = {{ $totalEmployees }};
    var activeEmployees = {{ $activeEmployees }};
    var highlightedEmployees = {{ $highlightedEmployees }};
    var retiredEmployees = {{ $retiredEmployees }};

    // Dados de contratações por mês (para os gráficos de área e barra)
    var hiredData = @json($hiredData);
    var hiredLabels = Object.keys(hiredData); // Meses
    var hiredCounts = Object.values(hiredData); // Quantidade por mês

    // Gráfico de Área (Line Chart)
    var ctxArea = document.getElementById('myAreaChart').getContext('2d');
    new Chart(ctxArea, {
        type: 'line',
        data: {
            labels: hiredLabels,
            datasets: [{
                label: 'Funcionários Contratados por Mês',
                data: hiredCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Gráfico de Barras
    var ctxBar = document.getElementById('myBarChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: hiredLabels,
            datasets: [{
                label: 'Funcionários Contratados por Mês',
                data: hiredCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Gráfico de Pizza
    var ctxPie = document.getElementById('myPieChart').getContext('2d');
    new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: ['Total de Funcionários', 'Funcionários Ativos', 'Funcionários Destacados', 'Funcionários Reformados'],
            datasets: [{
                label: 'Contagem de Funcionários',
                data: [totalEmployees, activeEmployees, highlightedEmployees, retiredEmployees],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(255, 99, 132, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            }
        }
    });
});

</script>
@endif
@endsection
