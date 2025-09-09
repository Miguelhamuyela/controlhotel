@extends('layouts.admin.pdf')
@section('pdfTitle','Relatório de Viaturas')

@section('titleSection')
  <h4>Relatório de Viaturas</h4>
  <p style="text-align: center;">
    <strong>Total de Viaturas:</strong> <ins>{{ $filtered->count() }}</ins>
  </p>
@endsection

@section('contentTable')
  @if($filtered->count())
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Placa</th><th>Marca</th><th>Modelo</th>
          <th>Ano</th><th>Cor</th><th>Capacidade</th><th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($filtered as $v)
        <tr>
          <td>{{ $v->id }}</td>
          <td>{{ $v->plate }}</td>
          <td>{{ $v->brand }}</td>
          <td>{{ $v->model }}</td>
          <td>{{ $v->yearManufacture }}</td>
          <td>{{ $v->color }}</td>
          <td>{{ $v->loadCapacity }}</td>
          <td>
            {{ $v->status=='Available'?'Disponível':($v->status=='InMaintenance'?'Em manutenção':'Indisponível') }}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Nenhuma viatura encontrada.</p>
  @endif
@endsection
