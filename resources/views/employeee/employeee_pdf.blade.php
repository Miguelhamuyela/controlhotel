@extends("layouts.admin.pdf")

@section("pdfTitle", "Relatório de Todos os Funcionários")

@section("titleSection")
  <h4>Relatório de Todos os Funcionários</h4>
  <p style="text-align: center;">
    <strong>Total de Funcionários:</strong> <ins>{{ $allEmployees->count() }}</ins>
  </p>
@endsection

@section("contentTable")
  @if($allEmployees->count())
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Nome Completo</th>
          <th>Departamento</th>
          <th>Tipo de Funcionario</th>
          <th>Categoria</th>
          <th>Cargo</th>
          <th>Especialidade</th>
          <th>Nível Acadêmico</th>
          <th>Curso</th>
          <th>Email</th>
        </tr>
      </thead>
      <tbody>
        @foreach($allEmployees as $emp)
          <tr>
            <td>{{ $emp->id }}</td>
            <td>{{ $emp->fullName }}</td>
            <td>{{ $emp->department->title ?? "-" }}</td>
            <td>{{ $emp->employeeType->name ?? "-" }}</td>
            <td>{{ $emp->employeeCategory->name ?? "-" }}</td>
            <td>{{ $emp->position->name ?? "-" }}</td>
            <td>{{ $emp->specialty->name ?? "-" }}</td>
            <td>{{ $emp->academicLevel ?? "-" }}</td>
            <td>{{ $emp->course->name ?? "-" }}</td>
            <td>{{ $emp->email }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Não há funcionários cadastrados.</p>
  @endif
@endsection

