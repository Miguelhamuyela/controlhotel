@extends('layouts.admin.pdf')

@section('pdfTitle', 'Relatório de Mobilidades')

@section('titleSection')
  <h4>Relatório de Mobilidades</h4>
  <p style="text-align: center;">
    <strong>Total de Mobilidades:</strong> <ins>{{ $allMobility->count() }}</ins>
  </p>
@endsection

@section('contentTable')
  @if($allMobility->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Funcionário</th>
          <th>Departamento Antigo</th>
          <th>Novo Departamento</th>
          <th>Causa</th>
          <th>Data de Registro</th>
        </tr>
      </thead>
      <tbody>
        @foreach($allMobility as $mob)
          <tr>
            <td>{{ $mob->id }}</td>
            <td>{{ $mob->employee->fullName ?? '-' }}</td>
            <td>{{ $mob->oldDepartment->title ?? '-' }}</td>
            <td>{{ $mob->newDepartment->title ?? '-' }}</td>
            <td>{{ $mob->causeOfMobility ?? '-' }}</td>
            <td>{{ $mob->created_at->format('d/m/Y H:i') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Nenhuma mobilidade registrada.</p>
  @endif
@endsection
