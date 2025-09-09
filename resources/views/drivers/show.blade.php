@extends('layouts.admin.layout')
@section('title','Ver Motorista')
@section('content')

<div class="container my-5">

  {{-- Cabeçalho com voltar e PDF --}}
  <div class="row mb-4">
    <div class="col-8">
      <h3><i class="bi bi-person-badge-fill me-2"></i>Ver Motorista #{{ $driver->id }}</h3>
    </div>
    <div class="col-4 text-end">
      <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary btn-sm me-2">
        <i class="bi bi-arrow-left"></i> Voltar
      </a>
      <a href="{{ route('drivers.showPdf', $driver->id) }}" class="btn btn-outline-primary btn-sm" target="_blank">
        <i class="bi bi-download"></i> Baixar PDF
      </a>
    </div>
  </div>

  {{-- Informações Pessoais e Profissionais --}}
  <div class="row mb-5 align-items-start">
    <div class="col-md-6">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-secondary text-white">
          <strong>Dados do Motorista</strong>
        </div>
        <div class="card-body">
          <table class="table table-borderless mb-0">
            <tbody>
              <tr><th class="ps-0">Funcionário</th>
                  <td>{{ $driver->employee->fullName ?? '-' }}</td></tr>
              <tr><th class="ps-0">Nome Completo</th>
                  <td>{{ $driver->fullName ?? '-' }}</td></tr>
              <tr><th class="ps-0">B.I.</th>
                  <td>{{ $driver->bi ?? '-' }}</td></tr>
              <tr><th class="ps-0">Nº da Carta</th>
                  <td>{{ $driver->licenseNumber }}</td></tr>
              <tr><th class="ps-0">Categoria</th>
                  <td>{{ $driver->licenseCategory }}</td></tr>
              <tr><th class="ps-0">Validade</th>
                  <td>{{ \Carbon\Carbon::parse($driver->licenseExpiry)->format('d/m/Y') }}</td></tr>
              <tr><th class="ps-0">Status</th>
                  <td>{{ $driver->status=='Active'?'Ativo':'Inativo' }}</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Viaturas atribuídas --}}
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white">
          <strong>Viaturas Atribuídas</strong>
        </div>
        <div class="card-body">
          @forelse($driver->vehicles as $v)
            <p><span class="badge bg-secondary">{{ $v->plate }}</span></p>
          @empty
            <p>-</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

</div>

@endsection
