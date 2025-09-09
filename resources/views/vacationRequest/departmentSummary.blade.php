@extends('layouts.admin.layout')
@section('title', 'Mapa de Férias por Departamento')
@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white">
    <h4>Mapa de Férias por Departamento</h4>
  </div>
  <div class="card-body">
    @if(count($summaryData))
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Departamento</th>
            <th>Total de Pedidos</th>
            <th>Aprovados</th>
            <th>Pendentes</th>
            <th>Recusados</th>
          </tr>
        </thead>
        <tbody>
          @foreach($summaryData as $data)
            <tr>
              <td>{{ $data['department'] }}</td>
              <td>{{ $data['total'] }}</td>
              <td>{{ $data['approved'] }}</td>
              <td>{{ $data['pending'] }}</td>
              <td>{{ $data['rejected'] }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    @else
      <p>Nenhum pedido de férias registrado.</p>
    @endif
  </div>
</div>
@endsection
