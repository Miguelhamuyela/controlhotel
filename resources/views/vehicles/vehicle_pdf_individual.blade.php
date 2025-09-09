@extends('layouts.admin.pdf')
@section('pdfTitle',"Viatura #{$vehicle->id}")

@section('titleSection')
  <h4>Detalhes da Viatura</h4>
@endsection

@section('contentTable')
  <table>
    <tbody>
      <tr><th>ID</th><td>{{ $vehicle->id }}</td></tr>
      <tr><th>Placa</th><td>{{ $vehicle->plate }}</td></tr>
      <tr><th>Marca</th><td>{{ $vehicle->brand }}</td></tr>
      <tr><th>Modelo</th><td>{{ $vehicle->model }}</td></tr>
      <tr><th>Ano</th><td>{{ $vehicle->yearManufacture }}</td></tr>
      <tr><th>Cor</th><td>{{ $vehicle->color }}</td></tr>
      <tr><th>Capacidade</th><td>{{ $vehicle->loadCapacity }}</td></tr>
      <tr><th>Status</th>
        <td>{{ $vehicle->status=='Available'?'Disponível':($vehicle->status=='InMaintenance'?'Em manutenção':'Indisponível') }}</td>
      </tr>
      <tr><th>Observações</th><td>{{ $vehicle->notes ?? '-' }}</td></tr>
    </tbody>
  </table>
@endsection
