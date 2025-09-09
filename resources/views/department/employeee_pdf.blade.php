@extends('layouts.admin.pdf')

@section('pdfTitle', 'Relatório do Departamento')

@section('titleSection')
<h4>Relatório dos Funcionários do Departamento de: {{ $department->title }}</h4>
<p style="text-align: center;">
  <strong>Total de Funcionários:</strong> <ins>{{ $department->employeee->count() }}</ins>
</p>
@endsection

@section('contentTable')
@if($department->employeee && $department->employeee->count())
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
      @foreach($department->employeee as $emp)
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
  <p style="text-align:center;">Não há funcionários cadastrados neste departamento.</p>
@endif
@endsection
