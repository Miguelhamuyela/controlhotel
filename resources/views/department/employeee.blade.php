@extends('layouts.admin.layout')
@section('title', 'Funcionários do Departamento')
@section('content')

<div class="card mb-4 mt-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span>
      <i class="bi bi-building me-2"></i>
      Funcionários do Departamento: {{ $department->title }}
    </span>

    <!-- Botão para gerar PDF -->
    <div>
      <a href="{{ route('depart.employeee.pdf', $department->id) }}" 
         class="btn btn-outline-light btn-sm" 
         title="Baixar PDF" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
      </a>
      <a href="{{ route('depart.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
        <i class="bi bi-arrow-left"></i> Voltar
      </a>
    </div>
  </div>
  <div class="card-body">
    @if($department->employeee && $department->employeee->count())
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome Completo</th>
              <th>Email</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            @foreach($department->employeee as $employeee)
              <tr>
                <td>{{ $employeee->id }}</td>
                <td>{{ $employeee->fullName }}</td>
                <td>{{ $employeee->email }}</td>
                <td>
                  <a href="{{ route('employeee.show', $employeee->id) }}" class="btn btn-warning btn-sm" title="Visualizar">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="{{ route('employeee.edit', $employeee->id) }}" class="btn btn-info btn-sm" title="Editar">
                    <i class="bi bi-pencil"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p>Não há funcionários cadastrados neste departamento.</p>
    @endif
  </div>
</div>

@endsection
