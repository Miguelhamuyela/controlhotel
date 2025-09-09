{{-- resources/views/employeeEvaluations/pdfAll.blade.php --}}
@extends('layouts.admin.pdf')
@section('pdfTitle','Todas Avaliações de Funcionários')
@section('titleSection')
  <h4>Todas as Avaliações de Funcionários</h4>
@endsection
@section('contentTable')
<table>
  <thead>
    <tr><th>ID</th><th>Funcionário</th><th>Data</th><th>Avaliador</th><th>Nota</th></tr>
  </thead>
  <tbody>
    @forelse($evaluations as $e)
      <tr>
        <td>{{ $e->id }}</td>
        <td>{{ $e->employee->fullName }}</td>
        <td>{{ $e->evaluationDate->format('d/m/Y') }}</td>
        <td>{{ $e->evaluator }}</td>
        <td>{{ number_format($e->overallScore,2,',','.') }}</td>
  
      </tr>
    @empty
      <tr><td colspan="6" class="text-center">Nenhuma avaliação registrada.</td></tr>
    @endforelse
  </tbody>
</table>
@endsection
