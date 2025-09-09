@extends('layouts.admin.layout')
@section('title', 'Lista de Cargos')
@section('content')

<div class="card mb-4 mt-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-card-list me-2"></i>Lista de Cargos</span>
    <a href="{{ route('positions.create') }}" class="btn btn-outline-light btn-sm" title="Novo Cargo"> Novo
      <i class="bi bi-plus-circle"></i>
    </a>
  </div>
  <div class="card-body">

     <!-- Filtro para listar funcionários por Cargo -->
     <div class="mt-4">
      <p class="mb-3 small text-muted">Listar funcionários por cargo:</p>
      <form action="{{ route('positions.employeee.filter') }}" method="GET" class="d-inline-flex">
        <div class="input-group w-auto">
          
          <!-- $allPositions: lista de todos os cargos (passada pelo controller) -->
          <select name="position" class="form-select" style="max-width: 250px;" required>
            <option value="">Selecione o Cargo</option>
            @foreach($allPositions as $p)
              <option value="{{ $p->id }}">{{ $p->name }}</option>
            @endforeach
          </select>
          <button type="submit" class="btn btn-primary" title="Pesquisar">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>
    </div><br>

    <div class="table-responsive">
      <table id="datatablesSimple" class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $d)
            <tr>
              <td>{{ $d->id }}</td>
              <td>{{ $d->name }}</td>
              <td>{{ $d->description ?? '-' }}</td>
              <td>
                <a href="{{ route('positions.show', $d->id) }}" class="btn btn-warning btn-sm" title="Visualizar">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('positions.edit', $d->id) }}" class="btn btn-info btn-sm" title="Editar">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="#" data-url="{{ url('positions/'.$d->id.'/delete') }}" class="btn btn-danger btn-sm delete-btn" title="Apagar">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

   

  </div>
</div>

@endsection
