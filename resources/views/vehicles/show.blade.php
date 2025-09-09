@extends('layouts.admin.layout')
@section('title','Ver Viatura')
@section('content')

<div class="container my-5">

  {{-- Cabeçalho com voltar e PDF --}}
  <div class="row mb-4">
    <div class="col-8">
      <h3><i class="fas fa-truck me-2"></i>Ver Viatura Nº{{ $vehicle->id }}</h3>
    </div>
    <div class="col-4 text-end">
      <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary btn-sm me-2">
        <i class="bi bi-arrow-left"></i> Voltar
      </a>
      <a href="{{ route('vehicles.showPdf', $vehicle->id) }}" class="btn btn-outline-primary btn-sm" target="_blank">
        <i class="bi bi-download"></i> Baixar PDF
      </a>
    </div>
  </div>

  {{-- Informações gerais --}}
  <div class="row mb-5 align-items-start">
    <div class="col-md-6">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-secondary text-white">
          <strong>Dados da Viatura</strong>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tbody>
              <tr><th class="ps-0">Placa</th><td>{{ $vehicle->plate }}</td></tr>
              <tr><th class="ps-0">Marca</th><td>{{ $vehicle->brand }}</td></tr>
              <tr><th class="ps-0">Modelo</th><td>{{ $vehicle->model }}</td></tr>
              <tr><th class="ps-0">Ano</th><td>{{ $vehicle->yearManufacture }}</td></tr>
              <tr><th class="ps-0">Cor</th><td>{{ $vehicle->color }}</td></tr>
              <tr><th class="ps-0">Capacidade</th><td>{{ $vehicle->loadCapacity }}</td></tr>
              <tr><th class="ps-0">Status</th>
                <td>
                  {{ $vehicle->status=='Available'?'Disponível':($vehicle->status=='InMaintenance'?'Em manutenção':'Indisponível') }}
                </td>
              </tr>
              <tr><th class="ps-0">Observações</th><td>{{ $vehicle->notes ?? '-' }}</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Relações --}}
    <div class="col-md-6">
      {{-- Condutores --}}
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-secondary text-white">
          <strong>Condutores</strong>
        </div>
        <div class="card-body">
          @forelse($vehicle->drivers as $d)
            <p>
              {{ $d->fullName ?? ($d->employee->fullName ?? '-') }}
              <span class="badge bg-primary">
                {{ \Carbon\Carbon::parse($d->pivot->startDate)->format('d/m/Y') }}
                – {{ $d->pivot->endDate ? \Carbon\Carbon::parse($d->pivot->endDate)->format('d/m/Y') : 'Agora' }}
              </span>
            </p>
          @empty
            <p>-</p>
          @endforelse
        </div>
      </div>
      {{-- Manutenções --}}
      <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
          <strong>Histórico de Manutenção</strong>
        </div>
        <div class="card-body">
          @forelse($vehicle->maintenance as $m)
            <p>
              {{ \Carbon\Carbon::parse($m->maintenanceDate)->format('d/m/Y') }}
              – {{ $m->type }} – {{ number_format($m->cost,2,',','.') }}
            </p>
          @empty
            <p>-</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

</div>

@endsection
