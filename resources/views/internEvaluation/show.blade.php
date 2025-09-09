@extends('layouts.admin.layout')
@section('title', 'Detalhes da Avaliação')

@section('content')
<div class="card mb-4 shadow">
  <div class="card-header bg-primary  text-white d-flex justify-content-between align-items-center">
    <h4>Detalhes da Avaliação</h4>
    <a href="{{ route('internEvaluation.index') }}" class="btn btn-outline-light btn-sm" title="Voltar">
      <i class="bi bi-arrow-left"></i> Voltar
    </a>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <tr>
        <th>ID</th>
        <td>{{ $evaluation->id }}</td>
      </tr>
      <tr>
        <th>Estagiário</th>
        <td>{{ $evaluation->intern->fullName ?? '-' }}</td>
      </tr>
      <tr>
        <th>Status da Avaliação</th>
        <td>{{ $evaluation->evaluationStatus }}</td>
      </tr>

      <!-- Novos campos -->
      <tr>
        <th>Programa de Estágio</th>
        <td>{{ $evaluation->programaEstagio ?? '-' }}</td>
      </tr>
      <tr>
        <th>Projectos</th>
        <td>{{ $evaluation->projectos ?? '-' }}</td>
      </tr>
      <tr>
        <th>Atividades Desenvolvidas</th>
        <td>{{ $evaluation->atividadesDesenvolvidas ?? '-' }}</td>
      </tr>

      <!-- Critérios -->
      <tr>
        <th>Pontualidade/Assiduidade</th>
        <td>{{ $evaluation->pontualidade }}</td>
      </tr>
      <tr>
        <th>Trabalho em Equipe</th>
        <td>{{ $evaluation->trabalhoEquipe }}</td>
      </tr>
      <tr>
        <th>Autodidacta</th>
        <td>{{ $evaluation->autodidacta }}</td>
      </tr>
      <tr>
        <th>Disciplina</th>
        <td>{{ $evaluation->disciplina }}</td>
      </tr>
      <tr>
        <th>Foco no Resultado</th>
        <td>{{ $evaluation->focoResultado }}</td>
      </tr>
      <tr>
        <th>Comunicação</th>
        <td>{{ $evaluation->comunicacao }}</td>
      </tr>
      <tr>
        <th>Apresentação</th>
        <td>{{ $evaluation->apresentacao }}</td>
      </tr>
      <tr>
        <th>Comentário</th>
        <td>{{ $evaluation->evaluationComment ?? '-' }}</td>
      </tr>
      <tr>
        <th>Criado em</th>
        <td>{{ $evaluation->created_at->format('d/m/Y H:i') }}</td>
      </tr>
    </table>
  </div>
</div>
@endsection
