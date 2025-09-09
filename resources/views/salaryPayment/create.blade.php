@extends('layouts.admin.layout')
@section('title','Adicionar Pagamento de Salário')
@section('content')
<div class="row justify-content-center">
  <div class="{{ isset($employee) ? 'col-md-5' : 'col-md-8' }}">
    <div class="card mb-4 shadow">
      <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <h4>Adicionar Pagamento de Salário</h4>
        <a href="{{ route('salaryPayment.index') }}" class="btn btn-outline-light btn-sm">
          <i class="bi bi-arrow-left"></i> Voltar
        </a>
      </div>
      <div class="card-body">
        @if(!isset($employee))
          {{-- busca funcionário --}}
          <form method="GET" action="{{ route('salaryPayment.searchEmployee') }}" class="mb-4">
            <div class="row g-3">
              <div class="col-md-8">
                <div class="form-floating">
                  <input type="text" name="employeeSearch" class="form-control"
                         placeholder="Pesquisar por ID ou Nome do Funcionário"
                         value="{{ old('employeeSearch') }}">
                  <label for="employeeSearch">Nome do Funcionário</label>
                </div>
                @error('employeeSearch')<div class="text-danger small">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-4">
                <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Buscar</button>
              </div>
            </div>
          </form>
        @else
          {{-- dados do funcionário --}}
          <div class="mb-3">
            <h5>Dados do Funcionário</h5>
            <p><strong>Nome:</strong> {{ $employee->fullName }}</p>
            <p><strong>E-mail:</strong> {{ $employee->email }}</p>
            <p><strong>Departamento:</strong> {{ $employee->department->title ?? '-' }}</p>
            <p><strong>Tipo de Funcionário:</strong> {{ $employee->employeeType->name ?? '-' }}</p>
            <p><strong>IBAN:</strong> {{ $employee->iban ?? '-' }}</p>
          </div>

          <form id="salaryForm" method="POST" action="{{ route('salaryPayment.store') }}">
            @csrf
            <input type="hidden" name="employeeId" value="{{ $employee->id }}">

            {{-- Mês de Competência --}}
            @php
              Carbon\Carbon::setLocale('pt_BR');
              $current = now();
              $start   = $current->copy()->startOfYear();
              $end     = $current->copy()->startOfMonth();
              $months  = [];
              while($start->lte($end)) {
                $months[] = $start->copy();
                $start->addMonth();
              }
            @endphp
            <div class="mb-3">
              <label for="workMonth" class="form-label">Mês de Competência</label>
              <select name="workMonth" id="workMonth" class="form-select" required>
                @foreach($months as $m)
                  <option value="{{ $m->format('Y-m') }}"
                    {{ old('workMonth', now()->format('Y-m')) == $m->format('Y-m') ? 'selected' : '' }}>
                    {{ $m->translatedFormat('F/Y') }}
                  </option>
                @endforeach
              </select>
            </div>

            {{-- Campos salário e descontos --}}
            <div class="mb-3">
              <label for="baseSalary" class="form-label">Salário Básico (Kz)</label>
              <input type="text" name="baseSalary" id="baseSalary" class="form-control currency"
                     value="{{ old('baseSalary',0) }}" required>
            </div>
            <div class="mb-3">
              <label for="subsidies" class="form-label">Subsídios (Kz)</label>
              <input type="text" name="subsidies" id="subsidies" class="form-control currency"
                     value="{{ old('subsidies',0) }}" required>
            </div>
            <div class="mb-3">
              <label for="irtRate" class="form-label">IRT (%)</label>
              <input type="text" name="irtRate" id="irtRate" class="form-control currency"
                     value="{{ old('irtRate',0) }}" required>
            </div>
            <div class="mb-3">
              <label for="inssRate" class="form-label">INSS (%)</label>
              <input type="text" name="inssRate" id="inssRate" class="form-control currency"
                     value="{{ old('inssRate',0) }}" required>
            </div>
            <div class="mb-3">
              <label for="paymentDate" class="form-label">Data de Pagamento</label>
              <input type="date" name="paymentDate" id="paymentDate" class="form-control"
                     value="{{ old('paymentDate', now()->format('Y-m-d')) }}">
            </div>
            <div class="mb-3">
              <label for="discount" class="form-label">Desconto (Kz)</label>
              <input type="text" name="discount" id="discount" class="form-control currency"
                     value="{{ old('discount',0) }}">
              <small id="absentInfo" class="form-text text-muted"></small>
            </div>
            <div class="mb-3">
              <label for="paymentStatus" class="form-label">Status do Pagamento</label>
              <select name="paymentStatus" id="paymentStatus" class="form-select" required>
                <option value="Pending"   {{ old('paymentStatus')=='Pending'   ? 'selected':'' }}>Pendente</option>
                <option value="Completed" {{ old('paymentStatus')=='Completed' ? 'selected':'' }}>Concluído</option>
                <option value="Failed"    {{ old('paymentStatus')=='Failed'    ? 'selected':'' }}>Falhou</option>
              </select>
            </div>
            <div class="mb-3">
              <label for="paymentComment" class="form-label">Comentário</label>
              <textarea name="paymentComment" id="paymentComment" class="form-control">{{ old('paymentComment') }}</textarea>
            </div>
            <button type="submit" class="btn btn-success w-100">
              <i class="bi bi-check-circle"></i> Salvar Pagamento
            </button>
          </form>

          @push('scripts')
          <script>
            document.addEventListener('DOMContentLoaded', () => {
              const baseEl  = document.getElementById('baseSalary');
              const subsEl  = document.getElementById('subsidies');
              const monthEl = document.getElementById('workMonth');
              const discEl  = document.getElementById('discount');
              const infoEl  = document.getElementById('absentInfo');
              const empId   = document.querySelector('input[name="employeeId"]').value;
              const unmask  = v => parseFloat(v.replace(/\./g,'').replace(/,/g,'.'))||0;
              const mask    = v => v.toFixed(2).replace('.',',').replace(/\B(?=(\d{3})+(?!\d))/g,'.');
              function updateDiscount(){
                const base = unmask(baseEl.value),
                      subs = unmask(subsEl.value),
                      wm   = monthEl.value;
                fetch(`{{ route('salaryPayment.calculateDiscount') }}?employeeId=${empId}`+
                      `&baseSalary=${base}&subsidies=${subs}&workMonth=${wm}`)
                  .then(r=>r.json()).then(json=>{
                    discEl.value       = mask(json.discount);
                    infoEl.textContent = `Faltas em ${wm}: ${json.absentDays}`;
                  });
              }
              [baseEl,subsEl,monthEl].forEach(el=>el.addEventListener('change',updateDiscount));
              updateDiscount();
            });
          </script>
          @endpush
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
