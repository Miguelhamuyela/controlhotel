@extends('layouts.admin.layout')
@section('title', $type=='in'?'Registrar Entrada':'Registrar Saída')

@section('content')
<div class="card mb-4">
  <div class="card-header">
    {{ $type=='in'?'Entrada de Material de ':'Saída de Material de: ' }}
    — {{ $category ? ucfirst($category) : 'Selecione a Categoria' }}
  </div>
  <div class="card-body">
    @php
      $role = Auth::user()->role;
      // Rota de store e parâmetros
      if ($role==='admin') {
        $storeRoute  = "admin.materials.transactions.{$type}.store";
        $routeParams = $category ? ['category'=>$category] : [];
      } else {
        $storeRoute  = "materials.transactions.{$type}.store";
        $routeParams = ['category'=>$category];
      }
    @endphp

    <form method="POST"
          action="{{ route($storeRoute, $routeParams) }}"
          enctype="multipart/form-data">
      @csrf

      {{-- Se for admin, mostra select de categoria primeiro --}}
      @if($role==='admin')
        <div class="col-md-6 mb-3">
          <label class="form-label">Seleção</label>
          <select name="category" class="form-select"
                  onchange="if(this.value) window.location='?category='+this.value;">
            <option value="">-- selecione  o Departamento--</option>
            <option value="infraestrutura"
              {{ $category=='infraestrutura'?'selected':'' }}>
              Infraestrutura
            </option>
            <option value="servicos_gerais"
              {{ $category=='servicos_gerais'?'selected':'' }}>
              Serviços Gerais
            </option>
          </select>
        </div>
      @endif

      {{-- Só mostra o resto do formulário depois que $category estiver definido --}}
      @if($category)
        <div class="row gx-3">
          <div class="col-md-6 mb-3">
            <label class="form-label">Material</label>
            <select name="MaterialId" class="form-select" required>
              <option value="">-- selecione material --</option>
              @foreach($materials as $m)
                <option value="{{ $m->id }}"
                  {{ old('MaterialId')==$m->id?'selected':'' }}>
                  {{ $m->Name }} ({{ $m->type->name }})
                  — Estoque: {{ $m->CurrentStock }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Data</label>
            <input type="date" name="TransactionDate" class="form-control"
                   value="{{ old('TransactionDate', now()->toDateString()) }}" required>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Quantidade</label>
            <input type="number" name="Quantity" class="form-control" min="1"
                   value="{{ old('Quantity') }}" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Destino</label>
            <input type="text" name="OriginOrDestination" class="form-control"
                   value="{{ old('OriginOrDestination') }}" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Documento (opcional)</label>
            <input type="file" name="DocumentationPath" class="form-control">
          </div>
          <div class="col-12 mb-3">
            <label class="form-label">Observações</label>
            <textarea name="Notes" class="form-control">{{ old('Notes') }}</textarea>
          </div>
        </div>
        <div class="text-center">
          <button class="btn btn-{{ $type=='in'?'success':'success' }}">
            {{ $type=='in'?'Confirmar Entrada':'Confirmar Saída' }}
          </button>
          <a href="{{ route(
                  $role==='admin'
                    ? 'admin.materials.transactions.index'
                    : 'materials.transactions.index',
                  $role==='admin' ? [] : ['category'=>$category]
                ) }}"
             class="btn btn-danger ms-2">
            Cancelar
          </a>
        </div>
      @endif

    </form>
  </div>
</div>
@endsection
