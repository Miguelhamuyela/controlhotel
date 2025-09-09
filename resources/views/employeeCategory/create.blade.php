@extends("layouts.admin.layout")
@section("title", "Criar Categoria de Funcionário")
@section("content")

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-plus-circle me-2"></i>Nova Categoria de Funcionário</span>
    <a href="{{ route("employeeCategory.index") }}" class="btn btn-outline-light btn-sm" title="Ver Todas">
      <i class="bi bi-card-list"></i>
    </a>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route("employeeCategory.store") }}">
      @csrf
      <div class="row g-3">
        <div class="col-md-12">
          <div class="form-floating mb-3">
            <input type="text" name="name" id="name" class="form-control" placeholder="Nome da Categoria" value="{{ old("name") }}">
            <label for="name">Nome da Categoria</label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-floating">
            <textarea name="description" id="description" class="form-control" placeholder="Descrição da Categoria" style="height: 100px;">{{ old("description") }}</textarea>
            <label for="description">Descrição da Categoria</label>
          </div>
        </div>
      </div>
      <div class="d-grid gap-2 col-6 mx-auto mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="bi bi-check-circle me-2"></i>Cadastrar Categoria
        </button>
      </div>
    </form>
  </div>
</div>

@endsection


