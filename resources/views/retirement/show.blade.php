@extends('layouts.admin.layout')
@section('title', 'Detalhes do Pedido de Reforma')
@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span>Detalhes do Pedido de Reforma</span>
    <a href="{{ route('retirements.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
      <i class="bi bi-arrow-left"></i> Voltar
    </a>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr>
        <th>Funcionário</th>
        <td>{{ $retirement->employee->fullName ?? '-' }}</td>
      </tr>
      <tr>
        <th>Data do Pedido</th>
        <td>{{ \Carbon\Carbon::parse($retirement->requestDate)->format('d/m/Y') }}</td>
      </tr>
      <tr>
        <th>Data de Reforma</th>
        <td>{{ $retirement->retirementDate ? \Carbon\Carbon::parse($retirement->retirementDate)->format('d/m/Y') : '-' }}</td>
      </tr>
      <tr>
        <th>Status</th>
        <td>{{ $retirement->status }}</td>
      </tr>
      <tr>
        <th>Observações</th>
        <td>{{ $retirement->observations ?? '-' }}</td>
      </tr>
      <tr>
        <th>Criado em</th>
        <td>{{ $retirement->created_at->format('d/m/Y H:i') }}</td>
      </tr>
    </table>
  </div>
</div>
@endsection
