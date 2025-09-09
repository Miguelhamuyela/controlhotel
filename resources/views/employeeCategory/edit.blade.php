@extends("layouts.admin.layout")
@section("title", "Editar Categoria de Funcionário")
@section("content")

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-pencil-square me-2"></i>Editar Categoria de Funcionário</span>
    <a href="{{ route("employeeCategory.index") }}" class="btn btn-outline-light btn-sm" title="Ver Todas">
      <i class="bi bi-card-list"></i>
    </a>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route("employeeCategory.update", $employeeCategory->id) }}">
      @csrf
      @method("PUT")
      <div class="row g-3">
        <div class="col-md-12">
          <div class="form-floating mb-3">
            <input type="text" name="name" id="name" class="form-control" placeholder="Nome da Categoria" value="{{ old("name", $employeeCategory->name) }}">
            <label for="name">Nome da Categoria</label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-floating">
            <textarea name="description" id="description" class="form-control" placeholder="Descrição da Categoria" style="height: 100px;">{{ old("description", $employeeCategory->description) }}</textarea>
            <label for="description">Descrição da Categoria</label>
          </div>
        </div>
      </div>
      <div class="d-grid gap-2 col-6 mx-auto mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="bi bi-save2 me-2"></i>Atualizar Categoria
        </button>
      </div>
    </form>
  </div>
</div>

@endsection


