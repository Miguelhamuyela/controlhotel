@extends('layouts.admin.layout')
@section('title', 'Editar Pedido de Reforma')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card mb-4 shadow">
      <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
        <h4>Editar Pedido de Reforma</h4>
        <a href="{{ route('retirements.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
          <i class="bi bi-arrow-left"></i> Voltar
        </a>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('retirements.update', $retirement->id) }}">
          @csrf
          @method('PUT')
          <div class="mb-3">
            <label for="requestDate" class="form-label">Data do Pedido</label>
            <input type="date" name="requestDate" id="requestDate" class="form-control" value="{{ $retirement->requestDate }}">
          </div>
          <div class="mb-3">
            <label for="retirementDate" class="form-label">Data de Reforma</label>
            <input type="date" name="retirementDate" id="retirementDate" class="form-control" value="{{ $retirement->retirementDate }}">
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
              <option value="Pendente" @if($retirement->status == 'Pendente') selected @endif>Pendente</option>
              <option value="Aprovado" @if($retirement->status == 'Aprovado') selected @endif>Aprovado</option>
              <option value="Rejeitado" @if($retirement->status == 'Rejeitado') selected @endif>Rejeitado</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="observations" class="form-label">Observações</label>
            <textarea name="observations" id="observations" class="form-control">{{ $retirement->observations }}</textarea>
          </div>
          <button type="submit" class="btn btn-success w-100">
            <i class="bi bi-check-circle"></i> Atualizar Pedido
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
