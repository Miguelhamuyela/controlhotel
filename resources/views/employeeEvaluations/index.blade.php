@extends('layouts.admin.layout')
@section('title','Avaliações de Funcionários')
@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between">
    <span><i class="fas fa-star me-2"></i>Avaliações de Funcionários</span>
    <div>
      <a href="{{ route('employeeEvaluations.pdfAll') }}" class="btn btn-outline-light btn-sm" target="_blank">
        <i class="bi bi-file-earmark-pdf"></i> Baixar Todos PDFs
      </a>
      <a href="{{ route('employeeEvaluations.create') }}" class="btn btn-outline-light btn-sm">
        Novo <i class="bi bi-plus-circle"></i>
      </a>
    </div>
  </div>
  <div class="card-body">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Funcionário</th>
          <th>Data</th>
          <th>Avaliador</th>
          <th>Nota</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @foreach($evaluations as $eval)
        <tr>
          <td>{{ $eval->id }}</td>
          <td>{{ $eval->employee->fullName }}</td>
          <td>{{ \Carbon\Carbon::parse($eval->evaluationDate)->format('d/m/Y') }}</td>
          <td>{{ $eval->evaluator }}</td>
          <td>{{ number_format($eval->overallScore,2,',','.') }}</td>
          <td class="d-flex gap-1">
            <a href="{{ route('employeeEvaluations.show', $eval) }}" class="btn btn-warning btn-sm">
              <i class="bi bi-eye"></i>
            </a>
            <a href="{{ route('employeeEvaluations.edit', $eval) }}" class="btn btn-info btn-sm">
              <i class="bi bi-pencil"></i>
            </a>
            <a href="{{ route('employeeEvaluations.pdf', $eval) }}" class="btn btn-outline-danger btn-sm" target="_blank">
              <i class="bi bi-file-earmark-pdf"></i>
            </a>
            <form action="{{ route('employeeEvaluations.destroy', $eval) }}" method="POST" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
