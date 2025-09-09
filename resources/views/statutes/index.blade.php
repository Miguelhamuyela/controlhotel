@extends('layouts.admin.layout')
@section('title', 'Estatutos')
@section('content')
<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-file-earmark-text me-2"></i>Lista de Estatutos</span>
    <a href="{{ route('statutes.create') }}" class="btn btn-outline-light btn-sm">
      <i class="bi bi-plus-circle"></i> Adicionar Novo
    </a>
  </div>
  <div class="card-body">
    @if(session('msg'))
      <div class="alert alert-success">{{ session('msg') }}</div>
    @endif
    @if($statutes->count())
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Título</th>
            <th>Data de Criação</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach($statutes as $statute)
            <tr>
              <td>{{ $statute->title }}</td>
              <td>{{ $statute->created_at->format('d/m/Y') }}</td>
              <td>
                <a href="{{ route('statutes.show', $statute->id) }}" class="btn btn-info btn-sm">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('statutes.edit', $statute->id) }}" class="btn btn-warning btn-sm">
                  <i class="bi bi-pencil"></i>
                </a>
                <!-- botão de exclusão -->
                <a href="#" 
                   data-url="{{ route('statutes.delete', $statute->id) }}" 
                   class="btn btn-danger btn-sm delete-btn">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p>Nenhum estatuto cadastrado.</p>
    @endif
  </div>
</div>
@endsection
