@extends('layouts.admin.layout')
@section('title','Detalhes da Movimentação')

@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between">
    <span><i class="bi bi-card-list me-2"></i> Detalhes da Movimentação</span>
    <a href="{{ route('materials.transactions.index',['category'=>$category]) }}"
       class="btn btn-outline-light btn-sm">
      <i class="bi bi-arrow-left"></i> Voltar
    </a>
  </div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-sm-3">Tipo</dt>
      <dd class="col-sm-9">{{ $tx->TransactionType=='in' ? 'Entrada' : 'Saída' }}</dd>

      <dt class="col-sm-3">Material</dt>
      <dd class="col-sm-9">{{ $tx->material->Name }}</dd>

      <dt class="col-sm-3">Quantidade</dt>
      <dd class="col-sm-9">{{ $tx->Quantity }}</dd>

      <dt class="col-sm-3">Data</dt>
      <dd class="col-sm-9">{{ \Carbon\Carbon::parse($tx->TransactionDate)->format('d/m/Y') }}</dd>

      <dt class="col-sm-3">Origem / Destino</dt>
      <dd class="col-sm-9">{{ $tx->OriginOrDestination }}</dd>

      <dt class="col-sm-3">Responsável</dt>
      <dd class="col-sm-9">{{ $tx->creator->fullName }}</dd>

      <dt class="col-sm-3">Documentação</dt>
      <dd class="col-sm-9">{{ $tx->DocumentationPath ? 'Sim' : 'Não' }}</dd>

      <dt class="col-sm-3">Observações</dt>
      <dd class="col-sm-9">{{ $tx->Notes }}</dd>
    </dl>
  </div>
</div>
@endsection
