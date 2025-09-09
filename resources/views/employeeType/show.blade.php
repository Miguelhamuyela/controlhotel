@extends('layouts.admin.layout')
@section('title', 'Ver Tipo de Funcionário')
@section('content')

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-eye me-2"></i>Ver Tipo de Funcionário</span>
    <a href="{{ route('employeeType.index') }}" class="btn btn-outline-light btn-sm" title="Ver Todos">
      <i class="bi bi-card-list"></i>
    </a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered">
        <tr>
          <th>Nome</th>
          <td>{{ $data->name }}</td>
        </tr>
        <tr>
          <th>Descrição</th>
          <td>{{ $data->description ?? '-' }}</td>
        </tr>
      </table>
    </div>
  </div>
</div>

@endsection
