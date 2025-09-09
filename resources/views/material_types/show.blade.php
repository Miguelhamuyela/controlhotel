@extends('layouts.admin.layout')
@section('title','Ver Tipo — '.ucfirst($category))

@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-eye me-2"></i>Tipo — {{ ucfirst($category) }}</span>
    <div>
      <a href="{{ route('material-types.index',['category'=>$category]) }}" class="btn btn-outline-light btn-sm">
        <i class="bi bi-card-list"></i>
      </a>
      <a href="{{ route('material-types.edit',[$type->id,'category'=>$category]) }}" class="btn btn-warning btn-sm">
        <i class="bi bi-pencil"></i>
      </a>
      <a href="#"
         data-url="{{ route('material-types.delete',[$type->id,'category'=>$category]) }}"
         class="btn btn-danger btn-sm delete-btn">
        <i class="bi bi-trash"></i>
      </a>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-striped table-bordered mb-0">
      <tr><th>Nome</th>      <td>{{ $type->name }}</td></tr>
      <tr><th>Descrição</th> <td>{{ $type->description ?? '—' }}</td></tr>
      <tr><th>Criado em</th> <td>{{ $type->created_at->format('d/m/Y') }}</td></tr>
      <tr><th>Atualizado</th><td>{{ $type->updated_at->format('d/m/Y') }}</td></tr>
    </table>
  </div>
</div>
@endsection
