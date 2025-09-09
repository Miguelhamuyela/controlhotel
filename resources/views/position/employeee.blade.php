@extends('layouts.admin.layout')
@section('title', 'Funcionários por Cargo')
@section('content')

<div class="card mb-4 mt-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span>
      <i class="bi bi-briefcase me-2"></i>
      Funcionários com o Cargo: {{ $position->name }}
    </span>
    <div>
      <a href="{{ route('positions.employeee.pdf', ['positionId' => $position->id]) }}" class="btn btn-outline-light btn-sm" title="Baixar PDF" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> Baixar PDF
      </a>
      <a href="{{ route('positions.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
        <i class="bi bi-arrow-left"></i> Voltar
      </a>
    </div>
  </div>
  <div class="card-body">
    @if($position->employees && $position->employees->count())
      <div class="table-responsive">
        <table class="table table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome Completo</th>
              <th>Email</th>
              <th>Departamento</th>
              <th>Especialidade</th>
            </tr>
          </thead>
          <tbody>
            @foreach($position->employees as $emp)
              <tr>
                <td>{{ $emp->id }}</td>
                <td>{{ $emp->fullName }}</td>
                <td>{{ $emp->email }}</td>
                <td>{{ $emp->department->title ?? '-' }}</td>
                <td>{{ $emp->specialty->name ?? '-' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <p style="text-align:center;">Não há funcionários com este cargo.</p>
    @endif
  </div>
</div>

@endsection
