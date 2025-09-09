@extends('layouts.admin.layout')
@section('title','Tipos de Material — '.($category?ucfirst($category):'Todos'))

@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between">
    <span><i class="fas fa-tags me-2"></i>Tipos — {{ $category?ucfirst($category):'Todos' }}</span>
    <a href="{{ route('material-types.create',['category'=>$category]) }}" class="btn btn-outline-light btn-sm">
      <i class="fas fa-plus-circle me-1"></i> Novo Tipo
    </a>
  </div>
  <div class="card-body">
    @if(session('msg'))
      <div class="alert alert-success">{{ session('msg') }}</div>
    @endif
    <table class="table table-striped">
      <thead>
        <tr><th>Nome</th><th>Descrição</th><th>Ações</th></tr>
      </thead>
      <tbody>
        @forelse($types as $t)
          <tr>
            <td>{{ $t->name }}</td>
            <td>{{ $t->description ?? '—' }}</td>
            <td class="text-center">
              <a href="{{ route('material-types.show',[$t->id,'category'=>$category]) }}"
                 class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
              <a href="{{ route('material-types.edit',[$t->id,'category'=>$category]) }}"
                 class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
              <a href="#"
                 data-url="{{ route('material-types.delete',[$t->id,'category'=>$category]) }}"
                 class="btn btn-sm btn-danger delete-btn"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
        @empty
          <tr><td colspan="3" class="text-center">Nenhum tipo cadastrado.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
