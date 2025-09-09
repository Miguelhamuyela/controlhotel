@extends('layouts.admin.layout')
@section('title','Editar Tipo — '.ucfirst($category))

@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white">
    <i class="fas fa-edit me-2"></i> Editar Tipo — {{ ucfirst($category) }}
  </div>
  <div class="card-body">
    <div class="row justify-content-center">
      <div class="col-md-6"> {{-- Reduz e centraliza --}}
        <form action="{{ route('material-types.update',[$type->id,'category'=>$category]) }}" method="POST">
          @csrf @method('PUT')
          <input type="hidden" name="category" value="{{ $category }}">

          <div class="mb-3">
            <label class="form-label">Nome do Tipo</label>
            <input type="text" name="name" class="form-control" value="{{ old('name',$type->name) }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Descrição (opcional)</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description',$type->description) }}</textarea>
          </div>

          <div class="text-center">
            <button class="btn btn-primary">
              <i class="fas fa-check me-1"></i> Atualizar
            </button>
            <a href="{{ route('material-types.index',['category'=>$category]) }}" class="btn btn-secondary ms-2">
              Cancelar
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
