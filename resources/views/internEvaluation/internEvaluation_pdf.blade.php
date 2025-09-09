@extends('layouts.admin.pdf')

@section('pdfTitle', 'Relatório de Avaliação de Estagiário')

@section('titleSection')
  <h4>Relatório de Avaliação de Estagiário</h4>
  <p style="text-align: center;">
    <strong>Estagiário:</strong> {{ $evaluation->intern->fullName ?? '-' }}<br>
    <strong>Instituição:</strong> {{ $evaluation->intern->institution ?? '-' }}<br>
    <strong>Início do Estágio:</strong> {{ \Carbon\Carbon::parse($evaluation->intern->internshipStart)->format('d/m/Y') }}<br>
    <strong>Término do Estágio:</strong> {{ \Carbon\Carbon::parse($evaluation->intern->internshipEnd)->format('d/m/Y') }}<br>
    <strong>Data de Avaliação:</strong> {{ $evaluation->created_at->format('d/m/Y H:i') }}<br>
    <strong>Status:</strong> {{ $evaluation->evaluationStatus }}<br>
  </p>
@endsection

@section('contentTable')
  <table>
    <thead>
      <tr>
        <th>Critério</th>
        <th>Avaliação</th>
      </tr>
    </thead>
    <tbody>
      <!-- Novos campos -->
      <tr>
        <td>Programa de Estágio</td>
        <td>{{ $evaluation->programaEstagio ?? '-' }}</td>
      </tr>
      <tr>
        <td>Projectos</td>
        <td>{{ $evaluation->projectos ?? '-' }}</td>
      </tr>
      <tr>
        <td>Atividades Desenvolvidas</td>
        <td>{{ $evaluation->atividadesDesenvolvidas ?? '-' }}</td>
      </tr>
      <!-- Critérios de avaliação -->
      <tr>
        <td>Pontualidade/Assiduidade</td>
        <td>{{ $evaluation->pontualidade }}</td>
      </tr>
      <tr>
        <td>Trabalho em Equipe</td>
        <td>{{ $evaluation->trabalhoEquipe }}</td>
      </tr>
      <tr>
        <td>Autodidacta</td>
        <td>{{ $evaluation->autodidacta }}</td>
      </tr>
      <tr>
        <td>Disciplina</td>
        <td>{{ $evaluation->disciplina }}</td>
      </tr>
      <tr>
        <td>Foco no Resultado</td>
        <td>{{ $evaluation->focoResultado }}</td>
      </tr>
      <tr>
        <td>Comunicação</td>
        <td>{{ $evaluation->comunicacao }}</td>
      </tr>
      <tr>
        <td>Apresentação</td>
        <td>{{ $evaluation->apresentacao }}</td>
      </tr>
      <tr>
        <td>Comentário</td>
        <td>{{ $evaluation->evaluationComment ?? '-' }}</td>
      </tr>
    </tbody>
  </table>
@endsection
