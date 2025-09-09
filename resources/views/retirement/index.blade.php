@extends('layouts.admin.layout')
@section('title', 'Pedidos de Reforma')
@section('content')

<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="fas fa-user-check me-2"></i>Lista de Pedidos de Reforma</span>
    <div>
      <!-- PDF de todos -->
      <a href="{{ route('retirements.pdf') }}" class="btn btn-outline-light btn-sm" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF (Todos)
      </a>

       <!-- PDF filtrado -->
    @if(request()->filled('startDate') || request()->filled('endDate') || (request()->filled('status') && request('status')!=='Todos'))
    <a href="{{ route('retirements.pdf') }}?{{ http_build_query(request()->only(['startDate','endDate','status'])) }}"
       class="btn btn-outline-light btn-sm" target="_blank" rel="noopener noreferrer">
      <i class="bi bi-file-earmark-pdf"></i> Baixar PDF (Filtrados)
    </a>
    @endif

      <a href="{{ route('retirements.create') }}" class="btn btn-outline-light btn-sm">
        <i class="bi bi-plus-circle"></i> Novo Pedido
      </a>
    </div>
  </div>

  <div class="card-body">
    <!-- Formulário de filtros -->
    <form method="GET" action="{{ route('retirements.index') }}" class="row g-3 mb-4">
      <div class="col-md-3">
        <label class="form-label">Data do Pedido Início</label>
        <input
          type="date"
          name="startDate"
          class="form-control"
          value="{{ request('startDate') }}"
        >
      </div>
      <div class="col-md-3">
        <label class="form-label">Data do Pedido Fim</label>
        <input
          type="date"
          name="endDate"
          class="form-control"
          value="{{ request('endDate') }}"
        >
      </div>
      <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
          <option value="Todos"   {{ request('status') === 'Todos'   ? 'selected' : '' }}>Todos</option>
          <option value="Aprovado"{{ request('status') === 'Aprovado'? 'selected' : '' }}>Aprovado</option>
          <option value="Recusado"{{ request('status') === 'Recusado'? 'selected' : '' }}>Recusado</option>
          <option value="Pendente"{{ request('status') === 'Pendente'? 'selected' : '' }}>Pendente</option>
        </select>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary w-100">
          <i class="bi bi-filter"></i> Filtrar
        </button>
      </div>
    </form>
    <!-- /Formulário de filtros -->

    @if($retirements->count())
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <th>Funcionário</th>
              <th>Data do Pedido</th>
              <th>Data de Reforma</th>
              <th>Status</th>
              <th>Observações</th>
              <th>Criado em</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            @foreach($retirements as $retirement)
              <tr>
                <td>{{ $retirement->employee->fullName ?? '-' }}</td>
                <td>{{ \Carbon\Carbon::parse($retirement->requestDate)->format('d/m/Y') }}</td>
                <td>
                  {{ $retirement->retirementDate
                      ? \Carbon\Carbon::parse($retirement->retirementDate)->format('d/m/Y')
                      : '-' }}
                </td>
                <td>
                  @if($retirement->status == 'Aprovado')
                    <span class="badge bg-success">Aprovado</span>
                  @elseif($retirement->status == 'Recusado')
                    <span class="badge bg-danger">Recusado</span>
                  @else
                    <span class="badge bg-warning">Pendente</span>
                  @endif
                </td>
                <td>{{ $retirement->observations ?? '-' }}</td>
                <td>{{ $retirement->created_at->format('d/m/Y H:i') }}</td>
                <td>
                  <a href="{{ route('retirements.show', $retirement->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="{{ route('retirements.edit', $retirement->id) }}" class="btn btn-info btn-sm">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <form
                    action="{{ route('retirements.destroy', $retirement->id) }}"
                    method="POST"
                    style="display:inline-block;"
                  >
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p class="text-center">Nenhum pedido de reforma registrado.</p>
    @endif

  </div>
</div>

@endsection
