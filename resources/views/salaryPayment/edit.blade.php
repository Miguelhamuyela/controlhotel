@extends('layouts.admin.layout')
@section('title', 'Editar Pagamento de Salário')
@section('content')

<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card mb-4 shadow">
      <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <h4>Editar Pagamento de Salário</h4>
        <a href="{{ route('salaryPayment.index') }}" class="btn btn-outline-light btn-sm">
          <i class="bi bi-arrow-left"></i> Voltar
        </a>
      </div>
      <div class="card-body">

        {{-- Dados do Funcionário --}}
        <div class="mb-3">
          <h5>Dados do Funcionário</h5>
          <p><strong>Nome:</strong> {{ $salaryPayment->employee->fullName }}</p>
          <p><strong>E-mail:</strong> {{ $salaryPayment->employee->email }}</p>
          <p><strong>Departamento:</strong> {{ $salaryPayment->employee->department->title ?? '-' }}</p>
          <p><strong>IBAN:</strong> {{ $salaryPayment->employee->iban ?? '-' }}</p>
        </div>

        <form id="salaryForm" method="POST" action="{{ route('salaryPayment.update', $salaryPayment->id) }}">
          @csrf
          @method('PUT')

          <input type="hidden" name="employeeId" value="{{ $salaryPayment->employeeId }}">

          {{-- Mês de Competência --}}
          @php
            \Carbon\Carbon::setLocale('pt_BR');
            $current = now();
            $start   = $current->copy()->startOfYear();
            $end     = $current->copy()->startOfMonth();
            $months  = [];
            while ($start->lte($end)) {
                $months[] = $start->copy();
                $start->addMonth();
            }
          @endphp
          <div class="mb-3">
            <label for="workMonth" class="form-label">Mês de Competência</label>
            <select name="workMonth" id="workMonth" class="form-select" required>
              @foreach ($months as $m)
                <option value="{{ $m->format('Y-m') }}"
                  {{ old('workMonth', \Carbon\Carbon::parse($salaryPayment->workMonth)->format('Y-m')) == $m->format('Y-m') ? 'selected' : '' }}>
                  {{ $m->translatedFormat('F/Y') }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- Salário Básico e Subsídios --}}
          <div class="mb-3">
            <label for="baseSalary" class="form-label">Salário Básico (Kz)</label>
            <input type="text"
                   name="baseSalary"
                   id="baseSalary"
                   class="form-control currency"
                   value="{{ old('baseSalary', $salaryPayment->baseSalary) }}"
                   required>
          </div>
          <div class="mb-3">
            <label for="subsidies" class="form-label">Subsídios (Kz)</label>
            <input type="text"
                   name="subsidies"
                   id="subsidies"
                   class="form-control currency"
                   value="{{ old('subsidies', $salaryPayment->subsidies) }}"
                   required>
          </div>

          {{-- IRT e INSS --}}
          <div class="mb-3">
            <label for="irtRate" class="form-label">IRT (%)</label>
            <input type="text"
                   name="irtRate"
                   id="irtRate"
                   class="form-control currency"
                   value="{{ old('irtRate', $salaryPayment->irtRate) }}"
                   required>
          </div>
          <div class="mb-3">
            <label for="inssRate" class="form-label">INSS (%)</label>
            <input type="text"
                   name="inssRate"
                   id="inssRate"
                   class="form-control currency"
                   value="{{ old('inssRate', $salaryPayment->inssRate) }}"
                   required>
          </div>

          {{-- Data de Pagamento --}}
          <div class="mb-3">
            <label for="paymentDate" class="form-label">Data de Pagamento</label>
            <input type="date"
                   name="paymentDate"
                   id="paymentDate"
                   class="form-control"
                   value="{{ old('paymentDate', $salaryPayment->paymentDate) }}">
          </div>

          {{-- Desconto e Informação de Faltas --}}
          <div class="mb-3">
            <label for="discount" class="form-label">Desconto (Kz)</label>
            <input type="text"
                   name="discount"
                   id="discount"
                   class="form-control currency"
                   value="{{ old('discount', $salaryPayment->discount) }}">
            <small id="absentInfo" class="form-text text-muted"></small>
          </div>

          {{-- Status e Comentário --}}
          <div class="mb-3">
            <label for="paymentStatus" class="form-label">Status do Pagamento</label>
            <select name="paymentStatus" id="paymentStatus" class="form-select" required>
              <option value="Pending"   {{ old('paymentStatus', $salaryPayment->paymentStatus) == 'Pending'   ? 'selected' : '' }}>Pendente</option>
              <option value="Completed" {{ old('paymentStatus', $salaryPayment->paymentStatus) == 'Completed' ? 'selected' : '' }}>Concluído</option>
              <option value="Failed"    {{ old('paymentStatus', $salaryPayment->paymentStatus) == 'Failed'    ? 'selected' : '' }}>Falhou</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="paymentComment" class="form-label">Comentário</label>
            <textarea name="paymentComment"
                      id="paymentComment"
                      class="form-control"
            >{{ old('paymentComment', $salaryPayment->paymentComment) }}</textarea>
          </div>

          <button type="submit" class="btn btn-success w-100">
            <i class="bi bi-check-circle"></i> Atualizar Pagamento
          </button>
        </form>

        @push('scripts')
        <script>
          document.addEventListener('DOMContentLoaded', () => {
            const baseEl   = document.getElementById('baseSalary');
            const subsEl   = document.getElementById('subsidies');
            const monthEl  = document.getElementById('workMonth');
            const discEl   = document.getElementById('discount');
            const infoEl   = document.getElementById('absentInfo');
            const empId    = document.querySelector('input[name="employeeId"]').value;
            const unmask   = v => parseFloat(v.replace(/\./g,'').replace(/,/g,'.')) || 0;
            const mask     = v => v.toFixed(2).replace('.',',').replace(/\B(?=(\d{3})+(?!\d))/g,'.');

            function updateDiscount() {
              const base = unmask(baseEl.value),
                    subs = unmask(subsEl.value),
                    wm   = monthEl.value;

              fetch(`{{ route('salaryPayment.calculateDiscount') }}?employeeId=${empId}` +
                    `&baseSalary=${base}&subsidies=${subs}&workMonth=${wm}`)
                .then(r => r.json()).then(json => {
                  discEl.value       = mask(json.discount);
                  infoEl.textContent = `Faltas em ${wm}: ${json.absentDays}`;
                });
            }

            [baseEl, subsEl, monthEl].forEach(el => el.addEventListener('change', updateDiscount));
            updateDiscount();
          });
        </script>
        @endpush

      </div>
    </div>
  </div>
</div>

@endsection
