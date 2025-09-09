@extends('layouts.admin.layout')
@section('title', 'Criar Tipo de Licença')
@section('content')

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-file-contract me-2"></i>Novo Tipo de Licença</span>
    <a href="{{ route('leaveType.index') }}" class="btn btn-outline-light btn-sm" title="Ver Todos"> Ver
      <i class="bi bi-card-list"></i>
    </a>
  </div>
  <div class="card-body">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form method="POST" action="{{ route('leaveType.store') }}">
          @csrf
         
          <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
          </div>

         
          <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
          </div>
        
          <div class="text-center">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-circle me-2"></i>Salvar Tipo de Licença
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
