@extends('layouts.admin.layout')
@section('title', 'Editar Tipo de Funcionário')
@section('content')

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-pencil-square me-2"></i>Editar Tipo de Funcionário</span>
    <a href="{{ route('employeeType.index') }}" class="btn btn-outline-light btn-sm" title="Ver Todos">
      <i class="bi bi-card-list"></i>
    </a>
  </div>
  <div class="card-body">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form method="POST" action="{{ route('employeeType.update', $data->id) }}">
          @csrf
          @method('PUT')
        
          <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $data->name) }}" required>
          </div>
        
          <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $data->description) }}</textarea>
          </div>
       
          <div class="text-center">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-circle"></i> Atualizar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
