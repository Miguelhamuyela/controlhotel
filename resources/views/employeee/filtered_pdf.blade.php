@extends("layouts.admin.pdf")

@section("Title", "Relatório de Funcionários Filtrados")

@section("titleSection")
  <h4>Relatório de Funcionários </h4>
  <p style="text-align: center;">
    @if($startDate && $endDate)
      <strong>Filtrados no Período de:</strong>{{ \Carbon\Carbon::parse($startDate)->format("d/m/Y") }} a {{ \Carbon\Carbon::parse($endDate)->format("d/m/Y") }}   

    @endif
    <strong>Total de Funcionários:</strong> <ins>{{ $filtered->count() }}</ins>
  </p>
@endsection

@section("contentTable")
  @if($filtered->count())
    <table>
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
          <th>Data de Registro</th>
        </tr>
      </thead>
      <tbody>
        @foreach($filtered as $emp)
          <tr>
            <td>{{ $emp->id }}</td>
            <td>{{ $emp->fullName }}</td>
            <td>{{ $emp->department->title ?? "-" }}</td>
            <td>{{ $emp->position->name ?? "-" }}</td>
            <td>{{ $emp->specialty->name ?? "-" }}</td>
            <td>{{ $emp->employeeType->name ?? "-" }}</td>
            <td>{{ $emp->employeeCategory->name ?? "-" }}</td>
            <td>{{ $emp->academicLevel ?? "-" }}</td>
            <td>{{ $emp->course->name ?? "-" }}</td>
            <td>{{ $emp->created_at->format("d/m/Y") }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @else
    <p style="text-align: center;">Nenhum funcionário encontrado no filtro aplicado.</p>
  @endif
@endsection