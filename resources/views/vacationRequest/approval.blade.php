@extends('layouts.admin.layout')
@section('title', 'Aprovação de Pedidos de Férias')
@section('content')

<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white">
    <h4 class="mb-0">Pedidos de Férias para Aprovação</h4>
  </div>
  <div class="card-body">
    @if(session('msg'))
      <div class="alert alert-success">{{ session('msg') }}</div>
    @endif
    <div class="table-responsive">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>ID</th>
            <th>Funcionário</th>
            <th>Data de Início</th>
            <th>Data de Fim</th>
            <th>Tipo</th>
            <th>Status</th>
            <th>Comentário</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $vr)
          <tr>
            <td>{{ $vr->id }}</td>
            <td>{{ $vr->employee->fullName ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($vr->vacationStart)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($vr->vacationEnd)->format('d/m/Y') }}</td>
            <td>{{ $vr->vacationType }}</td>
            <td>
              @if($vr->approvalStatus == 'Aprovado')
                <span class="badge bg-success">Aprovado</span>
              @elseif($vr->approvalStatus == 'Recusado')
                <span class="badge bg-danger">Recusado</span>
              @else
                <span class="badge bg-warning">Pendente</span>
              @endif
            </td>
            <td>
              <textarea name="approvalComment" class="form-control form-control-sm" placeholder="Digite o comentário">{{ $vr->approvalComment }}</textarea>
            </td>
            <td>
              <div class="d-flex flex-column gap-1">
                <form action="{{ route('dh.approveVacation', $vr->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-success btn-sm">
                    <i class="bi bi-check-circle"></i> Aprovar
                  </button>
                </form>
                <form action="{{ route('dh.rejectVacation', $vr->id) }}" method="POST">
                  @csrf
                  <button type="submit" class="btn btn-danger btn-sm">
                    <i class="bi bi-x-circle"></i> Rejeitar
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center">Nenhum pedido de férias para aprovação.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
