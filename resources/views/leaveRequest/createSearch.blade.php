@extends('layouts.admin.layout')
@section('title', 'Novo Pedido de Licença - Selecionar Funcionário')
@section('content')

<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="fas fa-file-alt me-2"></i>Novo Pedido de Licença</span>
    <a href="{{ route('leaveRequest.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
      <i class="bi bi-arrow-left"></i> Voltar
    </a>
  </div>
  <div class="card-body">
    <form action="{{ route('leaveRequest.searchEmployee') }}" method="GET" class="mb-4">
      <div class="row g-3">
        <div class="col-md-8">
          <div class="form-floating">
            <!-- Usamos number para ID, se desejar também texto para nome, pode ser text -->
            <input type="number" name="employeeId" id="employeeId" class="form-control" placeholder="ID do Funcionário" value="{{ old('employeeId') }}">
            <label for="employeeId">ID do Funcionário</label>
          </div>
        </div>
        <div class="col-md-4">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search"></i> Buscar
          </button>
        </div>
      </div>
    </form>

    @isset($employee)
      <hr>
      <h5>Dados do Funcionário:</h5>
      <p><strong>Nome:</strong> {{ $employee->fullName }}</p>
      <p><strong>Departamento:</strong> {{ $employee->department->title ?? '-' }}</p>
      
      <form method="POST" action="{{ route('leaveRequest.store') }}">
        @csrf
        <input type="hidden" name="employeeId" value="{{ $employee->id }}">
        <input type="hidden" name="departmentId" value="{{ $employee->department->id ?? '' }}">
        <div class="mb-3">
          <label for="leaveTypeId" class="form-label">Tipo de Licença</label>
          <select name="leaveTypeId" id="leaveTypeId" class="form-select" required>
            <option value="">-- Selecione o Tipo de Licença --</option>
            @foreach($leaveTypes as $lt)
              <option value="{{ $lt->id }}" {{ old('leaveTypeId') == $lt->id ? 'selected' : '' }}>
                {{ $lt->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="row g-2">
          <div class="col-6">
            <div class="form-floating">
              <input type="date" name="leaveStart" id="leaveStart" class="form-control" value="{{ old('leaveStart') }}" required>
              <label for="leaveStart">Data de Início</label>
            </div>
          </div>
          <div class="col-6">
            <div class="form-floating">
              <input type="date" name="leaveEnd" id="leaveEnd" class="form-control" value="{{ old('leaveEnd') }}" required>
              <label for="leaveEnd">Data de Término</label>
            </div>
          </div>
        </div>
        <div class="mb-3 mt-2">
          <label for="reason" class="form-label">Razão</label>
          <textarea name="reason" id="reason" rows="3" class="form-control">{{ old('reason') }}</textarea>
        </div>
  
        <button type="submit" class="btn btn-success w-100">
          <i class="bi bi-check-circle"></i> Salvar Pedido de Licença
        </button>
      </form>
    @endisset
  </div>
</div>

@endsection
