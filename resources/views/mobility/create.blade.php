@extends('layouts.admin.layout')
@section('title', 'Nova Mobilidade')
@section('content')

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-arrow-left-right me-2"></i>Nova Mobilidade</span>
    <a href="{{ route('mobility.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
      <i class="bi bi-arrow-left"></i> Voltar
    </a>
  </div>
  <div class="card-body">
    <!-- Formulário para buscar o funcionário por ID ou Nome -->
    @if(!isset($employee))
      <form action="{{ route('mobility.searchEmployee') }}" method="GET" class="mb-4">
        <div class="row g-3">
          <div class="col-md-8">
            <div class="form-floating">
              <input type="text" name="employeeSearch" id="employeeSearch" class="form-control" placeholder="ID ou Nome do Funcionário" value="{{ old('employeeSearch') }}">
              <label for="employeeSearch">ID ou Nome do Funcionário</label>
            </div>
            @error('employeeSearch')
              <small class="text-danger">{{ $message }}</small>
            @enderror
          </div>
          <div class="col-md-4">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
          </div>
        </div>
      </form>
    @else
      <hr>
      <!-- Dados do Funcionário -->
      <div class="mb-3">
        <h5>Dados do Funcionário</h5>
        <p><strong>Nome:</strong> {{ $employee->fullName }}</p>
        <p><strong>E-mail:</strong> {{ $employee->email }}</p>
        <p><strong>Departamento Atual:</strong> {{ $oldDepartment->title ?? '-' }}</p>
      </div>
      <!-- Formulário de Mobilidade -->
      <form action="{{ route('mobility.store') }}" method="POST">
        @csrf
        <input type="hidden" name="employeeId" value="{{ $employee->id }}">
        <input type="hidden" name="oldDepartmentId" value="{{ $oldDepartment->id ?? '' }}">
        <div class="mb-3">
          <label for="newDepartmentId" class="form-label">Novo Departamento</label>
          <select name="newDepartmentId" id="newDepartmentId" class="form-select" required>
            <option value="">-- Selecione --</option>
            @foreach($departments as $dept)
              <option value="{{ $dept->id }}" @if(old('newDepartmentId') == $dept->id) selected @endif>
                {{ $dept->title }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label for="causeOfMobility" class="form-label">Causa da Mobilidade</label>
          <textarea name="causeOfMobility" id="causeOfMobility" rows="3" class="form-control">{{ old('causeOfMobility') }}</textarea>
        </div>
        <div class="text-center">
          <button type="submit" class="btn btn-success" style="width: auto;">
            <i class="bi bi-check-circle"></i> Salvar Mobilidade
          </button>
        </div>
      </form>
    @endif
  </div>
</div>

@endsection