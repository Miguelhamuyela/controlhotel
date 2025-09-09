@extends('layouts.admin.layout')
@section('title','Novo Trabalho Extra')
@section('content')
<div class="card mb-4 shadow">
    <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <h4>Novo Trabalho Extra</h4>
        <a href="{{ route('extras.index') }}" class="btn btn-outline-light btn-sm">Voltar</a>
      </div>
  <div class="card-body">
    <form method="POST" action="{{ route('extras.store') }}" id="jobForm">
      @csrf

      {{-- Compact fields in one row --}}
      <div class="row">
        <div class="col-md-3 mb-3">
          <label class="form-label">Título</label>
          <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Total (Kz)</label>
          <input type="text" name="totalValue" class="form-control currency" value="{{ old('totalValue') }}" required>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-control" required>
            <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>Pendente</option>
            <option value="Approved" {{ old('status') == 'Approved' ? 'selected' : '' }}>Aprovado</option>
            <option value="Rejected" {{ old('status') == 'Rejected' ? 'selected' : '' }}>Recusado</option>
          </select>
        </div>
        <div class="col-md-3 mb-3">
          <label class="form-label">Buscar</label>
          <input type="text" id="employeeSearch" class="form-control" placeholder="Nome...">
          <div id="employeeList" class="list-group mt-1"></div>
        </div>
      </div>

      <h5>Participantes Selecionados</h5>
      <table class="table" id="selectedTable">
        <thead>
          <tr>
            <th>Funcionário</th>
            <th>Departamento</th>
            <th>Ajus. (Kz)</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
      <button type="submit" class="btn btn-success">Salvar</button>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const employeeSearch = document.getElementById('employeeSearch'),
        employeeList   = document.getElementById('employeeList'),
        selectedTable  = document.querySelector('#selectedTable tbody');
  let selectedEmployees = {};

  const searchUrl = "{{ route('extras.searchEmployee') }}";

  employeeSearch.addEventListener('input', async () => {
    const query = employeeSearch.value.trim();
    employeeList.innerHTML = '';
    if (!query) return;
    try {
      const response = await fetch(`${searchUrl}?q=${encodeURIComponent(query)}`, {
        headers: {'Accept':'application/json'}
      });
      if (!response.ok) return;
      const data = await response.json();
      data.forEach(employee => {
        if (selectedEmployees[employee.id]) return;
        const item = document.createElement('a');
        item.href = '#';
        item.className = 'list-group-item list-group-item-action';
        item.innerHTML = `<strong>${employee.text}</strong><br><small>${employee.extra}</small>`;
        item.onclick = event => { event.preventDefault(); addParticipant(employee); };
        employeeList.appendChild(item);
      });
    } catch(error) {
      console.error('Falha ao buscar funcionários:', error);
    }
  });

  function addParticipant(employee) {
    selectedEmployees[employee.id] = true;
    const tableRow = document.createElement('tr');
    tableRow.dataset.id = employee.id;
    tableRow.innerHTML = `
      <td>
        ${employee.text}
        <input type="hidden" name="participants[]" value="${employee.id}">
      </td>
      <td>${employee.extra}</td>
      <td><input type="text" name="bonus[${employee.id}]" class="form-control currency" value="0"></td>
      <td><button type="button" class="btn btn-sm btn-danger remove-participant">Remover</button></td>
    `;
    tableRow.querySelector('.remove-participant').onclick = () => {
      delete selectedEmployees[employee.id];
      tableRow.remove();
    };
    selectedTable.appendChild(tableRow);
    employeeList.innerHTML = '';
    employeeSearch.value = '';
  }
});
</script>
@endpush

