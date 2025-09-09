@extends('layouts.admin.layout')
@section('title', 'Create Intern')
@section('content')

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-person-plus me-2"></i>Novo Estagiário</span>
    <a href="{{ route('intern.index') }}" class="btn btn-outline-light btn-sm" title="Ver todos">
      <i class="bi bi-card-list"></i>
    </a>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('intern.store') }}" enctype="multipart/form-data">
      @csrf

      <!-- Linha: Departamento, Cargo, Especialidade -->
      <div class="row g-3">
        <div class="col-md-4">
          <div class="form-floating">
            <select name="depart" id="depart" class="form-select">
              <option value="" selected>Selecione</option>
              @foreach($departments as $depart)
                <option value="{{ $depart->id }}">{{ $depart->title }}</option>
              @endforeach
            </select>
            <label for="depart">Departamento</label>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-floating">
            <select name="positionId" id="positionId" class="form-select">
              <option value="" selected>Selecione</option>
              @foreach($positions as $position)
                <option value="{{ $position->id }}">{{ $position->name }}</option>
              @endforeach
            </select>
            <label for="positionId">Cargo</label>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-floating">
            <select name="specialtyId" id="specialtyId" class="form-select">
              <option value="" selected>Selecione</option>
              @foreach($specialties as $specialty)
                <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
              @endforeach
            </select>
            <label for="specialtyId">Especialidade</label>
          </div>
        </div>
      </div>

      <!-- Linha: Nome Completo e Email -->
      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" name="fullName" id="fullName" class="form-control" placeholder="Full Name" value="{{ old('fullName') }}">
            <label for="fullName">Nome Completo</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
            <label for="email">Email</label>
          </div>
        </div>
      </div>

      <!-- Linha: Endereço e Telefone -->
      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" name="address" id="address" class="form-control" placeholder="Address" value="{{ old('address') }}">
            <label for="address">Endereço</label>
          </div>
        </div>
        <div class="col-md-6">
          
          <div class="input-group">
       
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="selected_code" style="height: calc(3.5rem + 5px);">
              Selecione o Código
            </button>
   
            <ul class="dropdown-menu" id="phone_code_menu" style="max-height: 30em; overflow-y: auto;">
              <!-- Itens serão inseridos dinamicamente via JS -->
            </ul>
     
            <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Telefone" maxlength="16" value="{{ old('mobile') }}">
         
            <input type="hidden" name="phoneCode" id="phoneCode" value="{{ old('phoneCode') }}">
          </div>
        </div>
      </div>

      <!-- Linha: Nome do Pai e Nome da Mãe -->
      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" name="fatherName" id="fatherName" class="form-control" placeholder="Father's Name" value="{{ old('fatherName') }}">
            <label for="fatherName">Nome do Pai</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" name="motherName" id="motherName" class="form-control" placeholder="Mother's Name" value="{{ old('motherName') }}">
            <label for="motherName">Nome da Mãe</label>
          </div>
        </div>
      </div>

      <!-- Linha: Bilhete de Identidade e Data de Nascimento -->
      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <div class="form-floating">
            <input type="text" name="bi" id="bi" class="form-control" placeholder="BI" value="{{ old('bi') }}">
            <label for="bi">Bilhete de Identidade</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <input type="date" name="birth_date" id="birth_date" class="form-control" placeholder="Birth Date" value="{{ old('birth_date') }}"
                   max="{{ date('Y-m-d') }}"
                   min="{{ \Carbon\Carbon::now()->subYears(120)->format('Y-m-d') }}">
            <label for="birth_date">Data de Nascimento</label>
          </div>
        </div>
      </div>

      <!-- Linha: Nacionalidade e Gênero -->
      <div class="row g-3 mt-3">
        <div class="col-md-6">
          <div class="form-floating">
            <select name="nationality" id="nationality" class="form-select">
              <option value="">Selecione seu país</option>
              <!-- As opções dos países serão preenchidas via JavaScript -->
            </select>
            <label for="nationality">Nacionalidade</label>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-floating">
            <select name="gender" id="gender" class="form-select">
              <option value="" selected>Select</option>
              <option value="Masculino" @if(old('gender')=='Masculino') selected @endif>Masculino</option>
              <option value="Feminino" @if(old('gender')=='Feminino') selected @endif>Feminino</option>
            </select>
            <label for="gender">Gênero</label>
          </div>
        </div>
      </div>

      <!-- Novos campos: Início do Estágio, Fim do Estágio, Instituição -->
      <div class="row g-3 mt-3">
        <div class="col-md-4">
          <div class="form-floating">
            <input type="date" name="internshipStart" id="internshipStart" class="form-control" placeholder="Início do Estágio" value="{{ old('internshipStart') }}">
            <label for="internshipStart">Início do Estágio</label>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-floating">
            <input type="date" name="internshipEnd" id="internshipEnd" class="form-control" placeholder="Fim do Estágio" value="{{ old('internshipEnd') }}">
            <label for="internshipEnd">Fim do Estágio</label>
          </div>
          
        </div>
        <div class="col-md-4">
          <div class="form-floating">
            <input type="text" name="institution" id="institution" class="form-control" placeholder="Instituição de Origem" value="{{ old('institution') }}">
            <label for="institution">Instituição de Origem</label>
          </div>
        </div>
      </div>

      <!-- Botão de envio -->
      <div class="d-grid gap-2 col-6 mx-auto mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="bi bi-check-circle me-2"></i>Cadastrar Estagiário
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
