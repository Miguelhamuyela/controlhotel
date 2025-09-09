@extends('layouts.admin.layout')
@section('title', 'Lista de Mobilidades')
@section('content')

<div class="card mb-4 mt-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-arrow-left-right me-2"></i>Lista de Mobilidades</span>
    <div>
      <a href="{{ route('mobility.pdfAll') }}" class="btn btn-outline-light btn-sm" title="Baixar PDF" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
      </a>
      <a href="{{ route('mobility.create') }}" class="btn btn-outline-light btn-sm" title="Nova Mobilidade">
        <i class="bi bi-plus-circle"></i> Nova Mobilidade
      </a>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-hover table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Funcionário</th>
            <th>Departamento Antigo</th>
            <th>Novo Departamento</th>
            <th>Causa</th>
            <th>Data</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $mob)
            <tr>
              <td>{{ $mob->id }}</td>
              <td>{{ $mob->employee->fullName ?? '-' }}</td>
              <td>{{ $mob->oldDepartment->title ?? '-' }}</td>
              <td>{{ $mob->newDepartment->title ?? '-' }}</td>
              <td>{{ $mob->causeOfMobility ?? '-' }}</td>
              <td>{{ $mob->created_at->format('d/m/Y H:i') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center">Nenhuma mobilidade registrada.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
