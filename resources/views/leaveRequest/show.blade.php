@extends('layouts.admin.layout')
@section('title', 'Detalhes do Pedido de Licença')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card mb-4 shadow">
      <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <span>Detalhes do Pedido de Licença</span>
        <a href="{{ route('leaveRequest.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
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
            <th>Tipo de Licença</th>
            <td>{{ $data->leaveType->name ?? '-' }}</td>
          </tr>
          <tr>
            <th>Departamento</th>
            <td>{{ $data->department->title ?? '-' }}</td>
          </tr>
          <tr>
            <th>Data de Início</th>
            <td>{{ \Carbon\Carbon::parse($data->leaveStart)->format('d/m/Y') }}</td>
          </tr>
          <tr>
            <th>Data de Término</th>
            <td>{{ \Carbon\Carbon::parse($data->leaveEnd)->format('d/m/Y') }}</td>
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
