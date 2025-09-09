@extends('layouts.admin.pdf')
@section('pdfTitle','Relatório de Pagamentos')
@section('titleSection')
  <h4>Relatório de Pagamentos de Salário</h4>
  <p style="text-align:center"><strong>Total:</strong> <ins>{{ $salaryPayments->count() }}</ins></p>
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
          <th>Bruto (Kz)</th>
          <th>Subsídios (Kz)</th>
          <th>Desconto (Kz)</th>
          <th>Líquido (Kz)</th>
          <th>Data Pag.</th>
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
          <td>{{ number_format($p->baseSalary, 2, ',', '.') }}</td>
          <td>{{ number_format($p->subsidies, 2, ',', '.') }}</td>
          <td>{{ number_format($p->discount, 2, ',', '.') }}</td>
          <td>{{ number_format($p->salaryAmount, 2, ',', '.') }}</td>
          <td>{{ \Carbon\Carbon::parse($p->paymentDate)->format('d/m/Y') }}</td>
          <td>{{ $p->paymentStatus }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align:center">Nenhum pagamento registrado.</p>
  @endif
@endsection
