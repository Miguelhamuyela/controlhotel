@extends('layouts.admin.layout')
@section('title', 'Visualizar Estatuto')
@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card my-4 shadow">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
          <span><i class="bi bi-file-earmark-text me-2"></i>Detalhes do Estatuto</span>
          <a href="{{ route('statutes.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
            <i class="bi bi-arrow-left"></i> Voltar
          </a>
        </div>
        <div class="card-body">
          <h3>{{ $statute->title }}</h3>
          <p class="text-muted">Criado em: {{ $statute->created_at->format('d/m/Y') }}</p>
          <div class="mb-3">
            <p>{{ $statute->description }}</p>
          </div>
          @if($statute->document)
            <div class="mb-3">
              <a href="{{ asset('uploads/statutes/' . $statute->document) }}" target="_blank" class="btn btn-info">
                <i class="bi bi-eye"></i> Visualizar Documento
              </a>
            </div>
          @endif
          <a href="{{ route('statutes.edit', $statute->id) }}" class="btn btn-warning">
            <i class="bi bi-pencil-square"></i> Editar
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection