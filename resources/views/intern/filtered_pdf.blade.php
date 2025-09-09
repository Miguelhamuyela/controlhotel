@extends('layouts.admin.pdf')

@section('pdfTitle', 'Relatório de Estagiários Filtrados')

@section('titleSection')
  <h4>Relatório de Estagiários Filtrados</h4>
  <p style="text-align: center;">
    <strong>Período:</strong> {{ $startDate }} a {{ $endDate }} <br>
    <strong>Total de Estagiários:</strong> <ins>{{ $filtered->count() }}</ins>
  </p>
@endsection

@section('contentTable')
  @if($filtered->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome Completo</th>
          <th>Departamento</th>
          <th>Cargo</th>
          <th>Especialidade</th>
          <th>Instituição</th> 
          <th>Data de Registro</th>
        </tr>
      </thead>
      <tbody>
        @foreach($filtered as $intern)
          <tr>
            <td>{{ $intern->id }}</td>
            <td>{{ $intern->fullName }}</td>
            <td>{{ $intern->department->title ?? '-' }}</td>
            <td>{{ $intern->position->name ?? '-' }}</td>
            <td>{{ $intern->specialty->name ?? '-' }}</td>
            <td>{{ $intern->institution ?? '-' }}</td>
            <td>{{ $intern->created_at->format('d/m/Y H:i') }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Nenhum estagiário encontrado no período selecionado.</p>
  @endif
@endsection
