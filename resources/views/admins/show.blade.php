@extends('layouts.admin.layout')
@section('title', 'Visualizar Administrador')
@section('content')
<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-eye me-2"></i>Detalhes do Administrador</span>
    <a href="{{ route('admins.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
      <i class="bi bi-arrow-left"></i> Voltar
    </a>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr>
        <th>ID</th>
        <td>{{ $admin->id }}</td>
      </tr>
      <tr>
        <th>Email</th>
        <td>{{ $admin->email }}</td>
      </tr>
      <tr>
        <th>Papel</th>
        <td>{{ ucfirst($admin->role) }}</td>
      </tr>
      <tr>
        <th>Funcionário Vinculado</th>
        <td>
          @if($admin->employee)
            {{ $admin->employee->fullName }}<br>
            <small>Email: {{ $admin->employee->email }}</small><br>
            @if($admin->employee->photo)
              <img src="{{ asset('frontend/images/departments/' . $admin->employee->photo) }}" alt="{{ $admin->employee->fullName }}" style="max-height: 150px; border-radius: 50%;">
            @else
              <img src="{{ asset('frontend/images/default.png') }}" alt="{{ $admin->employee->fullName }}" style="max-height: 150px; border-radius: 50%;">
            @endif
          @else
            Não vinculado
          @endif
        </td>
      </tr>
      <tr>
        <th>Data de Registro</th>
        <td>{{ $admin->created_at->format('d/m/Y H:i') }}</td>
      </tr>
    </table>
  </div>
</div>
@endsection
