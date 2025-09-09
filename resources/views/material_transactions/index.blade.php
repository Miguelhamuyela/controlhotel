@extends('layouts.admin.layout')
@section('title','Histórico — '.(
    request('category')
      ? ucfirst(request('category'))
      : (Auth::user()->role==='admin'?'Todas as Categorias':'Minha Categoria')
  )
)

@section('content')
<div class="card mb-4">
  <div class="card-header bg-secondary text-white d-flex justify-content-between">
    <span><i class="bi bi-clock-history me-2"></i>Histórico — {{
      request('category')
        ? ucfirst(request('category'))
        : (Auth::user()->role==='admin'?'Todas':'Minha')
    }}</span>
    <div>
      @php
        $base = Auth::user()->role==='admin'
          ? 'admin.materials.transactions'
          : 'materials.transactions';
        $rp   = Auth::user()->role==='admin'
          ? ['category'=>request('category')]
          : ['category'=>request('category')];
      @endphp
      <a href="{{ route("{$base}.report-in",$rp) }}" class="btn btn-sm btn-outline-light" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> PDF Entradas
      </a>
      <a href="{{ route("{$base}.report-out",$rp) }}" class="btn btn-sm btn-outline-light" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> PDF Saídas
      </a>
      <a href="{{ route("{$base}.report-all",$rp) }}" class="btn btn-sm btn-outline-light" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> PDF Total
      </a>
    </div>
  </div>
  <div class="card-body">
    <form class="row g-3 mb-3" method="GET">
      @if(Auth::user()->role==='admin')
        <div class="col-md-3">
          <select name="category" class="form-select">
            <option value="">Todas categorias</option>
            <option value="infraestrutura" {{ request('category')=='infraestrutura'?'selected':'' }}>Infraestrutura</option>
            <option value="servicos_gerais" {{ request('category')=='servicos_gerais'?'selected':'' }}>Serviços Gerais</option>
          </select>
        </div>
      @endif
      <div class="{{ Auth::user()->role==='admin'?'col-md-2':'col-md-3' }}">
        <input type="date" name="startDate" class="form-control" value="{{ request('startDate') }}">
      </div>
      <div class="{{ Auth::user()->role==='admin'?'col-md-2':'col-md-3' }}">
        <input type="date" name="endDate" class="form-control" value="{{ request('endDate') }}">
      </div>
      <div class="{{ Auth::user()->role==='admin'?'col-md-2':'col-md-3' }}">
        <select name="type" class="form-select">
          <option value="">Todos</option>
          <option value="in"  {{ request('type')=='in'?'selected':'' }}>Entrada</option>
          <option value="out" {{ request('type')=='out'?'selected':'' }}>Saída</option>
        </select>
      </div>
      <div class="col-md-3 d-grid">
        <button class="btn btn-primary"><i class="bi bi-filter"></i> Filtrar</button>
      </div>
    </form>

    <table class="table table-striped">
      <thead>
        <tr>
          @if(Auth::user()->role==='admin')<th>Categoria</th>@endif
          <th>Tipo</th><th>Material</th><th>Qtde</th><th>Data</th><th>Destino</th><th>Responsável</th>
        </tr>
      </thead>
      <tbody>
        @forelse($txs as $t)
          <tr>
            @if(Auth::user()->role==='admin')<td>{{ ucfirst($t->material->Category) }}</td>@endif
            <td>{{ $t->TransactionType=='in'?'Entrada':'Saída' }}</td>
            <td>{{ $t->material->Name }}</td>
            <td>{{ $t->Quantity }}</td>
            <td>{{ \Carbon\Carbon::parse($t->TransactionDate)->format('d/m/Y') }}</td>
            <td>{{ $t->OriginOrDestination }}</td>
            <td>{{ $t->creator->fullName ?? 'Admin' }}</td>

          </tr>
        @empty
          <tr><td colspan="{{ Auth::user()->role==='admin'?7:6 }}" class="text-center">Nenhuma transação.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
