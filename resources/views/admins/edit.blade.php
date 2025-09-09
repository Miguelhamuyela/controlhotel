@extends('layouts.admin.layout')
@section('title', 'Editar Administrador')
@section('content')
<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-pencil-square me-2"></i>Editar Administrador</span>
    <a href="{{ route('admins.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
      <i class="bi bi-arrow-left"></i> Voltar
    </a>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admins.update', $admin->id) }}">
      @csrf
      @method('PUT')
      <div class="row g-3">
        <div class="col-md-6">
          <div class="form-floating">
            <select name="employeeId" id="employeeId" class="form-select">
              <option value="">Selecione um Funcionário (Opcional)</option>
              @foreach($employees as $employee)
                <option value="{{ $employee->id }}"
                        data-email="{{ $employee->email }}"
                        data-fullname="{{ $employee->fullName }}"
                        data-photo="{{ $employee->photo ? asset('frontend/images/departments/'.$employee->photo) : asset('frontend/images/default.png') }}"
                        {{ $admin->employeeId == $employee->id ? 'selected' : '' }}>
                  {{ $employee->fullName }}
                </option>
              @endforeach
            </select>
            <label for="employeeId">Funcionário Vinculado</label>
          </div>
        </div>
        <!-- Campo extra para exibir o nome do funcionário automaticamente -->
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" id="employeeFullName" class="form-control" placeholder="Nome do Funcionário" readonly>
            <label for="employeeFullName">Nome do Funcionário</label>
          </div>
        </div>
      </div>

      <!-- Exibição da fotografia do funcionário vinculado -->
      <div class="row g-3 mt-3" id="employeePhotoContainer" style="display: none;">
        <div class="col-md-12 text-center">
          <img id="employeePhoto" src="" alt="Foto do Funcionário" style="max-height: 150px; border-radius: 50%;">
        </div>
      </div>

      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <div class="form-floating">
            <select name="role" id="role" class="form-select" required>
              <option value="">Selecione o Papel</option>
              <option value="admin" {{ $admin->role == 'admin' ? 'selected' : '' }}>Administrador</option>
              <option value="director" {{ $admin->role == 'director' ? 'selected' : '' }}>Diretor</option>
              <option value="department_head" {{ $admin->role == 'department_head' ? 'selected' : '' }}>Chefe de Departamento</option>
              <option value="employee" {{ $admin->role == 'employee' ? 'selected' : '' }}>Funcionário</option>
            </select>
            <label for="role">Papel</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ $admin->email }}" required>
            <label for="email">Email</label>
          </div>
        </div>
      </div>
      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input type="password" name="password" class="form-control" placeholder="Senha">
            <label for="password">Nova Senha (deixe em branco para não alterar)</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirme a Senha">
            <label for="password_confirmation">Confirme a Senha</label>
          </div>
        </div>
      </div>
      <div class="mt-3 text-center">
        <button type="submit" class="btn btn-success">
          <i class="bi bi-check-circle"></i> Atualizar Usuário
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // Função para atualizar os campos com dados do funcionário selecionado
  function updateEmployeeFields() {
    var select = document.getElementById('employeeId');
    var selectedOption = select.options[select.selectedIndex];
    var emailField = document.getElementById('email');
    var fullNameField = document.getElementById('employeeFullName');
    var photoContainer = document.getElementById('employeePhotoContainer');
    var photoElement = document.getElementById('employeePhoto');

    if (select.value) {
      emailField.value = selectedOption.getAttribute('data-email') || '';
      fullNameField.value = selectedOption.getAttribute('data-fullname') || '';
      photoElement.src = selectedOption.getAttribute('data-photo') || '';
      photoContainer.style.display = 'block';
    } else {
      emailField.value = '';
      fullNameField.value = '';
      photoElement.src = '';
      photoContainer.style.display = 'none';
    }
  }

  document.getElementById('employeeId').addEventListener('change', updateEmployeeFields);

  // Executa a função ao carregar a página para preencher os campos caso já esteja selecionado
  window.addEventListener('load', function() {
    updateEmployeeFields();
    // Se necessário, inclua lógica para exibir campos extras de acordo com o papel
    var role = document.getElementById('role');
    role.dispatchEvent(new Event('change'));
  });

  // Lógica para exibir/ocultar campos extras conforme o papel selecionado (se implementados)
  document.getElementById('role').addEventListener('change', function() {
    // Exemplo:
    // document.getElementById('department_head_fields').style.display = (this.value === 'department_head') ? 'block' : 'none';
    // document.getElementById('director_fields').style.display = (this.value === 'director') ? 'block' : 'none';
  });
</script>
@endsection
