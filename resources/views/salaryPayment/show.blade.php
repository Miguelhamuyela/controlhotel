@extends('layouts.admin.layout')
@section('title','Detalhes Pagamento')
@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span>Detalhes do Pagamento</span>
    <a href="{{ route('salaryPayment.index') }}" class="btn btn-outline-light btn-sm"><i class="bi bi-arrow-left"></i> Voltar</a>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr><th>Competência</th><td>{{ \Carbon\Carbon::parse($salaryPayment->workMonth)->translatedFormat('F/Y') }}</td></tr>
      <tr><th>Funcionário</th><td>{{ $salaryPayment->employee->fullName }}</td></tr>
      <tr><th>Departamento</th><td>{{ $salaryPayment->employee->department->title ?? '-' }}</td></tr>
      <tr><th>Tipo</th><td>{{ $salaryPayment->employee->employeeType->name ?? '-' }}</td></tr>
      <tr><th>IBAN</th><td>{{ $salaryPayment->employee->iban }}</td></tr>
      <tr><th>Sal. Básico</th><td>{{ $salaryPayment->baseSalary }}</td></tr>
      <tr><th>Subsídios</th><td>{{ $salaryPayment->subsidies }}</td></tr>
      <tr><th>Desconto</th><td>{{ $salaryPayment->discount }}</td></tr>
      <tr><th>Sal. Líquido</th><td>{{ $salaryPayment->salaryAmount }}</td></tr>
      <tr><th>Pago em</th><td>{{ $salaryPayment->paymentDate }}</td></tr>
      <tr><th>Status</th><td>{{ $salaryPayment->paymentStatus }}</td></tr>
      <tr><th>Comentário</th><td>{{ $salaryPayment->paymentComment ?? '-' }}</td></tr>
      <tr><th>Criado em</th><td>{{ $salaryPayment->created_at->format('d/m/Y H:i') }}</td></tr>
    </table>
  </div>
</div>
@endsection