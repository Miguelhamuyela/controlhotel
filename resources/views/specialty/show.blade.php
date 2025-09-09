{{--Mostrar--}}
@extends('layouts.admin.layout')
@section('title', 'Ver Especialidade')
@section('content')

<div class="card mb-4 mt-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-eye me-2"></i>Ver Especialidade</span>
    <a href="{{ route('specialties.index') }}" class="btn btn-outline-light btn-sm" title="Ver Todos">
      <i class="bi bi-card-list"></i>
    </a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <tr>
          <th>Nome</th>
          <td>{{ $data->name }}</td>
        </tr>
        <tr>
          <th>Descrição</th>
          <td>{{ $data->description }}</td>
        </tr>
      </table>
    </div>
  </div>
</div>

@endsection
