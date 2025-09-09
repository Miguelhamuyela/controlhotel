@extends('layouts.admin.layout')
@section('title', 'Histórico de ' . $emp->fullName)
@section('content')
<div class="card mb-4">
  <div class="card-header bg-secondary text-white">
    <h3>Currículo: {{ $emp->fullName }}</h3>
  </div>
  <div class="card-body">
    <div class="row mb-4">
      <div class="col-md-3 text-center">
        <img src="{{ asset('frontend/images/departments/' . $emp->photo) }}"
             class="img-fluid rounded-circle" alt="Foto">
      </div>
      <div class="col-md-9">
        <p><strong>Departamento Atual:</strong> {{ $emp->department->title ?? '-' }}</p>
        <p><strong>Tipo:</strong> {{ $emp->employeeType->name ?? '-' }}</p>
        <p><strong>E-mail:</strong> {{ $emp->email }}</p>
        <p><strong>Nacionalidade:</strong> {{ $emp->nationality }}</p>
      </div>
    </div>

    {{-- Seções cronológicas --}}
    <h4>Histórico de Cargos</h4>
    <ul class="list-group mb-4">
      @foreach($emp->positionHistories as $h)
      <li class="list-group-item">
        {{ \Carbon\Carbon::parse($h->startDate)->format('d/m/Y') }}
        @if($h->endDate)
          até {{ \Carbon\Carbon::parse($h->endDate)->format('d/m/Y') }}
        @else
          até atualmente
        @endif
        — <strong>{{ $h->position->name }}</strong>
      </li>
      @endforeach
    </ul>

    <h4>Mobilidades</h4>
    <ul class="list-group mb-4">
      @foreach($emp->mobilities as $m)
      <li class="list-group-item">
        {{ \Carbon\Carbon::parse($m->created_at)->format('d/m/Y') }}
        — Transferido: <em>{{ $m->oldDepartment->title }} → {{ $m->newDepartment->title }}</em>
        <br><small>Motivo: {{ $m->causeOfMobility }}</small>
      </li>
      @endforeach
    </ul>

    <h4>Destacamentos</h4>
    <ul class="list-group mb-4">
      @foreach($emp->secondments as $s)
      <li class="list-group-item">
        {{ \Carbon\Carbon::parse($s->created_at)->format('d/m/Y') }}
        — Destaque para <strong>{{ $s->institution }}</strong>
        <br><small>Motivo: {{ $s->causeOfTransfer }}</small>
      </li>
      @endforeach
    </ul>

    <h4>Trabalhos Extras</h4>
    <ul class="list-group mb-4">
      @foreach($emp->extraJobs as $j)
      <li class="list-group-item">
        {{ $j->title }} ({{ \Carbon\Carbon::parse($j->created_at)->format('d/m/Y') }})
        <br><small>Recebeu: {{ number_format($j->pivot->assignedValue,2,',','.') }} Kz</small>
      </li>
      @endforeach
    </ul>

    <h4>Pagamentos de Salário</h4>
    <table class="table table-striped">
      <thead>
        <tr><th>Competência</th><th>Bruto</th><th>Desconto</th><th>Líquido</th><th>Status</th></tr>
      </thead>
      <tbody>
        @foreach($emp->salaryPayments as $p)
        <tr>
          <td>{{ \Carbon\Carbon::parse($p->workMonth)->translatedFormat('F/Y') }}</td>
          <td>{{ number_format($p->baseSalary + $p->subsidies,2,',','.') }}</td>
          <td>{{ number_format($p->discount,2,',','.') }}</td>
          <td>{{ number_format($p->salaryAmount,2,',','.') }}</td>
          <td>{{ __('status.' . strtolower($p->paymentStatus)) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection