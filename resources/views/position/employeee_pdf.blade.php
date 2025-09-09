@extends('layouts.admin.pdf')
@section('pdfTitle', 'Relatório de Pedidos de Férias')
@section('titleSection')


  <h4>Relatório dos Funcionários com o cargo de: {{ $position->name }}</h4>
  <p style="text-align: center;">
    <strong>Total de Funcionários:</strong> <ins>{{ $position->employees->count() }}</ins>
  </p>
@endsection

@section('contentTable')
  @if($position->employees && $position->employees->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome Completo</th>
          <th>Email</th>
          <th>Especialidade</th>
        </tr>
      </thead>
      <tbody>
        @foreach($position->employees as $emp)
          <tr>
            <td>{{ $emp->id }}</td>
            <td>{{ $emp->fullName }}</td>
            <td>{{ $emp->email }}</td>
            <td>{{ $emp->specialty->name ?? '-' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align:center;">Não há funcionários com este Cargo.</p>
  @endif

@endsection
