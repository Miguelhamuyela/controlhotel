@extends('layouts.admin.pdf')
@section('pdfTitle',"Manutenção #{$maintenance->id}")

@section('titleSection')
  <h4>Detalhes da Manutenção</h4>
@endsection

@section('contentTable')
  <table>
    <tbody>
      <tr><th>ID</th><td>{{ $maintenance->id }}</td></tr>
      <tr><th>Viatura</th><td>{{ $maintenance->vehicle->plate }}</td></tr>
      <tr><th>Tipo</th><td>{{ $maintenance->type }}</td></tr>
      <tr><th>Data</th><td>{{ \Carbon\Carbon::parse($maintenance->maintenanceDate)->format('d/m/Y') }}</td></tr>
      <tr><th>Custo</th><td>{{ number_format($maintenance->cost,2,',','.') }}</td></tr>
      <tr><th>Descrição</th><td>{{ $maintenance->description }}</td></tr>
    </tbody>
  </table>
@endsection
