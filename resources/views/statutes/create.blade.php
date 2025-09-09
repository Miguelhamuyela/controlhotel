@extends('layouts.admin.layout')
@section('title', 'Criar Estatuto')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card my-4 shadow">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <span><i class="bi bi-file-earmark-text me-2"></i>Novo Estatuto</span>
          <a href="{{ route('statutes.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
            <i class="bi bi-arrow-left"></i> Voltar
          </a>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('statutes.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
              <label for="title" class="form-label">Título do Estatuto</label>
              <input type="text" class="form-control" name="title" id="title" required>
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Descrição</label>
              <textarea class="form-control" name="description" id="description" rows="5" required></textarea>
            </div>
            <div class="mb-3">
              <label for="document" class="form-label">Documento</label>
              <input type="file" class="form-control" name="document" id="document">
            </div>
            <div class="d-grid">
              <button type="submit" class="btn btn-success">
                <i class="bi bi-check-circle"></i> Salvar Estatuto
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection