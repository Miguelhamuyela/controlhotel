@extends('layouts.admin.layout')
@section('title', 'Funcionários por Especialidade')
@section('content')

<div class="card mb-4 mt-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span>
      <i class="bi bi-star me-2"></i>
      Funcionários com a Especialidade: {{ $specialty->name }}
    </span>
    <div>
      <!-- Botão para gerar PDF para essa especialidade -->
      <a href="{{ route('specialties.pdf', ['specialtyId' => $specialty->id]) }}" 
         class="btn btn-outline-light btn-sm" title="Baixar PDF" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
      </a>
      <a href="{{ route('specialties.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
        <i class="bi bi-arrow-left"></i> Voltar
      </a>
    </div>
  </div>
  <div class="card-body">
    @if($specialty->employees && $specialty->employees->count())
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome Completo</th>
              <th>Email</th>
              <th>Cargo</th>
              <th>Departamento</th>
            </tr>
          </thead>
          <tbody>
            @foreach($specialty->employees as $emp)
              <tr>
                <td>{{ $emp->id }}</td>
                <td>{{ $emp->fullName }}</td>
                <td>{{ $emp->email }}</td>
                <td>{{ $emp->position->name ?? '-' }}</td>
                <td>{{ $emp->department->title ?? '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p style="text-align:center;">Não há funcionários com esta especialidade.</p>
    @endif
  </div>
</div>

@endsection
