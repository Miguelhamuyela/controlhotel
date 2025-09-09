@extends('layouts.admin.layout')
@section('title', 'Funcionários do Departamento')
@section('content')
<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="fas fa-users me-2"></i>Funcionários do Meu Departamento</span>
    <a href="{{ route('departmentHead.pendingVacationRequests') }}" class="btn btn-outline-light btn-sm" title="Ver Pedidos de Férias">
      <i class="fas fa-umbrella-beach"></i> Pedidos de Férias
    </a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          @forelse($employees as $emp)
            <tr>
              <td>{{ $emp->id }}</td>
              <td>{{ $emp->fullName }}</td>
              <td>{{ $emp->email }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center">Nenhum funcionário encontrado.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
