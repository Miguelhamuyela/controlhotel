@extends('layouts.admin.layout')
@section('title','Pagamentos de Salário')
@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="fas fa-money-bill-wave me-2"></i>Pagamentos de Salário</span>
    <div>
      <a href="{{ route('salaryPayment.pdfAll') }}" class="btn btn-outline-light btn-sm" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-file-earmark-pdf"></i> Todos (PDF)
      </a>
      <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterArea">
        <i class="bi bi-funnel"></i> Filtrar
      </button>
      <a href="{{ route('salaryPayment.create') }}" class="btn btn-outline-light btn-sm" title="Novo Pagamento">
        <i class="bi bi-plus-circle"></i> Novo
      </a>
    </div>
  </div>
  <div class="collapse" id="filterArea">
    <div class="card-body border-bottom">
      <form class="row g-3" method="GET" action="{{ route('salaryPayment.index') }}">
        <div class="col-md-3">
          <input type="date" name="startDate" value="{{ $filters['startDate'] ?? '' }}" class="form-control">
        </div>
        <div class="col-md-3">
          <input type="date" name="endDate" value="{{ $filters['endDate'] ?? '' }}" class="form-control">
        </div>
        <div class="col-md-2">
          <button class="btn btn-primary w-100">Aplicar</button>
        </div>
        <div class="col-md-2">
          <a href="{{ route('salaryPayment.pdfPeriod', $filters) }}"
             class="btn btn-success w-100" target="_blank" rel="noopener noreferrer">
            PDF Intervalo
          </a>
        </div>
      </form>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Competência</th>
            <th>Funcionário</th>
            <th>Departamento</th>
            <th>Tipo</th>
            <th>IBAN</th>
            <th>Sal. Básico</th>
            <th>Subsídios</th>
            <th>Desconto</th>
            <th>Sal. Líquido</th>
            <th>Pagamento</th>
            <th>Status</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @foreach($salaryPayments as $p)
          <tr>
            <td>{{ \Carbon\Carbon::parse($p->workMonth)->translatedFormat('F/Y') }}</td>
            <td>{{ $p->employee->fullName }}</td>
            <td>{{ $p->employee->department->title ?? '-' }}</td>
            <td>{{ $p->employee->employeeType->name ?? '-' }}</td>
            <td>{{ $p->employee->iban }}</td>
            <td>{{ number_format($p->baseSalary,2,',','.') }}</td>
            <td>{{ number_format($p->subsidies,2,',','.') }}</td>
            <td>{{ number_format($p->discount,2,',','.') }}</td>
            <td><strong>{{ number_format($p->salaryAmount,2,',','.') }}</strong></td>
            <td>{{ $p->paymentDate }}</td>
            <td>{{ $p->paymentStatus }}</td>
            <td class="d-flex gap-1">
              <a href="{{ route('salaryPayment.show',$p->id) }}"   class="btn btn-sm btn-warning" title="Ver Detalhes"><i class="bi bi-eye"></i></a>
              <a href="{{ route('salaryPayment.edit',$p->id) }}"   class="btn btn-sm btn-info" title="Editar Registro"><i class="bi bi-pencil"></i></a>
              <a href="{{ route('salaryPayment.pdfByEmployee', ['employeeId'=>$p->employee->id,'year'=>now()->year]) }}"
                 class="btn btn-sm btn-secondary" title="PDF Anual" target="_blank" rel="noopener noreferrer">
                <i class="bi bi-file-earmark-pdf"></i>
              </a>
              <form action="{{ route('salaryPayment.destroy',$p->id) }}"
                    method="POST" style="display:inline">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-danger" title="Apagar"><i class="bi bi-trash"></i></button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
