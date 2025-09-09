@extends('layouts.admin.layout')
@section('title','Trabalhos Extras')
@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="fas fa-briefcase me-2"></i>Trabalhos Extras</span>
    <div>
      <a href="{{ route('extras.pdfAll') }}" class="btn btn-outline-light btn-sm me-2" title="Baixar PDF" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
      </a>
      <a href="{{ route('extras.create') }}" class="btn btn-outline-light btn-sm">
        <i class="bi bi-plus-circle"></i> Adicionar Novo
      </a>
    </div>
  </div>
  <div class="card-body">
    @if(session('msg'))<div class="alert alert-success">{{ session('msg') }}</div>@endif
    <div class="table-responsive">
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Título</th>
            <th>Valor Total</th>
            <th>Participantes</th>
            <th>Status</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach($jobs as $job)
          <tr>
            <td>{{ $job->title }}</td>
            <td>{{ number_format($job->totalValue,2,',','.') }}</td>
            <td>{{ $job->employees->count() }}</td>
            <td>
              <span class="badge bg-{{ $job->statusBadgeColor }}">
                {{ $job->statusInPortuguese }}
              </span>
            </td>
            <td>
                <a href="{{ route('extras.show', $job->id) }}" class="btn btn-warning btn-sm" title="Visualizar"> <i class="bi bi-eye"></i> </a>
                <a href="{{ route('extras.edit', $job->id) }}" class="btn btn-info btn-sm" title="Editar"> <i class="bi bi-pencil"></i> </a>
                 <a href="#" data-url="{{ url("extras/{$job->id}/delete") }}" class="btn btn-danger btn-sm delete-btn" title="Apagar"> 
                  <i class="bi bi-trash"></i>
                </a>

                <a href="{{ route('extras.pdfShow',$job->id) }}" class="btn btn-secondary btn-sm" title="baixar pdf dos participantes" target="_blank" rel="noopener noreferrer">PDF</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

