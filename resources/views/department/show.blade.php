@extends('layouts.admin.layout')
@section('title', 'Ver Departamento')
@section('content')

<div class="card mb-4 mt-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-building me-2"></i>Ver Departamento</span>
    <a href="{{ route('depart.index') }}" class="btn btn-outline-light btn-sm" title="Ver Todos os Departamentos">
      <i class="bi bi-card-list"></i>
    </a>
  </div>  
  <div class="card-body">
    <table class="table table-bordered">
      <tr>
        <th>Título</th>
        <td>{{ $data->title }}</td>
      </tr>
      <tr>
        <th>Descrição</th>
        <td>{{ $data->description ?? '-' }}</td>
      </tr>
    </table>
  </div>
</div>

@endsection

