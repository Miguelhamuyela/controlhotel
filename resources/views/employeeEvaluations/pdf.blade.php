{{-- resources/views/employeeEvaluations/pdf.blade.php --}}
@extends('layouts.admin.pdf')
@section('pdfTitle','Avaliação de Funcionário')
@section('titleSection')
  <h4>Avaliação do(a) Funcionario: {{ $evaluation->employee->fullName }}</h4>
@endsection
@section('contentTable')
<table>
  <tr><th>Data</th><td>{{ $evaluation->evaluationDate->format('d/m/Y') }}</td></tr>
  <tr><th>Avaliador</th><td>{{ $evaluation->evaluator }}</td></tr>
  <tr><th>Nota Geral</th><td>{{ number_format($evaluation->overallScore,2,',','.') }}</td></tr>
  <tr><th>Comentários</th><td>{{ $evaluation->comments ?: '— Sem comentários —' }}</td></tr>
</table>
@endsection
