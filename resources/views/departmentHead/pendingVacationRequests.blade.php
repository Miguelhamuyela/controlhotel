@extends('layouts.admin.layout')
@section('title', 'Pedidos de Férias Pendentes')
@section('content')

<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Pedidos de Férias Pendentes</h4>
  </div>
  <div class="card-body">

    @if(session('msg'))
      <div class="alert alert-success">{{ session('msg') }}</div>
    @endif

    {{-- FILTRO POR DATAS --}}
    <form method="GET" action="{{ route('dh.pendingVacations') }}" class="row g-3 mb-4">
      <div class="col-md-4">
        <label for="from" class="form-label">De</label>
        <input type="date" name="from" id="from" value="{{ old('from', $from) }}" class="form-control">
      </div>
      <div class="col-md-4">
        <label for="to" class="form-label">Até</label>
        <input type="date" name="to" id="to" value="{{ old('to', $to) }}" class="form-control">
      </div>
      <div class="col-md-4 align-self-end">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-funnel-fill me-1"></i>Filtrar
        </button>
        <a href="{{ route('dh.pendingVacations') }}" class="btn btn-secondary">
          <i class="bi bi-arrow-clockwise me-1"></i>Limpar
        </a>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Funcionário</th>
            <th>Data Início</th>
            <th>Data Fim</th>
            <th>Tipo</th>
            <th>Status</th>
            <th>Comentário</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pendingRequests as $req)
            <tr>
              <td>{{ $req->id }}</td>
              <td>{{ $req->employee->fullName ?? '-' }}</td>
              <td>{{ \Carbon\Carbon::parse($req->vacationStart)->format('d/m/Y') }}</td>
              <td>{{ \Carbon\Carbon::parse($req->vacationEnd)->format('d/m/Y') }}</td>
              <td>{{ $req->vacationType }}</td>
              <td>
                @if($req->approvalStatus == 'Aprovado')
                  <span class="badge bg-success fs-6">Aprovado</span>
                @elseif($req->approvalStatus == 'Pendente')
                  <span class="badge bg-warning fs-6">Pendente</span>
                @elseif($req->approvalStatus == 'Recusado')
                  <span class="badge bg-danger fs-6">Recusado</span>
                @else
                  <span>{{ $req->approvalStatus }}</span>
                @endif
              </td>
              <td>
                <textarea id="comment-{{ $req->id }}"
                          class="form-control"
                          rows="1"
                          placeholder="Comentário"></textarea>
              </td>
              <td>
                <div class="d-flex flex-column flex-md-row">
                  {{-- Aprovar --}}
                  <form action="{{ route('dh.approveVacation', $req->id) }}"
                        method="POST"
                        onsubmit="document.getElementById('hidden-approve-{{ $req->id }}').value = document.getElementById('comment-{{ $req->id }}').value"
                        class="me-md-2 mb-2 mb-md-0">
                    @csrf
                    <input type="hidden" id="hidden-approve-{{ $req->id }}" name="approvalComment">
                    <button type="submit" class="btn btn-success btn-sm">
                      <i class="bi bi-check-circle"></i> Aprovar
                    </button>
                  </form>
                  {{-- Rejeitar --}}
                  <form action="{{ route('dh.rejectVacation', $req->id) }}"
                        method="POST"
                        onsubmit="document.getElementById('hidden-reject-{{ $req->id }}').value = document.getElementById('comment-{{ $req->id }}').value">
                    @csrf
                    <input type="hidden" id="hidden-reject-{{ $req->id }}" name="approvalComment">
                    <button type="submit" class="btn btn-danger btn-sm">
                      <i class="bi bi-x-circle"></i> Rejeitar
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="text-center">Nenhum pedido de férias pendente.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>

@endsection
