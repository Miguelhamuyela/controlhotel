@extends('layouts.admin.layout')
@section('title','Editar Manutenção')
@section('content')

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-tools me-2"></i>Editar Manutenção Nº{{ $maintenance->id }}</span>
    <a href="{{ route('maintenance.index') }}" class="btn btn-outline-light btn-sm" title="Ver Todos">
      <i class="bi bi-card-list"></i>
    </a>
  </div>
  <div class="card-body">
    <form action="{{ route('maintenance.update', $maintenance->id) }}" method="POST" enctype="multipart/form-data">
      @csrf @method('PUT')

      {{-- Veículo e Tipo --}}
      <div class="row g-3">
        <div class="col-md-6">
          <div class="form-floating">
            <select name="vehicleId" id="vehicleId" class="form-select" required>
              @foreach($vehicles as $v)
                <option value="{{ $v->id }}"
                  @if(old('vehicleId',$maintenance->vehicleId)==$v->id) selected @endif>
                  {{ $v->plate }} – {{ $v->brand }} – {{ $v->model }}
                </option>
              @endforeach
            </select>
            <label for="vehicleId">Viatura</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <select name="type" id="type" class="form-select" required>
              @foreach(['Preventive','Corrective'] as $t)
                <option value="{{ $t }}"
                  @if(old('type',$maintenance->type)==$t) selected @endif>
                  {{ $t=='Preventive'?'Preventiva':'Corretiva' }}
                </option>
              @endforeach
            </select>
            <label for="type">Tipo</label>
          </div>
        </div>
      </div>

      {{-- Data e Custo --}}
      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input
              type="date"
              name="maintenanceDate"
              id="maintenanceDate"
              class="form-control"
              placeholder="Data"
              value="{{ old('maintenanceDate',$maintenance->maintenanceDate) }}"
              required
            >
            <label for="maintenanceDate">Data</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input
              type="number"
              step="0.01"
              name="cost"
              id="cost"
              class="form-control"
              placeholder="Custo"
              value="{{ old('cost',$maintenance->cost) }}"
              required
            >
            <label for="cost">Custo (em Kwanza)</label>
          </div>
        </div>
      </div>

      {{-- Descrição --}}
      <div class="row g-3 mt-3">
        <div class="col-md-12">
          <div class="form-floating">
            <textarea
              name="description"
              id="description"
              class="form-control"
              placeholder="Descrição"
              style="height: 80px"
            >{{ old('description',$maintenance->description) }}</textarea>
            <label for="description">Descrição</label>
          </div>
        </div>
      </div>

      {{-- Faturas --}}
      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input type="file" name="invoice_pre" id="invoice_pre" class="form-control">
            <label for="invoice_pre">Fatura Prévia (PDF/Foto)</label>
            @if($maintenance->invoice_pre)
              <small class="text-muted">
                Atual: <a href="{{ Storage::url($maintenance->invoice_pre) }}" target="_blank">ver</a>
              </small>
            @endif
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="file" name="invoice_post" id="invoice_post" class="form-control">
            <label for="invoice_post">Fatura Concluída (PDF/Foto)</label>
            @if($maintenance->invoice_post)
              <small class="text-muted">
                Atual: <a href="{{ Storage::url($maintenance->invoice_post) }}" target="_blank">ver</a>
              </small>
            @endif
          </div>
        </div>
      </div>

      {{-- Botão Atualizar --}}
      <div class="d-grid gap-2 col-md-4 mx-auto mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="bi bi-check-circle me-2"></i>Atualizar Manutenção
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
