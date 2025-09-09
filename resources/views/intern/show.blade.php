@extends('layouts.admin.layout')
@section('title', 'View Intern')
@section('content')

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-eye me-2"></i>Ver Estagiário</span>
    <a href="{{ route('intern.index') }}" class="btn btn-outline-light btn-sm" title="View All">
      <i class="bi bi-card-list"></i>
    </a>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <tr>
          <th>Departamento</th>
          <td>{{ $data->department->title ?? $data->departmentId }}</td>
        </tr>
        <tr>
          <th>Cargo</th>
          <td>{{ $data->position->name ?? $data->positionId }}</td>
        </tr>
        <tr>
          <th>Especialidade</th>
          <td>{{ $data->specialty->name ?? $data->specialtyId }}</td>
        </tr>
        <tr>
          <th>Nome Completo</th>
          <td>{{ $data->fullName }}</td>
        </tr>
        <tr>
          <th>Endereço</th>
          <td>{{ $data->address }}</td>
        </tr>
        <tr>
          <th>Telefone</th>
          <td>
            @if($data->phone_code)
              {{ $data->phone_code }} 
            @endif
            {{ $data->mobile }}
          </td>
        </tr>
        <tr>
          <th>Nome do Pai</th>
          <td>{{ $data->fatherName }}</td>
        </tr>
        <tr>
          <th>Nome da Mãe</th>
          <td>{{ $data->motherName }}</td>
        </tr>
        <tr>
          <th>Bilhete de Identidade</th>
          <td>{{ $data->bi }}</td>
        </tr>
        <tr>
          <th>Data de Nascimento</th>
          <td>{{ $data->birth_date }}</td>
        </tr>
        <tr>
          <th>Nacionalidade</th>
          <td>{{ $data->nationality }}</td>
        </tr>
        <tr>
          <th>Gênero</th>
          <td>{{ $data->gender }}</td>
        </tr>
        <tr>
          <th>Email</th>
          <td>{{ $data->email }}</td>
        </tr>
        <tr>
          <th>Início do Estágio</th>
          <td>{{ $data->internshipStart }}</td>
        </tr>
        <tr>
          <th>Fim do Estágio</th>
          <td>{{ $data->internshipEnd ? $data->internshipEnd : 'A definir' }}</td>
        </tr>
        <tr>
          <th>Instituição de Origem</th>
          <td>{{ $data->institution }}</td>
        </tr>
      </table>
    </div>
  </div>
</div>

@endsection
