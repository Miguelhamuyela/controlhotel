{{--Update--}}
@extends('layouts.admin.layout')
@section('title', 'Editar Especialidade')
@section('content')

<div class="card mb-4 mt-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-pencil-square me-2"></i>Editar Especialidade</span>
    <a href="{{ route('specialties.index') }}" class="btn btn-outline-light btn-sm" title="Ver Todos">
      <i class="bi bi-card-list"></i>
    </a>
  </div>
  <div class="card-body">
    {{-- Exibição de erros
    
    @if($errors->any())
      <div class="alert alert-danger">
        @foreach($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    @if(Session::has('msg'))
      <div class="alert alert-success">
        {{ session('msg') }}
      </div>
    @endif--}}
    

    <form method="POST" action="{{ route('specialties.update', $data->id) }}">
      @csrf
      @method('put')
      

      <div class="mb-3">
        <div class="form-floating">
          <input type="text" name="name" id="name" class="form-control" placeholder="Nome da Especialidade" value="{{ old('name', $data->name) }}">
          <label for="name">Nome da Especialidade</label>
        </div>
      </div>
      
 
      <div class="mb-3">
        <div class="form-floating">
          <textarea name="description" id="description" class="form-control" placeholder="Descrição (Opcional)" style="height: 100px;">{{ old('description', $data->description) }}</textarea>
          <label for="description">Descrição (Opcional)</label>
        </div>
      </div>

      <div class="d-grid gap-2 col-6 mx-auto mt-4">
        <button type="submit" class="btn btn-primary btn-lg">
          <i class="bi bi-check-circle me-2"></i>Salvar Alterações
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
