@extends('layouts.admin.pdf')
@section('pdfTitle','Relatório de Manutenções')

@section('titleSection')
  <h4>Relatório de Manutenções</h4>
  <p style="text-align: center;">
    <strong>Total de Registros:</strong> <ins>{{ $filtered->count() }}</ins>
  </p>
@endsection

@section('contentTable')
  @if($filtered->count())
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Viatura</th><th>Tipo</th>
          <th>Data</th><th>Custo</th><th>Descrição</th>
        </tr>
      </thead>
      <tbody>
        @foreach($filtered as $r)
        <tr>
          <td>{{ $r->id }}</td>
          <td>{{ $r->vehicle->plate }}</td>
          <td>{{ $r->type }}</td>
          <td>{{ \Carbon\Carbon::parse($r->maintenanceDate)->format('d/m/Y') }}</td>
          <td>{{ number_format($r->cost,2,',','.') }}</td>
          <td>{{ $r->description }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Nenhum registro de manutenção encontrado.</p>
  @endif
@endsection
