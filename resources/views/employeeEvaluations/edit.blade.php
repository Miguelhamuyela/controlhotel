@extends('layouts.admin.layout')
@section('title','Editar Avaliação de Funcionário')
@section('content')
<div class="card my-4 shadow">
  <div class="card-header bg-primary text-white">
    <h4>Editar Relatório de Avaliação</h4>
  </div>
  <div class="card-body">

    <form id="evaluation-form" method="POST" action="{{ route('employeeEvaluations.update', $evaluation) }}">
      @csrf @method('PUT')
      <input type="hidden" name="employeeId" id="employee-id" value="{{ $evaluation->employeeId }}">

      {{-- 1) Busca desabilitada (já sabemos o funcionário) --}}
      <div class="row justify-content-center mb-4">
        <div class="col-md-4">
          <input type="text" class="form-control" value="{{ $evaluation->employee->fullName }}" readonly>
        </div>
      </div>

      {{-- 2) Campos (já visíveis) --}}
      <div id="rest-fields">

        <div class="row g-3">
          <div class="col-md-5">
            <label class="form-label">Data da Avaliação</label>
            <input type="date" name="evaluationDate" class="form-control"
                   value="{{ $evaluation->evaluationDate->format('Y-m-d') }}" required>
          </div>
          <div class="col-md-5 offset-md-2">
            <label class="form-label">Avaliador</label>
            <input type="text" name="evaluator" class="form-control"
                   value="{{ $evaluation->evaluator }}" required>
          </div>
        </div>

        <div class="row g-3 mt-3">
          <div class="col-md-5">
            <label class="form-label">Nota Geral</label>
            <input type="number" step="0.01" name="overallScore" class="form-control"
                   value="{{ $evaluation->overallScore }}" required>
          </div>
          <div class="col-md-5 offset-md-2"></div>
        </div>

        <div class="row mt-4">
          <div class="col-md-6 offset-md-3">
            <label class="form-label">Comentários</label>
            <textarea name="comments" class="form-control" rows="4">{{ $evaluation->comments }}</textarea>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col text-center">
            <button type="submit" class="btn btn-success w-50">Atualizar Avaliação</button>
          </div>
        </div>

      </div>
    </form>

  </div>
</div>
@endsection
