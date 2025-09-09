@extends("layouts.admin.layout")
@section("title", "Ver Funcionário")
@section("content")

@php
  use App\Models\SalaryPayment;
  $latestPayment = SalaryPayment::where("employeeId", $data->id)
      ->orderByDesc("paymentDate")
      ->first();
@endphp

<div class="container my-5">

  {{-- Cabeçalho com botão de voltar e baixar PDF --}}
  <div class="row mb-4">
    <div class="col-8">
      <h3><i class="bi bi-eye me-2"></i>Ver Funcionário</h3>
    </div>
    <div class="col-4 text-end">
      <a href="{{ route("employeee.index") }}" class="btn btn-outline-secondary btn-sm me-2">
        <i class="bi bi-arrow-left"></i> Voltar
      </a>
      <a href="{{ route("employeee.showPdf", $data->id) }}" class="btn btn-outline-primary btn-sm" target="_blank" rel="noopener noreferrer">
        <i class="bi bi-download" ></i> Baixar PDF
      </a>
    </div>
  </div>

  <div class="row mb-5 align-items-center justify-content-center">
    {{-- Foto --}}
    <div class="col-md-4 text-center mb-3 mb-md-0">
      @if($data->photo)
        <img src="{{ asset("frontend/images/departments/" . $data->photo) }}"
             class="img-fluid rounded-circle border"
             style="width:160px; height:160px; object-fit:cover;">
      @else
        <i class="fas fa-user-circle text-secondary" style="font-size:9rem;"></i>
      @endif
    </div>

    {{-- Card de Informações Pessoais (com o nome dentro) --}}
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white text-center">
          <strong>Informações Pessoais</strong>
        </div>
        <div class="card-body">
          <h4 class="text-center mb-2">Nome: {{ $data->fullName }}</h4>
          <table class="table table-borderless mb-0">
            <tbody>
              <tr>
                <th class="ps-0">E-mail</th>
                <td>{{ $data->email }}</td>
              </tr>
              <tr>
                <th class="ps-0">Telefone</th>
                <td>
                  @if($data->phone_code) {{ $data->phone_code }} @endif {{ $data->mobile }}
                </td>
              </tr>
              <tr>
                <th class="ps-0">Bilhete de Identidade</th>
                <td>{{ $data->bi }}</td>
              </tr>
              <tr>
                <th class="ps-0">Cópia do BI</th>
                <td>
                  @if($data->biPhoto)
                    @if(Str::endsWith($data->biPhoto, [".pdf"]))
                      <a href="{{ asset("frontend/images/biPhotos/".$data->biPhoto) }}" target="_blank">
                          Ver BILHETE de IDENTIDADE
                      </a>
                    @else
                      <img src="{{ asset("frontend/images/biPhotos/".$data->biPhoto) }}"
                           style="max-width:100px; border:1px solid #dee2e6; border-radius:4px;">
                    @endif
                  @else
                    -
                  @endif
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  {{-- Card de Detalhes Adicionais --}}
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header bg-secondary text-white text-center">
          <strong>Detalhes Adicionais</strong>
        </div>
        <div class="card-body px-4">
          <table class="table table-striped table-bordered">
            <tbody>
              <tr>
                <th>Departamento</th>
                <td>{{ $data->department->title ?? $data->departmentId }}</td>
              </tr>
              <tr>
                <th>Tipo de Funcionário</th>
                <td>{{ $data->employeeType->name ?? "-" }}</td>
              </tr>
              <tr>
                <th>Categoria do Funcionário</th>
                <td>{{ $data->employeeCategory->name ?? "-" }}</td>
              </tr>
              <tr>
                <th>Cargo</th>
                <td>{{ $data->position->name ?? $data->positionId }}</td>
              </tr>
              <tr>
                <th>Especialidade</th>
                <td>{{ $data->specialty->name ?? $data->specialtyId }}</td>
              </tr>
              <tr>
                <th>Nível Acadêmico</th>
                <td>{{ $data->academicLevel ?? "-" }}</td>
              </tr>
              <tr>
                <th>Curso</th>
                <td>{{ $data->course->name ?? "-" }}</td>
              </tr>
              <tr>
                <th>Endereço</th>
                <td>{{ $data->address }}</td>
              </tr>
              <tr>
                <th>Data de Nascimento</th>
                <td>{{ \Carbon\Carbon::parse($data->birth_date)->format("d-m-Y") }}</td>
              </tr>
              <tr>
                <th>Nacionalidade</th>
                <td>{{ $data->nationality }}</td>
              </tr>
              <tr>
                <th>Gênero</th>
                <td>{{ $data->gender }}</td>
              </tr>
              <tr>
                <th>IBAN</th>
                <td>{{ $data->iban ?? "-" }}</td>
              </tr>
              <tr>
                <th>Último Salário</th>
                <td>
                  @if($latestPayment)
                    {{ number_format($latestPayment->salaryAmount, 2, ",", ".") }}
                  @else
                    -
                  @endif
                </td>
              </tr>
              <tr>
                <th>Data do Último Pagamento</th>
                <td>
                  @if($latestPayment)
                    {{ \Carbon\Carbon::parse($latestPayment->paymentDate)->format("d-m-Y") }}
                  @else
                    -
                  @endif
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

</div>

@endsection
