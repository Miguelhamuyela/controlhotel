@extends('layouts.admin.pdf')

@section('pdfTitle', 'Detalhes do Trabalho Extra')

@section('titleSection')
  <h4>{{ $job->title }}</h4>
  <p><strong>Valor Total:</strong> {{ number_format($job->totalValue,2,',','.') }}</p>
  <p><strong>Status:</strong> {{ $job->statusInPortuguese }}</p>
<ins>_______________________________________________________________________________</ins>
@endsection

@section('contentTable')
  <h5>Distribuição</h5>
  <table>
    <thead>
      <tr><th>Funcionário</th><th>Ajuste (Kz)</th><th>Recebe (Kz)</th></tr>
    </thead>
    <tbody>
      @foreach($job->employees as $employee)
      <tr>
        <td>{{ $employee->fullName }}</td>
        <td>{{ number_format($employee->pivot->bonusAdjustment,2,',','.') }}</td>
        <td>{{ number_format($employee->pivot->assignedValue,2,',','.') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
@endsection

