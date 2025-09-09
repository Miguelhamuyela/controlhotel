@extends('layouts.admin.layout')
@section('title','Detalhes da Avaliação de Funcionário')
@section('content')
<div class="card shadow">
  <div class="card-body">
    <p><strong>ID:</strong> {{ $evaluation->id }}</p>
    <p><strong>Funcionário:</strong> {{ $evaluation->employee->fullName }}</p>
    <p><strong>Data:</strong> {{ $evaluation->evaluationDate->format('d/m/Y') }}</p>
    <p><strong>Avaliador:</strong> {{ $evaluation->evaluator }}</p>
    <p><strong>Nota Geral:</strong> {{ number_format($evaluation->overallScore,2,',','.') }}</p>
    <p><strong>Comentários:</strong><br>{{ $evaluation->comments }}</p>
    <a href="{{ route('employeeEvaluations.pdf', $evaluation) }}"
       class="btn btn-outline-danger" target="_blank">
      Baixar PDF
    </a>
  </div>
</div>
@endsection
