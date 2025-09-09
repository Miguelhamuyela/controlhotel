@extends('layouts.admin.layout')
@section('title','Editar Categoria de Carta')
@section('content')

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-pencil me-2"></i>Editar Categoria #{{ $licenseCategory->id }}</span>
    <a href="{{ route('licenseCategories.index') }}" class="btn btn-outline-light btn-sm" title="Ver Todas">
      <i class="bi bi-card-list"></i>
    </a>
  </div>

  <div class="card-body">
    <form action="{{ route('licenseCategories.update', $licenseCategory->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row g-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input
              type="text"
              name="name"
              id="name"
              class="form-control"
              placeholder="Nome"
              value="{{ old('name', $licenseCategory->name) }}"
              maxlength="50"
              required
            >
            <label for="name">Nome</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input
              type="text"
              name="description"
              id="description"
              class="form-control"
              placeholder="Descrição (opcional)"
              value="{{ old('description', $licenseCategory->description) }}"
            >
            <label for="description">Descrição</label>
          </div>
        </div>
      </div>

      <div class="d-grid gap-2 col-md-4 mx-auto mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="bi bi-check-circle me-2"></i>Atualizar Categoria
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
