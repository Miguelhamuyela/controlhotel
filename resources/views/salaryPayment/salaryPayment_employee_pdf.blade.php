@extends('layouts.admin.pdf')

@section('pdfTitle', "Salários de {{ $employee->fullName }} — {{ $year }}")

@section('titleSection')
  <h4>Salários de {{ $employee->fullName }}</h4>
  <p style="text-align:center;">
    <strong>Departamento:</strong> {{ $employee->department->title ?? '-' }}
  </p>
  <p style="text-align:center;">
    <strong>Tipo de Funcionário:</strong> {{ $employee->employeeType->name ?? '-' }}
  </p>
  <p style="text-align:center;">
    <strong>Ano:</strong> {{ $year }}
  </p>
  <p style="text-align:center;">
    <strong>Total de Lançamentos:</strong> {{ $payments->count() }}
  </p>
@endsection

@section('contentTable')
  @if($payments->count())
    <table>
      <thead>
        <tr>
          <th>Competência</th>
          <th>Bruto (Kz)</th>
          <th>Desconto (Kz)</th>
          <th>Líquido (Kz)</th>
          <th>Data</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($payments as $p)
          <tr>
            <td>{{ \Carbon\Carbon::parse($p->workMonth)->translatedFormat('F/Y') }}</td>
            {{-- bruto = base + subsídios --}}
            <td>{{ number_format($p->baseSalary + $p->subsidies, 2, ',', '.') }}</td>
            <td>{{ number_format($p->discount, 2, ',', '.') }}</td>
            <td>{{ number_format($p->salaryAmount, 2, ',', '.') }}</td>
            <td>{{ \Carbon\Carbon::parse($p->paymentDate)->format('d/m/Y') }}</td>
            <td>{{ $p->paymentStatus }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align:center;">Sem pagamentos no ano {{ $year }}.</p>
  @endif
@endsection
