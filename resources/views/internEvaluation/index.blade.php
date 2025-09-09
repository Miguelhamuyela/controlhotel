@extends('layouts.admin.layout')
@section('title', 'Avaliações de Estagiários')
@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <h4>Avaliações de Estagiários</h4>
    <div>
      {{-- 
      <a href="{{ route('internEvaluation.pdfAll') }}" class="btn btn-outline-light btn-sm" title="Baixar PDF">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
      </a>
      --}}
      <a href="{{ route('internEvaluation.create') }}" class="btn btn-outline-light btn-sm" title="Nova Avaliação">
        <i class="bi bi-plus-circle"></i> Nova Avaliação
      </a>
    </div>
  </div>
  <div class="card-body">
    @if($evaluations->count() > 0)
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Estagiário</th>
            <th>Status</th>
            <th>Criado em</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach($evaluations as $evaluation)
          <tr>
            <td>{{ $evaluation->id }}</td>
            <td>{{ $evaluation->intern->fullName ?? '-' }}</td>
            <td>{{ $evaluation->evaluationStatus }}</td>
            <td>{{ $evaluation->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <a href="{{ route('internEvaluation.show', $evaluation->id) }}" class="btn btn-info btn-sm" title="Visualizar">
                <i class="bi bi-eye"></i>
              </a>
              <a href="{{ route('internEvaluation.edit', $evaluation->id) }}" class="btn btn-warning btn-sm" title="Editar">
                <i class="bi bi-pencil"></i>
              </a>
              <a href="#" data-url="{{ url('internEvaluation/'.$evaluation->id.'/delete') }}" class="btn btn-danger btn-sm delete-btn" title="Apagar">
                <i class="bi bi-trash"></i>
              </a>
              <a href="{{ route('internEvaluation.pdf', $evaluation->id) }}" class="btn btn-secondary btn-sm" title="Baixar PDF" target="_blank" rel="noopener noreferrer">
                <i class="bi bi-download"></i>
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
    <p class="text-center">Nenhuma avaliação registrada.</p>
    @endif
  </div>
</div>
@endsection
