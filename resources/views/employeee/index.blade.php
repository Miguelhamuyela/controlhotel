@extends('layouts.admin.layout')

@section('title', 'Funcionários')

@section('content')
    <div class="card mb-4 p-4 rounded shadow-sm"> <!-- Card minimalista como no Duralux -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4><i class="fas fa-users me-2"></i>Todos os Funcionários</h4>
            <div>
                <a href="{{ route('employeee.pdfAll') }}" class="btn btn-outline-secondary btn-sm" title="Baixar PDF" target="_blank" rel="noopener noreferrer">
                    <i class="fas fa-file-pdf"></i> Baixar PDF
                </a>
                <a href="{{ route('employeee.filter') }}" class="btn btn-outline-secondary btn-sm" title="Filtrar por Data">
                    <i class="fas fa-calendar-alt"></i> Filtrar
                </a>
                <a href="{{ route('employeee.create') }}" class="btn btn-outline-secondary btn-sm" title="Adicionar Novo Funcionário">
                    Novo <i class="fas fa-plus-circle"></i>
                </a>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover"> <!-- Removi striped para minimalismo Duralux -->
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome Completo</th>
                        <th>Departamento</th>
                        <th>Cargo</th>
                        <th>Especialidade</th>
                        <th>Tipo de Funcionário</th>
                        <th>Categoria</th>
                        <th>Nível Acadêmico</th>
                        <th>Curso</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($data)
                        @foreach($data as $d)
                            <tr>
                                <td>{{ $d->id }}</td>
                                <td>{{ $d->fullName ?? 'Nome não definido' }}</td>
                                <td>{{ $d->department->title ?? 'Departamento não encontrado' }}</td>
                                <td>{{ $d->position->name ?? 'Cargo não encontrado' }}</td>
                                <td>{{ $d->specialty->name ?? 'Especialidade não encontrada' }}</td>
                                <td>{{ $d->employeeType->name ?? 'Tipo de Funcionário não definido' }}</td>
                                <td>{{ $d->employeeCategory->name ?? 'Categoria não definida' }}</td>
                                <td>{{ $d->academicLevel ?? '-' }}</td>
                                <td>{{ $d->course->name ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('employeee.show', $d->id) }}" class="btn btn-warning btn-sm" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('employeee.edit', $d->id) }}" class="btn btn-info btn-sm" title="Editar">
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>
                                    <a href="#" data-url="{{ url('employeee/'.$d->id.'/delete') }}" class="btn btn-danger btn-sm delete-btn" title="Apagar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection