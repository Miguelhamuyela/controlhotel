@extends('layouts.admin.pdf')
@section('pdfTitle','Relatório de Motoristas')

@section('titleSection')
  <h4>Relatório de Motoristas</h4>
  <p style="text-align: center;">
    <strong>Total de Motoristas:</strong> <ins>{{ $filtered->count() }}</ins>
  </p>
@endsection

@section('contentTable')
  @if($filtered->count())
    <table>
      <thead>
        <tr>
          <th>ID</th><th>Nome</th><th>B.I.</th><th>Nº Carta</th>
          <th>Categoria</th><th>Validade</th><th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($filtered as $d)
        <tr>
          <td>{{ $d->id }}</td>
          <td>{{ $d->employee->fullName ?? $d->fullName }}</td>
          <td>{{ $d->bi }}</td>
          <td>{{ $d->licenseNumber }}</td>
          <td>{{ $d->licenseCategory }}</td>
          <td>{{ \Carbon\Carbon::parse($d->licenseExpiry)->format('d/m/Y') }}</td>
          <td>{{ $d->status=='Active'?'Ativo':'Inativo' }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Nenhum motorista encontrado.</p>
  @endif
@endsection
