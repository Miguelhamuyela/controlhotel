@extends('layouts.admin.layout')
@section('title','Editar Material — '.ucfirst($category))

@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white">
    <i class="bi bi-pencil me-2"></i> Editar Material ({{ ucfirst($category) }})
  </div>
  <div class="card-body">
    <form action="{{ route('materials.update',$material->id) }}" method="POST">
      @csrf @method('PUT')
      <input type="hidden" name="Category" value="{{ $category }}">
      <!-- Tipo -->
      <div class="mb-3">
        <label class="form-label">Tipo de Material</label>
        <select name="materialTypeId" class="form-select" required>
          <option value="">-- selecione --</option>
          @foreach($types as $t)
            <option value="{{ $t->id }}" {{ old('materialTypeId',$material->materialTypeId)==$t->id?'selected':'' }}>
              {{ $t->name }}
            </option>
          @endforeach
        </select>
      </div>
      <!-- demais campos -->
      <div class="mb-3">
        <label class="form-label">Nome</label>
        <input type="text" name="Name" class="form-control" required value="{{ old('Name',$material->Name) }}">
      </div>
      <div class="mb-3">
        <label class="form-label">Número de Série</label>
        <input type="text" class="form-control" value="{{ $material->SerialNumber }}" disabled>
      </div>
      <div class="mb-3">
        <label class="form-label">Modelo</label>
        <input type="text" name="Model" class="form-control" required value="{{ old('Model',$material->Model) }}">
      </div>
      <div class="mb-3">
        <label class="form-label">Data de Fabrico</label>
        <input type="date" name="ManufactureDate" class="form-control" required value="{{ old('ManufactureDate',$material->ManufactureDate->toDateString()) }}">
      </div>
      <div class="mb-3">
        <label class="form-label">Observações</label>
        <textarea name="Notes" class="form-control" rows="3">{{ old('Notes',$material->Notes) }}</textarea>
      </div>
      <div class="text-center">
        <button class="btn btn-success"><i class="bi bi-check-circle me-1"></i> Atualizar</button>
        <a href="{{ route('materials.index',['category'=>$category]) }}" class="btn btn-secondary ms-2">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection
