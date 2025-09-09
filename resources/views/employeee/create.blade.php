@extends('layouts.admin.layout')

@section('title', 'Criar Funcionários')

@section('content')
    <div class="card my-4 shadow p-4"> <!-- Card simples como no Duralux -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-user-plus me-2"></i>Novo Funcionário</h4>
            <a href="{{ route('employeee.index') }}" class="btn btn-outline-secondary btn-sm" title="Ver Todos">
                <i class="fas fa-list"></i>
            </a>
        </div>
        <form method="POST" action="{{ route('employeee.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="depart" class="form-label">Departamento</label>
                    <select name="depart" id="depart" class="form-select">
                        <option value="" selected>Selecione</option>
                        @foreach($departments as $depart)
                            <option value="{{ $depart->id }}">{{ $depart->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="positionId" class="form-label">Cargo</label>
                    <select name="positionId" id="positionId" class="form-select">
                        <option value="" selected>Selecione</option>
                        @foreach($positions as $position)
                            <option value="{{ $position->id }}">{{ $position->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="specialtyId" class="form-label">Especialidade</label>
                    <select name="specialtyId" id="specialtyId" class="form-select">
                        <option value="" selected>Selecione</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="employeeTypeId" class="form-label">Tipo de Funcionário</label>
                    <select name="employeeTypeId" id="employeeTypeId" class="form-select">
                        <option value="" selected>Selecione</option>
                        @foreach($employeeTypes as $etype)
                            <option value="{{ $etype->id }}">{{ $etype->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="employeeCategoryId" class="form-label">Categoria do Funcionário</label>
                    <select name="employeeCategoryId" id="employeeCategoryId" class="form-select">
                        <option value="" selected>Selecione</option>
                        @foreach($employeeCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="courseId" class="form-label">Curso</label>
                    <select name="courseId" id="courseId" class="form-select">
                        <option value="" selected>Selecione</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="academicLevel" class="form-label">Nível Acadêmico</label>
                    <input type="text" name="academicLevel" id="academicLevel" class="form-control" placeholder="Nível Acadêmico" value="{{ old('academicLevel') }}">
                </div>
                <div class="col-md-3">
                    <label for="gender" class="form-label">Gênero</label>
                    <select name="gender" id="gender" class="form-select">
                        <option value="" selected>Selecione</option>
                        <option value="Masculino" @if(old('gender')=='Masculino') selected @endif>Masculino</option>
                        <option value="Feminino" @if(old('gender')=='Feminino') selected @endif>Feminino</option>
                    </select>
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label for="fullName" class="form-label">Nome Completo</label>
                    <input type="text" name="fullName" id="fullName" class="form-control" placeholder="Nome Completo" value="{{ old('fullName') }}">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="Email (ex: nome.sobrenome)" value="{{ old('email') }}">
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label for="address" class="form-label">Endereço</label>
                    <input type="text" name="address" id="address" class="form-control" placeholder="Endereço" value="{{ old('address') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Telefone</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="selected_code">
                            Selecione o Código
                        </button>
                        <ul class="dropdown-menu" id="phone_code_menu" style="max-height: 30em; overflow-y: auto;"></ul>
                        <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Telefone" maxlength="16" value="{{ old('mobile') }}">
                        <input type="hidden" name="phoneCode" id="phoneCode" value="{{ old('phoneCode') }}">
                    </div>
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-3">
                    <label for="bi" class="form-label">Bilhete de Identidade</label>
                    <input type="text" name="bi" id="bi" class="form-control" placeholder="Bilhete de Identidade" value="{{ old('bi') }}">
                </div>
                <div class="col-md-3">
                    <label for="biPhoto" class="form-label">Cópia do BI (PDF/Foto)</label>
                    <input type="file" name="biPhoto" id="biPhoto" class="form-control">
                </div>
                <div class="col-md-6">
                    <label for="birth_date" class="form-label">Data de Nascimento</label>
                    <input type="date" name="birth_date" id="birth_date" class="form-control" value="{{ old('birth_date') }}" max="{{ date('Y-m-d') }}" min="{{ \Carbon\Carbon::now()->subYears(120)->format('Y-m-d') }}">
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label for="nationality" class="form-label">Nacionalidade</label>
                    <select name="nationality" id="nationality" class="form-select">
                        <option value="">Selecione seu país</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="iban" class="form-label">IBAN</label>
                    <input type="text" name="iban" id="iban" class="form-control" placeholder="IBAN" value="AO06{{ old('iban') ? substr(old('iban'), 4) : '' }}" maxlength="25" pattern="AO06[0-9]{21}" title="O IBAN deve começar por AO06 seguido de 21 dígitos.">
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-6 offset-md-3">
                    <label for="photo" class="form-label">Fotografia</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                </div>
            </div>
            <div class="d-grid gap-2 col-6 mx-auto mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-check-circle me-2"></i>Cadastrar Funcionário
                </button>
            </div>
        </form>
    </div>
@endsection