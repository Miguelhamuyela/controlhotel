@extends('layouts.admin.layout')
@section('title', 'Estagiários')
@section('content')

<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-person-lines-fill me-2"></i>Todos os Estagiários</span>
    <div>
      <a href="{{ route('intern.pdfAll') }}" class="btn btn-outline-light btn-sm" title="Baixar PDF" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
      </a>
  
      <a href="{{ route('intern.filter') }}" class="btn btn-outline-light btn-sm" title="Filtrar por Data">
        <i class="bi bi-calendar-event"></i> Filtrar
      </a>

      <a href="{{ route('intern.create') }}" class="btn btn-outline-light btn-sm" title="Adicionar novo Estagiário"> Novo
        <i class="bi bi-plus-circle"></i>
      </a>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="datatablesSimple" class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome Completo</th>
            <th>Departamento</th>
            <th>Cargo</th>
            <th>Especialidade</th>
            <th>Endereço</th>
            <th>Email</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tfoot>
          <tr>
            <th>ID</th>
            <th>Nome Completo</th>
            <th>Departamento</th>
            <th>Cargo</th>
            <th>Especialidade</th>
            <th>Endereço</th>
            <th>Email</th>
            <th>Ações</th>
          </tr>
        </tfoot>
        <tbody>
          @if ($data)
            @foreach($data as $d)
              <tr>
                <td>{{ $d->id }}</td>
                <td>{{ $d->fullName ?? 'Nome não definido' }}</td>
                <td>{{ $d->department->title ?? 'Departamento não encontrado' }}</td>
                <td>{{ $d->position->name ?? 'Cargo não encontrado' }}</td>
                <td>{{ $d->specialty->name ?? 'Especialidade não encontrada' }}</td>
                <td>{{ $d->address ?? 'Endereço não definido' }}</td>
                <td>{{ $d->email ?? 'Email não definido' }}</td>
                <td>
                  <a href="{{ route('intern.show', $d->id) }}" class="btn btn-warning btn-sm" title="Visualizar">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="{{ route('intern.edit', $d->id) }}" class="btn btn-info btn-sm" title="Editar">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="#" data-url="{{ url('intern/'.$d->id.'/delete') }}" class="btn btn-danger btn-sm delete-btn" title="Apagar">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
