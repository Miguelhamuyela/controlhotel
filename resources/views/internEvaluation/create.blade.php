@extends('layouts.admin.layout')
@section('title', 'Nova Avaliação de Estagiário')
@section('content')
<div class="card my-4 shadow">
  <div class="card-header bg-primary text-white">
    <h4>Novo Relatório de Avaliação</h4>
  </div>
  <div class="card-body">
    @unless(isset($intern))
      <form method="GET" action="{{ route('internEvaluation.searchIntern') }}" class="mb-4">
        <div class="row g-3">
          <div class="col-md-10">
            <input type="text" name="internSearch" class="form-control" placeholder="Pesquisar por ID ou Nome do Estagiário" value="{{ old('internSearch') }}">
          </div>
          <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
          </div>
        </div>
      </form>
    @endunless

    @if(isset($intern))
      <form method="POST" action="{{ route('internEvaluation.store') }}">
        @csrf
        <input type="hidden" name="internId" value="{{ $intern->id }}">

        <!-- Dados do Estagiário -->
        <div class="row">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Nome do Estagiário</label>
              <input type="text" class="form-control" value="{{ $intern->fullName }}" readonly>
            </div>
          </div>
        </div>

        <!-- Novos campos para detalhar a avaliação -->
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Programa de Estágio</label>
            <textarea name="programaEstagio" class="form-control" rows="3">{{ old('programaEstagio') }}</textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">Projectos</label>
            <textarea name="projectos" class="form-control" rows="3">{{ old('projectos') }}</textarea>
          </div>
          <div class="col-md-4">
            <label class="form-label">Atividades Desenvolvidas</label>
            <textarea name="atividadesDesenvolvidas" class="form-control" rows="3">{{ old('atividadesDesenvolvidas') }}</textarea>
          </div>
        </div>

        <!-- Seção para seleção do Status da Avaliação -->
        <div class="row g-3 mt-4">
          <div class="col-md-4">
            <label class="form-label">Status da Avaliação</label>
            <select name="evaluationStatus" class="form-select" required>
              <option value="Pendente" selected>Pendente</option>
              <option value="Aprovado">Aprovado</option>
              <option value="Recusado">Recusado</option>
            </select>
          </div>
        </div>

        <!-- Avaliações distribuídas em duas colunas -->
        <div class="row g-3 mt-4">
          <!-- Coluna Esquerda -->
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Pontualidade/Assiduidade</label>
              <select name="pontualidade" class="form-select" required>
                <option value="">Selecione</option>
                <option value="Regular">Regular</option>
                <option value="Mediano">Mediano</option>
                <option value="Bom">Bom</option>
                <option value="Muito Bom">Muito Bom</option>
                <option value="Excelente">Excelente</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Trabalho em Equipe</label>
              <select name="trabalhoEquipe" class="form-select" required>
                <option value="">Selecione</option>
                <option value="Regular">Regular</option>
                <option value="Mediano">Mediano</option>
                <option value="Bom">Bom</option>
                <option value="Muito Bom">Muito Bom</option>
                <option value="Excelente">Excelente</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Autodidacta</label>
              <select name="autodidacta" class="form-select" required>
                <option value="">Selecione</option>
                <option value="Regular">Regular</option>
                <option value="Mediano">Mediano</option>
                <option value="Bom">Bom</option>
                <option value="Muito Bom">Muito Bom</option>
                <option value="Excelente">Excelente</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Disciplina</label>
              <select name="disciplina" class="form-select" required>
                <option value="">Selecione</option>
                <option value="Regular">Regular</option>
                <option value="Mediano">Mediano</option>
                <option value="Bom">Bom</option>
                <option value="Muito Bom">Muito Bom</option>
                <option value="Excelente">Excelente</option>
              </select>
            </div>
          </div>
          
          <!-- Coluna Direita -->
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label">Foco no Resultado</label>
              <select name="focoResultado" class="form-select" required>
                <option value="">Selecione</option>
                <option value="Regular">Regular</option>
                <option value="Mediano">Mediano</option>
                <option value="Bom">Bom</option>
                <option value="Muito Bom">Muito Bom</option>
                <option value="Excelente">Excelente</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Comunicação</label>
              <select name="comunicacao" class="form-select" required>
                <option value="">Selecione</option>
                <option value="Regular">Regular</option>
                <option value="Mediano">Mediano</option>
                <option value="Bom">Bom</option>
                <option value="Muito Bom">Muito Bom</option>
                <option value="Excelente">Excelente</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Apresentação</label>
              <select name="apresentacao" class="form-select" required>
                <option value="">Selecione</option>
                <option value="Regular">Regular</option>
                <option value="Mediano">Mediano</option>
                <option value="Bom">Bom</option>
                <option value="Muito Bom">Muito Bom</option>
                <option value="Excelente">Excelente</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Comentário da Avaliação e Botão de Envio -->
        <div class="row mt-3">
          <div class="col-md-8 offset-md-2">
            <div class="mb-3">
              <label class="form-label">Comentário da Avaliação</label>
              <textarea name="evaluationComment" rows="4" class="form-control" placeholder="Comentários adicionais">{{ old('evaluationComment') }}</textarea>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col text-center">
            <button type="submit" class="btn btn-success w-50">
              Salvar Avaliação
            </button>
          </div>
        </div>
      </form>
    @endif
  </div>
</div>
@endsection
