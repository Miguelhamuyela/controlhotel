@extends("layouts.admin.pdf")

@section("pdfTitle", "Ficha do Funcionário")

@section("titleSection")
  <h4>Ficha do Funcionário</h4>
  <p><strong>Nome:</strong> <ins>{{ $employee->fullName }}</ins></p>
@endsection

@section("contentTable")
  {{-- Foto no PDF --}}
  @if($employee->photo)
    <div style="text-align: center; margin-bottom: 20px;">
      <img src="{{ public_path("frontend/images/departments/" . $employee->photo) }}"
           style="width:120px; height:120px; object-fit:cover; border-radius:60px;">
    </div>
  @endif

  <table>
    <tbody>
      <tr>
        <th>E-mail</th>
        <td>{{ $employee->email }}</td>
      </tr>
      <tr>
        <th>Telefone</th>
        <td>
          @if($employee->phone_code) {{ $employee->phone_code }} @endif {{ $employee->mobile }}
        </td>
      </tr>
      <tr>
        <th>Bilhete de Identidade</th>
        <td>{{ $employee->bi }}</td>
      </tr>
      <tr>
        <th>Data de Nascimento</th>
        <td>{{ \Carbon\Carbon::parse($employee->birth_date)->format("d-m-Y") }}</td>
      </tr>
      <tr>
        <th>Departamento</th>
        <td>{{ $employee->department->title ?? "-" }}</td>
      </tr>
      <tr>
        <th>Cargo</th>
        <td>{{ $employee->position->name ?? "-" }}</td>
      </tr>
      <tr>
        <th>Tipo de Funcionário</th>
        <td>{{ $employee->employeeType->name ?? "-" }}</td>
      </tr>
      <tr>
        <th>Categoria do Funcionário</th>
        <td>{{ $employee->employeeCategory->name ?? "-" }}</td>
      </tr>
      <tr>
        <th>Nacionalidade</th>
        <td>{{ $employee->nationality }}</td>
      </tr>
      <tr>
        <th>Gênero</th>
        <td>{{ $employee->gender }}</td>
      </tr>
      <tr>
        <th>IBAN</th>
        <td>{{ $employee->iban ?? "-" }}</td>
      </tr>
      @php
        use App\Models\SalaryPayment;
        $last = SalaryPayment::where("employeeId", $employee->id)
                 ->orderByDesc("paymentDate")->first();
      @endphp
      <tr>
        <th>Último Salário</th>
        <td>
          @if($last) {{ number_format($last->salaryAmount, 2, ",", ".") }}
          @else - @endif
        </td>
      </tr>
      <tr>
        <th>Data do Último Pagamento</th>
        <td>
          @if($last) {{ \Carbon\Carbon::parse($last->paymentDate)->format("d-m-Y") }}
          @else - @endif
        </td>
      </tr>
    </tbody>
  </table>
@endsection
