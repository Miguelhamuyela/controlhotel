@extends('layouts.admin.pdf')

@section('pdfTitle', 'Relatório por Especialidade')

@section('titleSection')
  <h4>Relatório dos Funcionários com a Especialidade: {{ $specialty->name }}</h4>
  <p style="text-align: center;">
    <strong>Total de Funcionários:</strong> <ins>{{ $specialty->employees->count() }}</ins>
  </p>
@endsection

@section('contentTable')
  @if($specialty->employees && $specialty->employees->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome Completo</th>
          <th>Email</th>
          <th>Cargo</th>
        </tr>
      </thead>
      <tbody>
        @foreach($specialty->employees as $emp)
          <tr>
            <td>{{ $emp->id }}</td>
            <td>{{ $emp->fullName }}</td>
            <td>{{ $emp->email }}</td>
            <td>{{ $emp->position->name ?? '-' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Não há funcionários com esta especialidade.</p>
  @endif
@endsection
