@extends('layouts.admin.layout')
@section('title', 'Pedidos de Licença')
@section('content')

<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="fas fa-file-alt me-2"></i>Lista de Pedidos de Licença</span>
    <div>
      <a href="{{ route('leaveRequest.pdfAll') }}" class="btn btn-outline-light btn-sm" title="Baixar PDF" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF (Todos)
      </a>
      
       <!-- PDF filtrado -->
    @if(request()->filled('startDate') || request()->filled('endDate') || (request()->filled('status') && request('status')!=='Todos'))
    <a href="{{ route('leaveRequest.pdfAll') }}?{{ http_build_query(request()->only(['startDate','endDate','status'])) }}"
       class="btn btn-outline-light btn-sm" target="_blank" rel="noopener noreferrer">
      <i class="bi bi-file-earmark-pdf"></i> Baixar PDF (Filtrados)
    </a>
    @endif


      <a href="{{ route('leaveRequest.create') }}" class="btn btn-outline-light btn-sm">
        <i class="bi bi-plus-circle"></i> Novo Pedido
      </a>
    </div>
  </div>

  <div class="card-body">
    <form method="GET" action="{{ route('leaveRequest.index') }}" class="row g-3 mb-4">
      <div class="col-md-3">
        <label class="form-label">Data Início</label>
        <input type="date" name="startDate" class="form-control" value="{{ request('startDate') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Data Fim</label>
        <input type="date" name="endDate" class="form-control" value="{{ request('endDate') }}">
      </div>
      <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="Todos" {{ request('status')==='Todos' ? 'selected' : '' }}>Todos</option>
          <option value="Aprovado" {{ request('status')==='Aprovado' ? 'selected' : '' }}>Aprovado</option>
          <option value="Recusado" {{ request('status')==='Recusado' ? 'selected' : '' }}>Recusado</option>
          <option value="Pendente" {{ request('status')==='Pendente' ? 'selected' : '' }}>Pendente</option>
        </select>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-filter"></i> Filtrar
        </button>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Funcionário</th>
            <th>Tipo de Licença</th>
            <th>Departamento</th>
            <th>Data Início</th>
            <th>Data Término</th>
            <th>Razão</th>
            <th>Status</th>
            <th>Comentário</th>
            <th>Criado em</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $lr)
          <tr>
            <td>{{ $lr->employee->fullName ?? '-' }}</td>
            <td>{{ $lr->leaveType->name ?? '-' }}</td>
            <td>{{ $lr->department->title ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($lr->leaveStart)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($lr->leaveEnd)->format('d/m/Y') }}</td>
            <td>{{ $lr->reason ?? '-' }}</td>
            <td>
              @if($lr->approvalStatus == 'Aprovado')
                <span class="badge bg-success">Aprovado</span>
              @elseif($lr->approvalStatus == 'Recusado')
                <span class="badge bg-danger">Recusado</span>
              @else
                <span class="badge bg-warning">Pendente</span>
              @endif
            </td>
            <td>{{ $lr->approvalComment ?? '-' }}</td>
            <td>{{ $lr->created_at->format('d/m/Y H:i') }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="9" class="text-center">Nenhum pedido de licença listado.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
