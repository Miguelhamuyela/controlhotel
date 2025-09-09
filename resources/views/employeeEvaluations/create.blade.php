@extends('layouts.admin.layout')
@section('title','Nova Avaliação de Funcionário')
@section('content')
<div class="card my-4 shadow">
  <div class="card-header bg-primary text-white">
    <h4>Novo Relatório de Avaliação</h4>
  </div>
  <div class="card-body">
    <form id="form-eval" method="POST" action="{{ route('employeeEvaluations.store') }}">
      @csrf
      <input type="hidden" name="employeeId" id="employee-id">

      {{-- Campo de busca curto e centralizado --}}
      <div class="row justify-content-center mb-4">
        <div class="col-md-4 position-relative">
          <input type="text" id="employee-search" class="form-control" placeholder="Pesquisar funcionário..." autocomplete="off">
          <ul id="search-results" class="list-group position-absolute w-100" style="z-index:2000"></ul>
        </div>
      </div>

      {{-- Campos ocultos até seleção --}}
      <div id="fields" style="display:none">
        <div class="row g-3">
          <div class="col-md-5">
            <label class="form-label">Data</label>
            <input type="date" name="evaluationDate" class="form-control" required>
          </div>
          <div class="col-md-5 offset-md-2">
            <label class="form-label">Avaliador</label>
            <input type="text" name="evaluator" class="form-control" value="{{ $currentUserName }}" required>
          </div>
        </div>
        <div class="row g-3 mt-3">
          <div class="col-md-5">
            <label class="form-label">Nota</label>
            <input type="number" step="0.01" name="overallScore" class="form-control" required>
          </div>
          <div class="col-md-5 offset-md-2"></div>
        </div>
        <div class="row mt-4">
          <div class="col-md-6 offset-md-3">
            <label class="form-label">Comentários</label>
            <textarea name="comments" class="form-control" rows="4"></textarea>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col text-center">
            <button type="submit" class="btn btn-success w-50">Salvar Avaliação</button>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  const input = document.getElementById('employee-search'),
        list  = document.getElementById('search-results'),
        hid   = document.getElementById('employee-id'),
        fields= document.getElementById('fields');
  let timer;

  input.addEventListener('input', () => {
    clearTimeout(timer);
    const q = input.value.trim();
    if (q.length < 2) {
      list.innerHTML = '';
      fields.style.display = 'none';
      return;
    }
    timer = setTimeout(() => {
      fetch(`{{ route('employeeEvaluations.searchEmployee') }}?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(data => {
          list.innerHTML = data.map(emp =>
            `<li class="list-group-item list-group-item-action" data-id="${emp.id}">
               ${emp.fullName}
             </li>`
          ).join('');
        });
    }, 200);
  });

  list.addEventListener('click', e => {
    if (!e.target.dataset.id) return;
    input.value = e.target.textContent.trim();
    hid.value   = e.target.dataset.id;
    list.innerHTML = '';
    fields.style.display = 'block';
  });
</script>
@endpush
@endsection
