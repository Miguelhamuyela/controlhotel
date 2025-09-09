@extends('layouts.admin.pdf')

@section('titleSection')
    <!-- Opcional: Título extra, se necessário -->
@endsection

@section('contentTable')
@foreach($vacations as $vacation)
  <!-- Página 1: Modelo de FÉRIAS ANUAIS para o Funcionário -->
  <div style="page-break-after: always; padding: 20px; font-family: serif; font-size: 14px;">
      <p style="text-align: center; font-weight: bold;">FÉRIAS ANUAIS</p>
      <hr>
      <p><strong>DESPACHO:</strong> __________________________ /20</p>
      <p>LUANDA __________________________</p>
      <p>(a) ____________________</p>
      <p>(b) ____________________</p>
      <p>(c) ____________________</p>
      <p style="margin-top: 10px;">
          Requer ao Exmo. Director Geral, a concessão das férias anuais, a que tem direito, nos termos das disposições combinadas do n. 1, do artigo 77.º, do n. 1 do artigo 79.º, e do n.º 1 do artigo 83.º, todos da Lei n. 26/22, de 22 de Agosto.
      </p>
      <p>
          <em>Vinte e dois (22) dias úteis, no período compreendido entre a __________________ ou</em>
      </p>
      <p>
          <em>Parcialmente, onze (11) dias úteis, no período compreendido entre a __________________</em>
      </p>
      <p style="text-align: center; font-weight: bold; margin-top: 20px;">PEDE E ESPERA DEFERIMENTO!</p>
      <p style="text-align: center;">Luanda, aos ____ de ____ de 20____</p>
      <br>
      <!-- Área de assinaturas: Chefe da Área à esquerda e Funcionário à direita -->
      <table style="width: 100%; margin-top: 20px;">
         <tr>
            <td style="width: 50%; text-align: left;">
                 <strong>O(A) CHEFE DA ÁREA:</strong> {{ $employee->department->department_head_name ?? 'N/D' }} <br>
                 <strong>Assinatura:</strong> __________________________________
            </td>
            <td style="width: 50%; text-align: right;">
                 <strong>O funcionario(a):</strong> {{ $employee->fullName }} <br>
                 <strong>Assinatura:</strong> __________________________________
            </td>
         </tr>
      </table>
      <p style="margin-top: 10px;">(a) Nome completo: {{ $employee->fullName }}</p>
      <p>(b) Categoria: {{ $employee->position->name ?? '-' }}</p>
      <br>
      <hr>
      <!-- Dados específicos do pedido -->
      <p><strong>Tipo de Férias:</strong> {{ $vacation->vacationType }}</p>
      <p><strong>Data de Início:</strong> {{ \Carbon\Carbon::parse($vacation->vacationStart)->format('d/m/Y') }}</p>
      <p><strong>Data de Término:</strong> {{ \Carbon\Carbon::parse($vacation->vacationEnd)->format('d/m/Y') }}</p>
      <p><strong>Comentário do Chefe:</strong> {{ $vacation->approvalComment }}</p>
  </div>

  <!-- Página 2: Modelo para a Área dos Recursos Humanos -->
  <div style="page-break-after: always; padding: 20px; font-family: serif; font-size: 14px;">
      <p style="text-align: center; font-weight: bold;">INFORMAÇÃO DA ÁREA DE RECURSOS HUMANOS</p>
      <hr>
      <p>No ano anterior, _____________</p>
      <p>Gozou ___________ dias de férias anuais</p>
      <p>Deu as seguintes faltas: _______________</p>
      <p><strong>FALTAS JUSTIFICADAS:</strong> (Alineas a) a h) do n.º 1 do artigo 67.º da Lei n. 26/22, de 22 de Agosto)</p>
      <p><strong>EFEITOS DAS FALTAS INJUSTIFICADAS:</strong> (Artigo 75.º, n.º 26/22, de 22 de Agosto)</p>
      <p><strong>LICENÇA POR DOENÇA:</strong> (Artigo 90.º da Lei n. 26/22, de 22 de Agosto)</p>
      <p>O(a) requerente tem direito a __________ dias de férias anuais, a partir de __________</p>
      <br>
      <p><strong>OBS:</strong>_______________________________________________________________________________</p><br>

      <p>PELO DEPARTAMENTO DE ADMINISTRAÇÃO E SERVIÇOS GERAIS DO INFOSI, em Luanda, aos ____ de ____ de 20____</p>
      <p><strong>O RESPONSÁVEL DA ÁREA:</strong> __________________________________</p>
  </div>
@endforeach
@endsection
