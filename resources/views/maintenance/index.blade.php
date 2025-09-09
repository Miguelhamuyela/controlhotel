@extends('layouts.admin.layout')
@section('title','Maintenance')
@section('content')

<div class="card mb-4 shadow">
  <div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-tools me-2"></i>Registros de Manutenção</span>
    <div>
      <a href="{{ route('maintenance.pdfAll', request()->only('startDate','endDate')) }}"
         class="btn btn-outline-light btn-sm" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i>
        Baixar PDF ({{ request()->filled('startDate')||request()->filled('endDate') ? 'Filtrado' : 'Todos' }})
      </a>
      <a href="{{ route('maintenance.create') }}"
         class="btn btn-outline-light btn-sm" title="Novo Registro de Manutenção">
        Novo <i class="bi bi-plus-circle"></i>
      </a>
    </div>
  </div>
</div>

  

  
  <form method="GET" action="{{ route('maintenance.index') }}" class="row g-3 mb-4">
  <div class="col-md-3">
    <label class="form-label">Data Início</label>
    <input type="date" name="startDate" class="form-control" value="{{ request('startDate') }}">
  </div>
  <div class="col-md-3">
    <label class="form-label">Data Fim</label>
    <input type="date" name="endDate" class="form-control" value="{{ request('endDate') }}">
  </div>
  <div class="col-md-3 d-flex align-items-end">
    <button class="btn btn-primary w-100"><i class="bi bi-filter"></i> Filtrar</button>
  </div>
</form>


  <div class="card-body">
    @if(session('msg'))
      <div class="alert alert-success">{{ session('msg') }}</div>
    @endif
    <div class="table-responsive">
      <table id="datatablesSimple" class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Veículo</th>
            <th>Tipo</th>
            <th>Data</th>
            <th>Custo</th>
            <th>Descrição</th>
            <th>Acções</th>
          </tr>
        </thead>
        <tbody>
          @foreach($records as $r)
            <tr>
              <td>{{ $r->id }}</td>
              <td>{{ $r->vehicle->plate }}</td>
              <td>{{ $r->type }}</td>
              <td>{{ $r->maintenanceDate }}</td>
              <td>{{ $r->cost }}</td>
              <td>{{ Str::limit($r->description,30) }}</td>
              <td>
                <a href="{{ route('maintenance.show',$r->id) }}" class="btn btn-warning btn-sm">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('maintenance.edit',$r->id) }}" class="btn btn-info btn-sm">
                  <i class="bi bi-pencil"></i>
                </a>
                <a href="#" data-url="{{ url('maintenance/'.$r->id.'/delete') }}"
                   class="btn btn-danger btn-sm delete-btn">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
