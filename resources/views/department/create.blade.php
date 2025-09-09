@extends('layouts.admin.layout')
@section('title', 'Novo Departamento')
@section('content')

<div class="card mb-4 mt-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-plus-circle me-2"></i>Novo Departamento</span>
    <a href="{{ route('depart.index') }}" class="btn btn-outline-light btn-sm" title="Ver Todos"> Ver
      <i class="bi bi-card-list"></i>
    </a>
  </div>  
  <div class="card-body">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form method="POST" action="{{ route('depart.store') }}">
          @csrf
          
          <!-- Título do Departamento -->
          <div class="mb-3">
            <div class="form-floating">
              <input type="text" name="title" class="form-control" id="title" placeholder="Título do Departamento" value="{{ old('title') }}">
              <label for="title">Título do Departamento</label>
            </div>
          </div>

          <!-- Descrição do Departamento -->
          <div class="mb-3">
            <div class="form-floating">
              <textarea name="description" class="form-control" id="description" placeholder="Descrição do Departamento" style="height: 100px;">{{ old('description') }}</textarea>
              <label for="description">Descrição do Departamento</label>
            </div>
          </div>
          
          <!-- Botão de envio -->
          <div class="d-grid gap-2 col-6 mx-auto mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="bi bi-check-circle me-2"></i>Criar Departamento
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
