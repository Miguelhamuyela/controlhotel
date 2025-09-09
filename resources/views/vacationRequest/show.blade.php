@extends('layouts.admin.layout')
@section('title', 'Detalhes do Pedido de Férias')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card mb-4 shadow">
      <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <span>Detalhes do Pedido de Férias</span>
        <a href="{{ route('vacationRequest.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
          <i class="bi bi-arrow-left"></i> Voltar
        </a>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <tr>
            <th>ID</th>
            <td>{{ $data->id }}</td>
          </tr>
          <tr>
            <th>Funcionário</th>
            <td>{{ $data->employee->fullName ?? '-' }}</td>
          </tr>
          <tr>
            <th>Tipo de Férias</th>
            <td>{{ $data->vacationType }}</td>
          </tr>
          <tr>
            <th>Data de Início</th>
            <td>{{ \Carbon\Carbon::parse($data->vacationStart)->format('d/m/Y') }}</td>
          </tr>
          <tr>
            <th>Data de Fim</th>
            <td>{{ \Carbon\Carbon::parse($data->vacationEnd)->format('d/m/Y') }}</td>
          </tr>
          <tr>
            <th>Documento</th>
            <td>
              @if($data->supportDocument)
                <a href="{{ asset('storage/' . $data->supportDocument) }}" target="_blank">
                  {{ $data->originalFileName ?? 'Ver Documento' }}
                </a>
              @else
                -
              @endif
            </td>
          </tr>
          <tr>
            <th>Razão</th>
            <td>{{ $data->reason ?? '-' }}</td>
          </tr>
          <tr>
            <th>Status</th>
            <td>{{ $data->approvalStatus }}</td>
          </tr>
          <tr>
            <th>Comentário</th>
            <td>{{ $data->approvalComment ?? '-' }}</td>
          </tr>
          <tr>
            <th>Criado em</th>
            <td>{{ $data->created_at->format('d/m/Y H:i') }}</td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
