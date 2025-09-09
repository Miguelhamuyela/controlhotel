@extends('layouts.admin.pdf')
@section('pdfTitle','Vehicles Report')

@section('titleSection')
  <h4>Relatório de veículos</h4>
  <p><strong>Total:</strong> {{ $vehicles->count() }}</p>
@endsection

@section('contentTable')
  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Matricula</th>
        <th>Modelo</th>
        <th>Status</th>
        <th>Motorista</th>
      </tr>
    </thead>
    <tbody>
      @foreach($vehicles as $v)
        <tr>
          <td>{{ $v->id }}</td>
          <td>{{ $v->plate }}</td>
          <td>{{ $v->model }}</td>
          <td>{{ $v->status }}</td>
          <td>
            @foreach($v->drivers as $d)
              {{ $d->fullName }}@if(! $loop->last), @endif
            @endforeach
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
