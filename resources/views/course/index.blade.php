@extends("layouts.admin.layout")
@section("title", "Cursos")
@section("content")

<div class="card mb-4 shadow">
  <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
    <span><i class="bi bi-book me-2"></i>Todos os Cursos</span>
    <div>
      <a href="{{ route("course.create") }}" class="btn btn-outline-light btn-sm" title="Adicionar Novo Curso"> 
        Novo <i class="bi bi-plus-circle"></i>
      </a>
    </div>
  </div>
  <div class="card-body">
    <div class="table-responsive">
      <table id="datatablesSimple" class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome do Curso</th>
            <th>Descrição</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          @if ($courses)
            @foreach($courses as $course)
              <tr>
                <td>{{ $course->id }}</td>
                <td>{{ $course->name }}</td>
                <td>{{ $course->description ?? "-" }}</td>
                <td>
                  <a href="{{ route("course.show", $course->id) }}" class="btn btn-warning btn-sm" title="Visualizar">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a href="{{ route("course.edit", $course->id) }}" class="btn btn-info btn-sm" title="Editar">
                    <i class="bi bi-pencil"></i>
                  </a>
                  <a href="#" data-url="{{ url("course/".$course->id."/delete") }}" class="btn btn-danger btn-sm delete-btn" title="Apagar">
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


