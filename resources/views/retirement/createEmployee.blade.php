@extends('layouts.admin.layout')
@section('title', 'Adicionar Pedido de Reforma')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card mb-4 shadow">
      <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <h4>Adicionar Pedido de Reforma</h4>
        <a href="{{ route('retirements.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
          <i class="bi bi-arrow-left"></i> Voltar
        </a>
      </div>
      <div class="card-body">
        <!-- Dados do Funcionário -->
        <div class="mb-3">
          <h5>Dados do Funcionário</h5>
          <p><strong>Nome:</strong> {{ $employee->fullName }}</p>
          <p><strong>E-mail:</strong> {{ $employee->email }}</p>
          <p><strong>Departamento:</strong> {{ $employee->department->title ?? '-' }}</p>
        </div>
        <!-- Formulário de Pedido de Reforma -->
        <form method="POST" action="{{ route('retirements.store') }}">
          @csrf
          <input type="hidden" name="employeeId" value="{{ $employee->id }}">
          <!-- Campo oculto para definir o status como "Pendente" -->
          <input type="hidden" name="status" value="Pendente">
          <div class="mb-3">
            <label for="retirementDate" class="form-label">Data de Reforma</label>
            <input type="date" name="retirementDate" id="retirementDate" class="form-control">
          </div>
          <div class="mb-3">
            <label for="observations" class="form-label">Observações</label>
            <textarea name="observations" id="observations" class="form-control"></textarea>
          </div>
          <button type="submit" class="btn btn-success w-100">
            <i class="bi bi-check-circle"></i> Enviar Pedido 
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
