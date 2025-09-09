@extends('layouts.admin.layout')
@section('title', 'Nova Especialidade')
@section('content')

<div class="card mb-4 mt-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-plus-circle me-2"></i>Adicionar Especialidade</span>
    <a href="{{ route('specialties.index') }}" class="btn btn-outline-light btn-sm" title="Ver Todos"> ver
      <i class="bi bi-card-list"></i>
    </a>
  </div>
  <div class="card-body">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <form method="POST" action="{{ route('specialties.store') }}">
          @csrf
          

          <div class="mb-3">
            <div class="form-floating">
              <input type="text" name="name" id="name" class="form-control" placeholder="Nome da Especialidade">
              <label for="name">Nome da Especialidade</label>
            </div>
          </div>
          
        
          <div class="mb-3">
            <div class="form-floating">
              <textarea name="description" id="description" class="form-control" placeholder="Descrição (Opcional)" style="height: 100px;"></textarea>
              <label for="description">Descrição (Opcional)</label>
            </div>
          </div>
          
 
          <div class="d-grid gap-2 col-6 mx-auto mt-4">
            <button type="submit" class="btn btn-primary btn-lg">
              <i class="bi bi-check-circle me-2"></i>Criar Especialidade
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
