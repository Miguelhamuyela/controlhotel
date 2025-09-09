@extends('layouts.admin.layout')
@section('title', 'Editar Tipo de Licença')
@section('content')

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white">
    <span><i class="bi bi-pencil-square me-2"></i>Editar Tipo de Licença</span>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('leaveType.update', $data->id) }}">
      @csrf
      @method('put')
      <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $data->name) }}" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Descrição</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $data->description) }}</textarea>
      </div>
      <button type="submit" class="btn btn-primary">
        <i class="bi bi-check-circle me-2"></i>Atualizar Tipo de Licença
      </button>
    </form>
  </div>
</div>

@endsection
