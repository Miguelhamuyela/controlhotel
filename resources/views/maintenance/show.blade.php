@extends('layouts.admin.layout')
@section('title','Ver Manutenção')
@section('content')

<div class="container my-5">

  {{-- Cabeçalho --}}
  <div class="row mb-4">
    <div class="col-8">
      <h3><i class="bi bi-tools me-2"></i>Ver Manutenção Nº{{ $maintenance->id }}</h3>
    </div>
    <div class="col-4 text-end">
      <a href="{{ route('maintenance.index') }}" class="btn btn-outline-secondary btn-sm me-2">
        <i class="bi bi-arrow-left"></i> Voltar
      </a>
      <a href="{{ route('maintenance.showPdf', $maintenance) }}" class="btn btn-outline-primary btn-sm" target="_blank">
        <i class="bi bi-download"></i> Baixar PDF
      </a>
    </div>
  </div>

  {{-- Card --}}
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white text-center">
          <strong>Detalhes da Manutenção</strong>
        </div>
        <div class="card-body">
          <table class="table table-striped table-borderless mb-0">
            <tbody>
              <tr>
                <th class="ps-0">Viatura</th>
                <td>{{ $maintenance->vehicle->plate }} – {{ $maintenance->vehicle->brand }} – {{ $maintenance->vehicle->model }}</td>
              </tr>
              <tr>
                <th class="ps-0">Tipo</th>
                <td>{{ $maintenance->type === 'Preventive' ? 'Preventiva' : 'Corretiva' }}</td>
              </tr>
              <tr>
                <th class="ps-0">Data</th>
                <td>{{ \Carbon\Carbon::parse($maintenance->maintenanceDate)->format('d/m/Y') }}</td>
              </tr>
              <tr>
                <th class="ps-0">Custo</th>
                <td>{{ number_format($maintenance->cost, 2, ',', '.') }}</td>
              </tr>
              <tr>
                <th class="ps-0">Descrição</th>
                <td>{{ $maintenance->description ?? '-' }}</td>
              </tr>

              @if($maintenance->invoice_pre)
              <tr>
                <th class="ps-0">Fatura Prévia</th>
                <td>
                  @if(Str::endsWith($maintenance->invoice_pre, '.pdf'))
                    <a href="{{ asset('frontend/docs/maintenance/pre/'.$maintenance->invoice_pre) }}" target="_blank">
                      Ver / Download PDF
                    </a>
                  @else
                    <img src="{{ asset('frontend/docs/maintenance/pre/'.$maintenance->invoice_pre) }}" style="max-width:200px;">
                  @endif
                </td>
              </tr>
              @endif

              @if($maintenance->invoice_post)
              <tr>
                <th class="ps-0">Fatura Concluída</th>
                <td>
                  @if(Str::endsWith($maintenance->invoice_post, '.pdf'))
                    <a href="{{ asset('frontend/docs/maintenance/post/'.$maintenance->invoice_post) }}" target="_blank">
                      Ver / Download PDF
                    </a>
                  @else
                    <img src="{{ asset('frontend/docs/maintenance/post/'.$maintenance->invoice_post) }}" style="max-width:200px;">
                  @endif
                </td>
              </tr>
              @endif

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
