@extends('layouts.admin.layout')
@section('title', 'Meus Funcionários')
@section('content')

@php
    // Obtém o funcionário vinculado ao usuário logado (chefe de departamento)
    $departmentHead = auth()->user()->employee;
@endphp

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white">
    <h4>Meus Funcionários</h4>
  </div>
  <div class="card-body">
    <!-- Exibe o nome do chefe de departamento no topo -->
    <div class="mb-3">
        <strong>Chefe de Departamento:</strong> {{ $departmentHead->fullName ?? 'Não definido' }}
    </div>
    
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Posição</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @forelse($employees as $emp)
          <tr>
            <td>{{ $emp->fullName }}</td>
            <td>{{ $emp->position->name ?? '-' }}</td>
            <td>
              <a href="{{ route('dh.downloadEmployeeVacationPdf', $emp->id) }}" class="btn btn-sm btn-outline-primary" title="Baixar PDF de Férias" target="_blank" rel="noopener noreferrer">
                <i class="bi bi-file-earmark-pdf"></i> Férias
              </a>
              <a href="{{ route('dh.downloadEmployeeLeavePdf', $emp->id) }}" class="btn btn-sm btn-outline-secondary" title="Baixar PDF de Licença" target="_blank" rel="noopener noreferrer">
                <i class="bi bi-file-earmark-pdf"></i> Licença
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="3">Nenhum funcionário encontrado.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
