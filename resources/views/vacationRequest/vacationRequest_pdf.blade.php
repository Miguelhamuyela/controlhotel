@extends('layouts.admin.pdf')
@section('pdfTitle', 'Relatório de Pedidos de Férias')
@section('titleSection')
  <h4>Pedidos de Férias Filtrados</h4>
  <p style="text-align: center;">
    <strong>Total de Pedidos (Filtrados):</strong> <ins>{{ $allRequests->count() }}</ins>
  </p>
@endsection
@section('contentTable')
  @if($allRequests->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Funcionário</th>
          <th>Tipo de Férias</th>
          <th>Status</th>
          <th>Data Início</th>
          <th>Data Fim</th>
          <th>Documento</th>
          <th>Razão</th>
        </tr>
      </thead>
      <tbody>
        @foreach($allRequests as $vr)
        <tr>
          <td>{{ $vr->id }}</td>
          <td>{{ $vr->employee->fullName ?? '-' }}</td>
          <td>{{ $vr->vacationType }}</td>
          <td>{{ $vr->approvalStatus }}</td>
          <td>{{ \Carbon\Carbon::parse($vr->vacationStart)->format('d/m/Y') }}</td>
          <td>{{ \Carbon\Carbon::parse($vr->vacationEnd)->format('d/m/Y') }}</td>
          <td>{{ $vr->originalFileName ?? '-' }}</td>
          <td>{{ $vr->reason ?? '-' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Nenhum pedido no intervalo selecionado.</p>
  @endif
@endsection
