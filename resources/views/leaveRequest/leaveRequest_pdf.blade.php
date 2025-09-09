@extends('layouts.admin.pdf')
@section('pdfTitle', 'Relatório de Pedidos de Licença')
@section('titleSection')
  <h4>Relatório de Pedidos de Licença</h4>
  <p style="text-align: center;">
    <strong>Total de Pedidos:</strong> <ins>{{ $allLeaveRequests->count() }}</ins>
  </p>
@endsection
@section('contentTable')
  @if($allLeaveRequests->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Funcionário</th>
          <th>Tipo de Licença</th>
          <th>Departamento</th>
          <th>Data de Início</th>
          <th>Data de Término</th>
          <th>Razão</th>
          <th>Status</th>
          <th>Comentário</th>
        </tr>
      </thead>
      <tbody>
        @foreach($allLeaveRequests as $lr)
          <tr>
            <td>{{ $lr->id }}</td>
            <td>{{ $lr->employee->fullName ?? '-' }}</td>
            <td>{{ $lr->leaveType->name ?? '-' }}</td>
            <td>{{ $lr->department->title ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($lr->leaveStart)->format('d/m/Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($lr->leaveEnd)->format('d/m/Y') }}</td>
            <td>{{ $lr->reason ?? '-' }}</td>
            <td>{{ $lr->approvalStatus }}</td>
            <td>{{ $lr->approvalComment ?? '-' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Nenhum pedido de licença registrado.</p>
  @endif
@endsection
