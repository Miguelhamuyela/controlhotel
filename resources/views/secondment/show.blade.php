@extends('layouts.admin.layout')
@section('title', 'Visualizar Destacamento')
@section('content')

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="fas fa-id-badge me-2"></i>Detalhes do Destacamento</span>
    <a href="{{ route('secondment.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
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
        <th>Causa da Transferência</th>
        <td>{{ $data->causeOfTransfer ?? '-' }}</td>
      </tr>
      <tr>
        <th>Instituição</th>
        <td>{{ $data->institution }}</td>
      </tr>
      <tr>
        <th>Documento de Suporte</th>
        <td>
          @if($data->supportDocument)
            <a href="{{ asset('uploads/secondments/' . $data->supportDocument) }}" target="_blank">
              {{ $data->originalFileName ?? $data->supportDocument }}
            </a>
          @else
            -
          @endif
        </td>
      </tr>
      <tr>
        <th>Data de Registro</th>
        <td>{{ $data->created_at->format('d/m/Y H:i') }}</td>
      </tr>
    </table>
  </div>
</div>

@endsection
