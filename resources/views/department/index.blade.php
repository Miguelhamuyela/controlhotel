@extends('layouts.admin.layout')
@section('title', 'Departments (Departamentos)')
@section('content')

<div class="card mb-4 mt-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-building me-2"></i>Lista de Departamentos</span>
    <a href="{{ route('depart.create') }}" class="btn btn-outline-light btn-sm" title="Adicionar Novo"> Novo
      <i class="bi bi-plus-circle"></i>
    </a>
  </div>
  <div class="card-body">
    
    <!-- Formulário para selecionar departamento e listar seus funcionários -->
    <div class="mt-4">
      <p class="mb-3  text-muted">Listar funcionários por departamento:</p>
      <form action="{{ route('depart.employeee') }}" method="GET" class="d-inline-flex">
        <div class="input-group w-auto">
          <select name="department" class="form-select" style="max-width: 300px;" required>
            <option value="">Selecione o Departamento</option>
            @foreach($data as $d)
              <option value="{{ $d->id }}">{{ $d->title }}</option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-primary" title="Pesquisar">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>
    </div><br>


    <table id="datatablesSimple" class="table table-striped table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Título</th>
          <th>Descrição</th>
          <th>Ação</th>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th>ID</th>
          <th>Título</th>
          <th>Descrição</th>
          <th>Ação</th>
        </tr>
      </tfoot>
      <tbody>
        @if ($data)
          @foreach($data as $d)
            <tr>
              <td>{{ $d->id }}</td>
              <td>{{ $d->title }}</td>
              <td>{{ $d->description ?? '-' }}</td>
              <td>
                <a href="{{ route('depart.show', $d->id) }}" class="btn btn-warning btn-sm" title="Visualizar">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('depart.edit', $d->id) }}" class="btn btn-info btn-sm" title="Editar">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="#" data-url="{{ url('depart/'.$d->id.'/delete') }}" class="btn btn-danger btn-sm delete-btn" title="Apagar">
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

@endsection
