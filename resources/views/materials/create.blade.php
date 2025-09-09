@extends('layouts.admin.layout')
@section('title','Novo Material — '.ucfirst($category))

@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white">
    <i class="bi bi-plus-circle me-2"></i> Cadastrar Material ({{ ucfirst($category) }})
  </div>
  <div class="card-body">
    <form action="{{ route('materials.store') }}" method="POST">
      @csrf
      <input type="hidden" name="Category" value="{{ $category }}">
      <div class="row">
        <div class="col-md-6">
          <!-- Tipo -->
          <div class="mb-3">
            <label class="form-label">Tipo de Material</label>
            <select name="materialTypeId" class="form-select" required>
              <option value="">-- selecione --</option>
              @foreach($types as $t)
                <option value="{{ $t->id }}" {{ old('materialTypeId')==$t->id?'selected':'' }}>
                  {{ $t->name }}
                </option>
              @endforeach
            </select>
          </div>
          <!-- demais campos... -->
          <div class="mb-3">
            <label class="form-label">Número de Série</label>
            <input type="text" name="SerialNumber" class="form-control" required value="{{ old('SerialNumber') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Data de Fabrico</label>
            <input type="date" name="ManufactureDate" class="form-control" required value="{{ old('ManufactureDate') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">NIF DO FORNECEDOR</label>
            <input type="text" name="SupplierIdentifier" class="form-control" required value="{{ old('SupplierIdentifier') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Qtd. Inicial em Estoque</label>
            <input type="number" name="CurrentStock" class="form-control" min="0" value="{{ old('CurrentStock',0) }}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="Name" class="form-control" required value="{{ old('Name') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Modelo</label>
            <input type="text" name="Model" class="form-control" required value="{{ old('Model') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Fornecedor</label>
            <input type="text" name="SupplierName" class="form-control" required value="{{ old('SupplierName') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Data de Entrada</label>
            <input type="date" name="EntryDate" class="form-control" required value="{{ old('EntryDate') }}">
          </div>
          <div class="mb-3">
            <label class="form-label">Observações</label>
            <textarea name="Notes" class="form-control" rows="3">{{ old('Notes') }}</textarea>
          </div>
        </div>
      </div>
      <div class="text-center">
        <button class="btn btn-success"><i class="bi bi-check-circle me-1"></i> Salvar</button>
        <a href="{{ route('materials.index',['category'=>$category]) }}" class="btn btn-secondary ms-2">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection
