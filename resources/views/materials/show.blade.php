@extends('layouts.admin.layout')
@section('title','Detalhes do Material')

@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between">
    <span><i class="bi bi-box-seam me-2"></i> Detalhes do Material</span>
    <a href="{{ route('materials.index',['category'=>$material->Category]) }}" class="btn btn-outline-light btn-sm">
      <i class="bi bi-arrow-left"></i> Voltar
    </a>
  </div>
  <div class="card-body">
    <dl class="row">
      <dt class="col-sm-4">Tipo de Material</dt>
      <dd class="col-sm-8">{{ $material->type->name }}</dd>
      <dt class="col-sm-4">Nome</dt>
      <dd class="col-sm-8">{{ $material->Name }}</dd>
      <dt class="col-sm-4">Número de Série</dt>
      <dd class="col-sm-8">{{ $material->SerialNumber }}</dd>
      <dt class="col-sm-4">Modelo</dt>
      <dd class="col-sm-8">{{ $material->Model }}</dd>
      <dt class="col-sm-4">Data de Fabrico</dt>
      <dd class="col-sm-8">{{ \Carbon\Carbon::parse($material->ManufactureDate)->format('d/m/Y') }}</dd>
      <dt class="col-sm-4">Fornecedor</dt>
      <dd class="col-sm-8">{{ $material->SupplierName }}</dd>
      <dt class="col-sm-4">NIF do Fornecedor</dt>
      <dd class="col-sm-8">{{ $material->SupplierIdentifier }}</dd>
      <dt class="col-sm-4">Data de Entrada</dt>
      <dd class="col-sm-8">{{ \Carbon\Carbon::parse($material->EntryDate)->format('d/m/Y') }}</dd>
      <dt class="col-sm-4">Qtd. Inicial em Estoque</dt>
      <dd class="col-sm-8">{{ $material->CurrentStock }}</dd>
      <dt class="col-sm-4">Observações</dt>
      <dd class="col-sm-8">{{ $material->Notes ?? '—' }}</dd>
    </dl>
  </div>
</div>
@endsection
