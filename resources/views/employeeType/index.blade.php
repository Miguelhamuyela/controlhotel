@extends('layouts.admin.layout')
@section('title', 'Lista de Tipos de Funcionários')
@section('content')

<div class="card mb-4 mt-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-card-list me-2"></i>Lista de Tipos de Funcionários</span>
    <a href="{{ route('employeeType.create') }}" class="btn btn-outline-light btn-sm" title="Novo Tipo de Funcionário">
      <i class="bi bi-plus-circle"></i>
    </a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $type)
            <tr>
              <td>{{ $type->id }}</td>
              <td>{{ $type->name }}</td>
              <td>{{ $type->description ?? '-' }}</td>
              <td>
                <a href="{{ route('employeeType.show', $type->id) }}" class="btn btn-warning btn-sm" title="Visualizar">
                    <i class="bi bi-eye"></i>
                  </a>
                <a href="{{ route('employeeType.edit', $type->id) }}" class="btn btn-info btn-sm" title="Editar">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="#" data-url="{{ url('employeeType/'.$type->id.'/delete') }}" class="btn btn-danger btn-sm delete-btn" title="Apagar">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center">Nenhum tipo de funcionário cadastrado.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
