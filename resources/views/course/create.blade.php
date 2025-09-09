@extends("layouts.admin.layout")
@section("title", "Criar Curso")
@section("content")

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-plus-circle me-2"></i>Novo Curso</span>
    <a href="{{ route("course.index") }}" class="btn btn-outline-light btn-sm" title="Ver Todos">
      <i class="bi bi-card-list"></i>
    </a>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route("course.store") }}">
      @csrf
      <div class="row g-3">
        <div class="col-md-12">
          <div class="form-floating mb-3">
            <input type="text" name="name" id="name" class="form-control" placeholder="Nome do Curso" value="{{ old("name") }}">
            <label for="name">Nome do Curso</label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-floating">
            <textarea name="description" id="description" class="form-control" placeholder="Descrição do Curso" style="height: 100px;">{{ old("description") }}</textarea>
            <label for="description">Descrição do Curso</label>
          </div>
        </div>
      </div>
      <div class="d-grid gap-2 col-6 mx-auto mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="bi bi-check-circle me-2"></i>Cadastrar Curso
        </button>
      </div>
    </form>
  </div>
</div>

@endsection


