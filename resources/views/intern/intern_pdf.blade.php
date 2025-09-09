@extends('layouts.admin.pdf')

@section('pdfTitle', 'Relatório de Todos os Estagiários')

@section('titleSection')
  <h4>Relatório de Todos os Estagiários</h4>
  <p style="text-align: center;">
    <strong>Total de Estagiários:</strong> <ins>{{ $allInterns->count() }}</ins>
  </p>
@endsection

@section('contentTable')
  @if($allInterns->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome Completo</th>
          <th>Departamento</th>
          <th>Cargo</th>
          <th>Especialidade</th>
          <th>Email</th>
          <th>Início do Estágio</th>
          <th>Fim do Estágio</th>
          <th>Instituição</th>
        </tr>
      </thead>
      <tbody>
        @foreach($allInterns as $intern)
          <tr>
            <td>{{ $intern->id }}</td>
            <td>{{ $intern->fullName }}</td>
            <td>{{ $intern->department->title ?? '-' }}</td>
            <td>{{ $intern->position->name ?? '-' }}</td>
            <td>{{ $intern->specialty->name ?? '-' }}</td>
            <td>{{ $intern->email }}</td>
            <td>{{ $intern->internshipStart }}</td>
            <td>{{ $intern->internshipEnd ? $intern->internshipEnd : 'A definir' }}</td>
            <td>{{ $intern->institution }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Não há estagiários cadastrados.</p>
  @endif
@endsection
