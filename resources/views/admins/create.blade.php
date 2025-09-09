{{-- resources/views/admins/create.blade.php --}}
@extends('layouts.admin.layout')
@section('title', 'Criar Administrador')
@section('content')
<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-person-plus me-2"></i>Novo Administrador</span>
    <a href="{{ route('admins.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
      <i class="bi bi-arrow-left"></i> Voltar
    </a>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('admins.store') }}" enctype="multipart/form-data">
      @csrf

      {{-- Seleção de funcionário --}}
      <div class="row g-3">
        <div class="col-md-6">
          <div class="form-floating">
            <select name="employeeId" id="employeeId" class="form-select">
              <option value="">Selecione um Funcionário (Opcional)</option>
              @foreach($employees as $employee)
                <option value="{{ $employee->id }}"
                        data-email="{{ $employee->email }}"
                        data-fullname="{{ $employee->fullName }}"
                        data-photo="{{ $employee->photo
                          ? asset('frontend/images/departments/'.$employee->photo)
                          : asset('frontend/images/default.png') }}">
                  {{ $employee->fullName }}
                </option>
              @endforeach
            </select>
            <label for="employeeId">Funcionário Vinculado</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" id="employeeFullName" class="form-control" placeholder="Nome do Funcionário" readonly>
            <label for="employeeFullName">Nome do Funcionário</label>
          </div>
        </div>
      </div>

      {{-- Preview da foto do funcionário --}}
      <div class="row g-3 mt-3" id="employeePhotoContainer" style="display: none;">
        <div class="col-md-12 text-center">
          <img id="employeePhoto" src="" alt="Foto do Funcionário" style="max-height: 150px; border-radius: 50%;">
        </div>
      </div>

      {{-- Papel e e‑mail --}}
      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <div class="form-floating">
            <select name="role" id="role" class="form-select" required>
              <option value="">Selecione o Papel</option>
              <option value="admin">Administrador</option>
              <option value="director">Diretor</option>
              <option value="department_head">Chefe de Departamento</option>
              <option value="employee">Funcionário</option>
            </select>
            <label for="role">Papel</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
            <label for="email">Email</label>
          </div>
        </div>
      </div>

      {{-- Campos Chefe de Departamento --}}
      <div id="department_head_fields" style="display: none;">
        <div class="row g-3 mt-3">
          <div class="col-md-6">
            <div class="form-floating">
              <select name="department_id" id="department_id" class="form-select">
                <option value="">Selecione o Departamento</option>
                @foreach($departments as $dept)
                  <option value="{{ $dept->id }}">{{ $dept->title }}</option>
                @endforeach
              </select>
              <label for="department_id">Departamento</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input type="text" name="department_head_name" id="department_head_name" class="form-control" placeholder="Nome do Chefe">
              <label for="department_head_name">Nome do Chefe de Departamento</label>
            </div>
          </div>
        </div>
        <div class="row g-3 mt-3">
          <div class="col-md-12">
            <div class="form-floating">
              <input type="file" name="photo" class="form-control">
              <label for="photo">Foto do Chefe</label>
            </div>
          </div>
        </div>
      </div>

      {{-- Campos Diretor --}}
      <div id="director_fields" style="display: none;">
        <div class="row g-3 mt-3">
          <div class="col-md-6">
            <div class="form-floating">
              <select name="directorType" id="directorType" class="form-select">
                <option value="">Selecione o tipo de Diretor</option>
                <option value="directorGeneral">Diretor(a) Geral</option>
                <option value="directorTechnical">Diretor(a) da Área Técnica</option>
                <option value="directorAdministrative">Diretor(a) Adjunta para Área Administrativa</option>
              </select>
              <label for="directorType">Tipo de Diretor</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input type="text" name="directorName" id="directorName" class="form-control" placeholder="Nome do Diretor">
              <label for="directorName">Nome do Diretor</label>
            </div>
          </div>
        </div>

        <div class="row g-3 mt-3">
          <div class="col-md-6">
            <div class="form-floating">
              <textarea name="biography" class="form-control" placeholder="Biografia do Diretor" style="height: 100px;"></textarea>
              <label for="biography">Biografia</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-floating">
              <input type="url" name="linkedin" class="form-control" placeholder="Link do LinkedIn">
              <label for="linkedin">LinkedIn</label>
            </div>
          </div>
        </div>

        <div class="row g-3 mt-3">
          <div class="col-md-12">
            <div class="form-floating">
              <input type="file" name="directorPhoto" class="form-control">
              <label for="directorPhoto">Foto do Diretor</label>
            </div>
          </div>
        </div>
      </div>

      {{-- Senha --}}
      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input type="password" name="password" class="form-control" placeholder="Senha" required>
            <label for="password">Senha</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirme a Senha" required>
            <label for="password_confirmation">Confirme a Senha</label>
          </div>
        </div>
      </div>

      <div class="mt-3 text-center">
        <button type="submit" class="btn btn-success">
          <i class="bi bi-check-circle"></i> Salvar Usuário
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  // Popula nome do funcionário nos campos específicos
  function populateRoleFields(fullName) {
    const role = document.getElementById('role').value;
    document.getElementById('department_head_name').value = role === 'department_head' ? fullName : '';
    document.getElementById('directorName').value          = role === 'director' ? fullName : '';
  }

  // Habilita/desabilita 'required' conforme o papel selecionado
  function toggleRequiredFields() {
    const role = document.getElementById('role').value;

    // Campos de Diretor
    document.getElementById('directorType').required = (role === 'director');
    document.getElementById('directorName').required = (role === 'director');

    // Campos de Chefe de Departamento
    document.getElementById('department_id').required           = (role === 'department_head');
    document.getElementById('department_head_name').required    = (role === 'department_head');
  }

  // Quando muda o funcionário vinculado
  document.getElementById('employeeId').addEventListener('change', function () {
    const sel   = this.options[this.selectedIndex];
    const email = sel.getAttribute('data-email')    || '';
    const name  = sel.getAttribute('data-fullname') || '';
    const photo = sel.getAttribute('data-photo')    || '';

    document.getElementById('email').value               = email;
    document.getElementById('employeeFullName').value    = name;
    document.getElementById('employeePhoto').src         = photo;
    document.getElementById('employeePhotoContainer').style.display = this.value ? 'block' : 'none';

    populateRoleFields(name);
  });

  // Quando muda o papel
  document.getElementById('role').addEventListener('change', function () {
    const isHead     = this.value === 'department_head';
    const isDirector = this.value === 'director';

    document.getElementById('department_head_fields').style.display = isHead ? 'block' : 'none';
    document.getElementById('director_fields').style.display        = isDirector ? 'block' : 'none';

    populateRoleFields(document.getElementById('employeeFullName').value);
    toggleRequiredFields();
  });

  // Ao carregar a página, ajusta required pela configuração inicial
  toggleRequiredFields();
</script>
@endsection
