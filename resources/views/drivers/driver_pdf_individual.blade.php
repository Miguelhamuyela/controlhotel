@extends('layouts.admin.pdf')
@section('pdfTitle',"Motorista #{$driver->id}")

@section('titleSection')
  <h4>Detalhes do Motorista</h4>
@endsection

@section('contentTable')
  <table>
    <tbody>
      <tr><th>ID</th><td>{{ $driver->id }}</td></tr>
      <tr><th>Funcionário</th><td>{{ $driver->employee->fullName ?? '-' }}</td></tr>
      <tr><th>Nome Completo</th><td>{{ $driver->fullName ?? '-' }}</td></tr>
      <tr><th>B.I.</th><td>{{ $driver->bi ?? '-' }}</td></tr>
      <tr><th>Nº da Carta</th><td>{{ $driver->licenseNumber }}</td></tr>
      <tr><th>Categoria</th><td>{{ $driver->licenseCategory }}</td></tr>
      <tr><th>Validade</th><td>{{ \Carbon\Carbon::parse($driver->licenseExpiry)->format('d/m/Y') }}</td></tr>
      <tr><th>Status</th><td>{{ $driver->status=='Active'?'Ativo':'Inativo' }}</td></tr>
    </tbody>
  </table>
@endsection
