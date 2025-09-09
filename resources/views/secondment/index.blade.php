@extends('layouts.admin.layout')
@section('title', 'Lista de Destacamentos')
@section('content')

<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="fas fa-id-badge me-2"></i>Lista de Destacamentos</span>
    <div>
      <a href="{{ route('secondment.pdfAll') }}" class="btn btn-outline-light btn-sm" title="Baixar PDF" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
      </a>
      <a href="{{ route('secondment.create') }}" class="btn btn-outline-light btn-sm" title="Novo Destacamento">
        <i class="bi bi-plus-circle"></i> Novo Destacamento
      </a>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Funcionário</th>
            <th>Causa da Transferência</th>
            <th>Instituição</th>
            <th>Documento de Suporte</th>
            <th>Data de Registro</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $s)
            <tr>
              <td>{{ $s->id }}</td>
              <td>{{ $s->employee->fullName ?? '-' }}</td>
              <td>{{ $s->causeOfTransfer ?? '-' }}</td>
              <td>{{ $s->institution ?? '-' }}</td>
              <td>
                @if($s->supportDocument)
                  <a href="{{ asset('uploads/secondments/' . $s->supportDocument) }}" target="_blank">
                    {{ $s->originalFileName ?? $s->supportDocument }}
                  </a>
                @else
                  -
                @endif
              </td>
              <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center">Nenhum destacamento registrado.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
