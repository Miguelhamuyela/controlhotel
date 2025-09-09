@extends('layouts.admin.layout')
@section('title', 'Registro de Presença - Lote')
@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <h4 class="mb-0">Registro de Presença - Data: {{ \Carbon\Carbon::parse($recordDate)->format('d/m/Y') }}</h4>
    <a href="{{ route('attendance.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
      <i class="bi bi-arrow-left"></i> Voltar
    </a>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('attendance.storeBatch') }}">
      @csrf
      <input type="hidden" name="recordDate" value="{{ $recordDate }}">
      
      <h5>Funcionários Ativos</h5>
      @if(count($activeEmployees))
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Funcionário</th>
              <th>Status</th>
              <th>Observações</th>
            </tr>
          </thead>
          <tbody>
            @foreach($activeEmployees as $employee)
              <tr>
                <td>{{ $employee->fullName }}</td>
                <td>
                  <select name="attendance[{{ $employee->id }}]" class="form-select" required>
                    <option value="">-- Selecione --</option>
                    <option value="Presente">Presente</option>
                    <option value="Ausente">Ausente</option>
                    <option value="Férias">Férias</option>
                    <option value="Licença">Licença</option>
                    <option value="Doença">Doença</option>
                    <option value="Teletrabalho">Teletrabalho</option>
                  </select>
                </td>
                <td>
                  <input type="text" name="observations[{{ $employee->id }}]" class="form-control" placeholder="Observações">
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      @else
        <p>Nenhum funcionário ativo para registro.</p>
      @endif

      <hr>
      <h5>Funcionários com Ausência Justificada</h5>
      @if(count($justifiedEmployees))
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>Funcionário</th>
                <th>Justificativa</th>
              </tr>
            </thead>
            <tbody>
              @foreach($justifiedEmployees as $item)
                <tr>
                  <td>{{ $item['employee']->fullName }}</td>
                  <td>{{ $item['justification'] }} ({{ $item['details'] }})</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @else
        <p>Nenhum funcionário com ausência justificada.</p>
      @endif

      <div class="text-center mt-3">
        <button type="submit" class="btn btn-success" style="width: auto;">
          <i class="bi bi-check-circle"></i> Salvar Registros
        </button>
      </div>
    </form>
  </div>
</div>
@endsection
