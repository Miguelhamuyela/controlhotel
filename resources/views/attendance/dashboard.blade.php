@extends('layouts.admin.layout')
@section('title', 'Mapa de Efetividade')
@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
    <div>
      <h4>Mapa de Efetividade</h4>
      <p>Período: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>
    <div>
      <a href="{{ route('attendance.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
        <i class="bi bi-arrow-left"></i> Voltar
      </a>
    </div>
  </div>
  <div class="card-body">
    @if(count($dashboardData))
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Funcionário</th>
              <th>Departamento</th>
              <th>Total Dias Úteis</th>
              <th>Presenças</th>
              <th>Dias Justificados</th>
              <th>Faltas</th>
              <th>Taxa de Presença (%)</th>
            </tr>
          </thead>
          <tbody>
            @foreach($dashboardData as $item)
              <tr>
                <td>{{ $item['employeeName'] }}</td>
                <td>{{ $item['department'] }}</td>
                <td>{{ $item['totalWeekdays'] }}</td>
                <td>{{ $item['presentDays'] }}</td>
                <td>{{ $item['justifiedDays'] }}</td>
                <td>{{ $item['absentDays'] }}</td>
                <td>{{ $item['attendanceRate'] }}%</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p>Nenhum dado de efetividade encontrado para o período selecionado.</p>
    @endif
  </div>
</div>
@endsection
