@extends('layouts.admin.layout')
@section('title', 'Editar Avaliação')

@section('content')
<div class="card my-4 shadow">
  <div class="card-header bg-warning text-white">
    <h4>Editar Avaliação de Estagiário</h4>
  </div>
  <div class="card-body">
    <form method="POST" action="{{ route('internEvaluation.update', $evaluation->id) }}">
      @csrf
      @method('PUT')

      
      <div class="mb-3">
        <label class="form-label">Programa de Estágio</label>
        <textarea name="programaEstagio" class="form-control" rows="3">{{ $evaluation->programaEstagio }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Projectos</label>
        <textarea name="projectos" class="form-control" rows="3">{{ $evaluation->projectos }}</textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Atividades Desenvolvidas</label>
        <textarea name="atividadesDesenvolvidas" class="form-control" rows="3">{{ $evaluation->atividadesDesenvolvidas }}</textarea>
      </div>

      <!-- Critérios de avaliação existentes -->
      <div class="mb-3">
        <label class="form-label">Pontualidade/Assiduidade</label>
        <select name="pontualidade" class="form-select" required>
          <option value="">Selecione</option>
          <option value="Regular"   @if($evaluation->pontualidade=='Regular') selected @endif>Regular</option>
          <option value="Mediano"   @if($evaluation->pontualidade=='Mediano') selected @endif>Mediano</option>
          <option value="Bom"       @if($evaluation->pontualidade=='Bom') selected @endif>Bom</option>
          <option value="Muito Bom" @if($evaluation->pontualidade=='Muito Bom') selected @endif>Muito Bom</option>
          <option value="Excelente" @if($evaluation->pontualidade=='Excelente') selected @endif>Excelente</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Trabalho em Equipe</label>
        <select name="trabalhoEquipe" class="form-select" required>
          <option value="">Selecione</option>
          <option value="Regular"   @if($evaluation->trabalhoEquipe=='Regular') selected @endif>Regular</option>
          <option value="Mediano"   @if($evaluation->trabalhoEquipe=='Mediano') selected @endif>Mediano</option>
          <option value="Bom"       @if($evaluation->trabalhoEquipe=='Bom') selected @endif>Bom</option>
          <option value="Muito Bom" @if($evaluation->trabalhoEquipe=='Muito Bom') selected @endif>Muito Bom</option>
          <option value="Excelente" @if($evaluation->trabalhoEquipe=='Excelente') selected @endif>Excelente</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Autodidacta</label>
        <select name="autodidacta" class="form-select" required>
          <option value="">Selecione</option>
          <option value="Regular"   @if($evaluation->autodidacta=='Regular') selected @endif>Regular</option>
          <option value="Mediano"   @if($evaluation->autodidacta=='Mediano') selected @endif>Mediano</option>
          <option value="Bom"       @if($evaluation->autodidacta=='Bom') selected @endif>Bom</option>
          <option value="Muito Bom" @if($evaluation->autodidacta=='Muito Bom') selected @endif>Muito Bom</option>
          <option value="Excelente" @if($evaluation->autodidacta=='Excelente') selected @endif>Excelente</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Disciplina</label>
        <select name="disciplina" class="form-select" required>
          <option value="">Selecione</option>
          <option value="Regular"   @if($evaluation->disciplina=='Regular') selected @endif>Regular</option>
          <option value="Mediano"   @if($evaluation->disciplina=='Mediano') selected @endif>Mediano</option>
          <option value="Bom"       @if($evaluation->disciplina=='Bom') selected @endif>Bom</option>
          <option value="Muito Bom" @if($evaluation->disciplina=='Muito Bom') selected @endif>Muito Bom</option>
          <option value="Excelente" @if($evaluation->disciplina=='Excelente') selected @endif>Excelente</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Foco no Resultado</label>
        <select name="focoResultado" class="form-select" required>
          <option value="">Selecione</option>
          <option value="Regular"   @if($evaluation->focoResultado=='Regular') selected @endif>Regular</option>
          <option value="Mediano"   @if($evaluation->focoResultado=='Mediano') selected @endif>Mediano</option>
          <option value="Bom"       @if($evaluation->focoResultado=='Bom') selected @endif>Bom</option>
          <option value="Muito Bom" @if($evaluation->focoResultado=='Muito Bom') selected @endif>Muito Bom</option>
          <option value="Excelente" @if($evaluation->focoResultado=='Excelente') selected @endif>Excelente</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Comunicação</label>
        <select name="comunicacao" class="form-select" required>
          <option value="">Selecione</option>
          <option value="Regular"   @if($evaluation->comunicacao=='Regular') selected @endif>Regular</option>
          <option value="Mediano"   @if($evaluation->comunicacao=='Mediano') selected @endif>Mediano</option>
          <option value="Bom"       @if($evaluation->comunicacao=='Bom') selected @endif>Bom</option>
          <option value="Muito Bom" @if($evaluation->comunicacao=='Muito Bom') selected @endif>Muito Bom</option>
          <option value="Excelente" @if($evaluation->comunicacao=='Excelente') selected @endif>Excelente</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">Apresentação</label>
        <select name="apresentacao" class="form-select" required>
          <option value="">Selecione</option>
          <option value="Regular"   @if($evaluation->apresentacao=='Regular') selected @endif>Regular</option>
          <option value="Mediano"   @if($evaluation->apresentacao=='Mediano') selected @endif>Mediano</option>
          <option value="Bom"       @if($evaluation->apresentacao=='Bom') selected @endif>Bom</option>
          <option value="Muito Bom" @if($evaluation->apresentacao=='Muito Bom') selected @endif>Muito Bom</option>
          <option value="Excelente" @if($evaluation->apresentacao=='Excelente') selected @endif>Excelente</option>
        </select>
      </div>

      <!-- Comentário Geral -->
      <div class="mb-3">
        <label class="form-label">Comentário Geral</label>
        <textarea name="evaluationComment" rows="4" class="form-control"
                  placeholder="Comentários adicionais">{{ $evaluation->evaluationComment }}</textarea>
      </div>

      <!-- Status (Pendente, Aprovado, Recusado) -->
      <div class="mb-3">
        <label class="form-label">Status da Avaliação</label>
        <select name="evaluationStatus" class="form-select" required>
          <option value="Pendente" @if($evaluation->evaluationStatus=='Pendente') selected @endif>Pendente</option>
          <option value="Aprovado" @if($evaluation->evaluationStatus=='Aprovado') selected @endif>Aprovado</option>
          <option value="Recusado" @if($evaluation->evaluationStatus=='Recusado') selected @endif>Recusado</option>
        </select>
      </div>

      <button type="submit" class="btn btn-success w-100">Atualizar Avaliação</button>
    </form>
  </div>
</div>
@endsection
