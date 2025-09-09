@extends("layouts.admin.layout")
@section("title", "Detalhes da Categoria de Funcionário")
@section("content")

<div class="card my-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-info-circle me-2"></i>Detalhes da Categoria de Funcionário</span>
    <a href="{{ route("employeeCategory.index") }}" class="btn btn-outline-light btn-sm" title="Voltar">
      <i class="bi bi-arrow-left"></i>
    </a>
  </div>
  <div class="card-body">
    <table class="table table-striped table-bordered">
      <tbody>
        <tr>
          <th>ID</th>
          <td>{{ $employeeCategory->id }}</td>
        </tr>
        <tr>
          <th>Nome da Categoria</th>
          <td>{{ $employeeCategory->name }}</td>
        </tr>
        <tr>
          <th>Criado em</th>
          <td>{{ $employeeCategory->created_at->format("d/m/Y H:i:s") }}</td>
        </tr>
        <tr>
          <th>Atualizado em</th>
          <td>{{ $employeeCategory->updated_at->format("d/m/Y H:i:s") }}</td>
        </tr>
      </tbody>
    </table>
    <div class="d-flex justify-content-end">
      <a href="{{ route("employeeCategory.edit", $employeeCategory->id) }}" class="btn btn-info me-2">
        <i class="bi bi-pencil"></i> Editar
      </a>
      <form action="{{ route("employeeCategory.destroy", $employeeCategory->id) }}" method="POST" onsubmit="return confirm("Tem certeza que deseja excluir esta categoria?");">
        @csrf
        @method("DELETE")
        <button type="submit" class="btn btn-danger">
          <i class="bi bi-trash"></i> Excluir
        </button>
      </form>
    </div>
  </div>
</div>

@endsection




