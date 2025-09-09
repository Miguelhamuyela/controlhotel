@extends('layouts.admin.pdf')

@section('pdfTitle', "Pagamentos de {$startDate} a {$endDate}")

@section('titleSection')
  <h4>Pagamentos de Salário</h4>
  <p style="text-align:center;"><strong>Período:</strong> {{ $startDate }} — {{ $endDate }}</p>
  <p style="text-align:center;"><strong>Total:</strong> {{ $salaryPayments->count() }}</p>
@endsection

@section('contentTable')
  @if($salaryPayments->count())
  <table>
    <thead>
      <tr>
        <th>Competência</th>
        <th>Funcionário</th>
        <th>Tipo</th>
        <th>Departamento</th>
        <th>IBAN</th>
        <th>Bruto (Kz)</th>
        <th>Desconto (Kz)</th>
        <th>Líquido (Kz)</th>
        <th>Data</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach($salaryPayments as $p)
      <tr>
        <td>{{ \Carbon\Carbon::parse($p->workMonth)->translatedFormat('F/Y') }}</td>
        <td>{{ $p->employee->fullName }}</td>
        <td>{{ $p->employee->employeeType->name ?? '-' }}</td>
        <td>{{ $p->employee->department->title ?? '-' }}</td>
        <td>{{ $p->employee->iban }}</td>
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
    <p style="text-align:center;">Nenhum pagamento neste período.</p>
  @endif
@endsection
