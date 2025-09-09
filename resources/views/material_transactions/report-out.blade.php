@extends('layouts.admin.pdf')

@section('pdfTitle', 'Relatório de Saídas — ' . ucfirst($category))

@section('titleSection')
  <h4>Saídas — {{ ucfirst($category) }}</h4>
  <p style="text-align:center;"><strong>Total de Saídas:</strong> {{ $txs->count() }}</p>
@endsection

@section('contentTable')
  @if($txs->count())
    <table>
      <thead>
        <tr>
          <th>Material</th>
          <th>Tipo</th>
          <th>Qtde</th>
          <th>Data</th>
          <th>Destino</th>
          <th>Responsável</th>
          <th>Doc.</th>
          <th>Obs.</th>
        </tr>
      </thead>
      <tbody>
        @foreach($txs as $t)
        <tr>
          <td>{{ $t->material->Name  }}</td>
          <td>{{ $t->material->type->name }}</td>
          <td>{{ $t->Quantity }}</td>
          <td>{{ \Carbon\Carbon::parse($t->TransactionDate)->format('d/m/Y') }}</td>
          <td>{{ $t->OriginOrDestination }}</td>
          <td>{{ $t->creator->fullName ?? 'Admin' }}</td>
          <td>@if($t->DocumentationPath) Sim @else — @endif</td>
          <td>{{ $t->Notes }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align:center;">Nenhuma saída registrada.</p>
  @endif

  <div style="margin-top:40px; display:flex; justify-content:space-between;">
    <div>___________________________<br>Chefe do Departamento</div>
    <div>___________________________<br>Fornecedor</div>
  </div>
@endsection

