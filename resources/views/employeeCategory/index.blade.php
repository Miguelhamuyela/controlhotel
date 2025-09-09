@extends("layouts.admin.layout")
@section("title", "Categorias de Funcionários")
@section("content")

<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-tags me-2"></i>Todas as Categorias de Funcionários</span>
    <div>
      <a href="{{ route("employeeCategory.create") }}" class="btn btn-outline-light btn-sm" title="Adicionar Nova Categoria"> 
        Nova <i class="bi bi-plus-circle"></i>
      </a>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="datatablesSimple" class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome da Categoria</th>
            <th>Descrição</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @if ($employeeCategories)
            @foreach($employeeCategories as $category)
              <tr>
                <td>{{ $category->id }}</td>
                <td>{{ $category->name }}</td>
                <td>{{ $category->description ?? "-" }}</td>
                <td>
                  <a href="{{ route("employeeCategory.show", $category->id) }}" class="btn btn-warning btn-sm" title="Visualizar">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="{{ route("employeeCategory.edit", $category->id) }}" class="btn btn-info btn-sm" title="Editar">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="#" data-url="{{ url("employeeCategory/".$category->id."/delete") }}" class="btn btn-danger btn-sm delete-btn" title="Apagar">
                    <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            @endforeach
          @endif
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection


