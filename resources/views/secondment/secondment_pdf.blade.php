@extends('layouts.admin.pdf')

@section('pdfTitle', 'Relatório de Destacamentos')

@section('titleSection')
  <h4>Relatório de Destacamentos</h4>
  <p style="text-align: center;">
    <strong>Total de Destacamentos:</strong> <ins>{{ $allSecondments->count() }}</ins>
  </p>
@endsection

@section('contentTable')
  @if($allSecondments->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Funcionário</th>
          <th>Causa da Transferência</th>
          <th>Instituição</th>
          <th>Documento de Suporte</th>
        </tr>
      </thead>
      <tbody>
        @foreach($allSecondments as $s)
          <tr>
            <td>{{ $s->id }}</td>
            <td>{{ $s->employee->fullName ?? '-' }}</td>
            <td>{{ $s->causeOfTransfer ?? '-' }}</td>
            <td>{{ $s->institution }}</td>
            <td>
              @if($s->supportDocument)
                {{ $s->originalFileName ?? $s->supportDocument }}
              @else
                -
              @endif
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Nenhum destacamento registrado.</p>
  @endif
@endsection
