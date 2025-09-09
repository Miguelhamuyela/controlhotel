@extends('layouts.admin.pdf')

@section('pdfTitle', 'Relatório de Reformas')

@section('titleSection')
  <h4>Reformas Filtradas</h4>
  <p style="text-align: center;">
    <strong>Total de Reformas:</strong> <ins>{{ $allRetirements->count() }}</ins>
  </p>
@endsection

@section('contentTable')
  @if($allRetirements->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Funcionário</th>
          <th>Status</th>
          <th>Observações</th>
          <th>Registro</th>
        </tr>
      </thead>
      <tbody>
        @foreach($allRetirements as $retire)
          <tr>
            <td>{{ $retire->id }}</td>
            <td>{{ $retire->employee->fullName ?? '-' }}</td>
            <td>{{ $retire->status }}</td>
            <td>{{ $retire->observations ?? '-' }}</td>
            <td>{{ $retire->created_at->format('d/m/Y H:i') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Nenhum pedido no intervalo selecionado.</p>
  @endif
@endsection
