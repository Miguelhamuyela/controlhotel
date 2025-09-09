@extends('layouts.admin.pdf')

@section('pdfTitle', 'Relatório de Trabalhos Extras')

@section('titleSection')
  <h4>Trabalhos Extras</h4>
  <p style="text-align:center;">
    <strong>Total de Registros:</strong> <ins>{{ $jobs->count() }}</ins>
  </p>
@endsection

@section('contentTable')
  @if($jobs->count())
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Valor Total</th>
        <th>Participantes</th>
        <th>Status</th>
        <th>Data</th>
      </tr>
    </thead>
    <tbody>
      @foreach($jobs as $job)
      <tr>
        <td>{{ $job->id }}</td>
        <td>{{ $job->title }}</td>
        <td>{{ number_format($job->totalValue,2,',','.') }}</td>
        <td>{{ $job->employees->count() }}</td>
        <td>{{ $job->statusInPortuguese }}</td>
        <td>{{ $job->created_at->format('d/m/Y') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
    <p style="text-align:center;">Nenhum registro encontrado.</p>
  @endif
@endsection

