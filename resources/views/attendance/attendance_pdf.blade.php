@extends('layouts.admin.pdf')
@section('pdfTitle', 'Relatório de Registros de Presença')
@section('titleSection')
  <h4>Relatório de Registros de Presença</h4>
  <p style="text-align: center;">
    <strong>Total de Registros:</strong> <ins>{{ $records->count() }}</ins>
  </p>
@endsection
@section('contentTable')
  @if($records->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Funcionário</th>
          <th>Data</th>
          <th>Status</th>
          <th>Observações</th>
        </tr>
      </thead>
      <tbody>
        @foreach($records as $record)
          <tr>
            <td>{{ $record->id }}</td>
            <td>{{ $record->employee->fullName ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($record->recordDate)->format('d/m/Y') }}</td>
            <td>{{ $record->status }}</td>
            <td>{{ $record->observations ?? '-' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Nenhum registro encontrado.</p>
  @endif
@endsection
