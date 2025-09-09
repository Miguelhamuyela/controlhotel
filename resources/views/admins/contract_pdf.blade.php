@extends('layouts.admin.pdf')
@section('pdfTitle', 'Contrato de Trabalho')
@section('titleSection')
    <h4>Contrato de Trabalho</h4>
@endsection

@section('contentTable')
<div style="margin: 40px;">
    <p>
        Eu, <strong>{{ $admin->employee->fullName }}</strong>, portador do Bilhete de Identidade <strong>{{ $admin->employee->bi }}</strong>,
        declaro estar de acordo com os termos deste contrato de trabalho com a empresa.
    </p>
    <p>
        Este contrato formaliza o vínculo empregatício, estabelecendo as responsabilidades, direitos e benefícios acordados entre as partes.
    </p>
    <br><br>
    <table style="width: 100%; margin-top: 350px;">
        <tr>
            <td style="text-align: center; width: 50%;">
                __________________________________<br>
                Assinatura do Funcionário
            </td>
            @if($admin->employee->department)
            <td style="text-align: center; width: 50%;">
                __________________________________<br>
                Assinatura do Chefe do Departamento: 
                {{ $admin->employee->department->title }} <br>
                @if($admin->employee->department->department_head_name)
                    - {{ $admin->employee->department->department_head_name }}
                @endif
            </td>
            @endif
        </tr>
    </table>
</div>
@endsection
