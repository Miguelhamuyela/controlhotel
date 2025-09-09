@extends('layouts.admin.layout')
@section('title','Detalhes do Trabalho Extra')
@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <h4>{{ $job->title }}</h4>
    <div>
      <a href="{{ route('extras.pdfShow', $job->id) }}" class="btn btn-outline-light btn-sm me-2" title="Baixar PDF" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
      </a>
      <a href="{{ route('extras.index') }}" class="btn btn-outline-light btn-sm">Voltar</a>
    </div>
  </div>
  <div class="card-body">
    <div class="row mb-3">
      <div class="col-md-6">
        <p><strong>Valor Total:</strong> {{ number_format($job->totalValue,2,',','.') }}</p>
      </div>
      <div class="col-md-6">
        <p><strong>Status:</strong> 
          <span class="badge bg-{{ $job->statusBadgeColor }}">
            {{ $job->statusInPortuguese }}
          </span>
        </p>
      </div>
    </div>
    
    <h5>Distribuição</h5>
    <table class="table">
      <thead><tr><th>Funcionário</th><th>Ajus. (Kz)</th><th>Recebe (Kz)</th></tr></thead>
      <tbody>
        @foreach($job->employees as $employee)
        <tr>
          <td>{{ $employee->fullName }}</td>
          <td>{{ number_format($employee->pivot->bonusAdjustment,2,',','.') }}</td>
          <td>{{ number_format($employee->pivot->assignedValue,2,',','.') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

