{{-- resources/views/frontend/directorShow.blade.php --}}
@extends('layouts.site.frontend')

@push('styles')
  <style>
    /* Espaçamento extra entre imagem e conteúdo */
    .director-detail-row {
      --bs-gutter-x: 3rem;
      --bs-gutter-y: 1.5rem;
    }
    /* Botões menores e com ícones apenas */
    .btn-icon {
      width: 2.5rem;
      height: 2.5rem;
      padding: 0;
      font-size: 1.2rem;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }
  </style>
@endpush

@section('content')
  {{-- Breadcrumb --}}
  <div class="bg-light py-3">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="{{ route('frontend.index') }}">Início</a></li>
          <li class="breadcrumb-item"><a href="{{ route('frontend.directors') }}">Diretoria</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{ $director->directorName }}</li>
        </ol>
      </nav>
    </div>
  </div>

  {{-- Detalhe do Diretor --}}
  <section class="py-5">
    <div class="container">
      <div class="row director-detail-row align-items-center">
        {{-- Foto --}}
        <div class="col-lg-4 text-center">
          <img
            src="{{ asset('frontend/images/directors/' . ($director->directorPhoto ?? 'default.jpg')) }}"
            alt="{{ $director->directorName }}"
            class="img-fluid rounded mb-3"
            style="max-width: 100%;"
          >
        </div>

        {{-- Conteúdo --}}
        <div class="col-lg-8">
          <h2 class="fw-bold mb-3">{{ $director->directorName }}</h2>
          <h5 class="text-muted mb-4">
            @switch($director->directorType)
              @case('directorGeneral') Diretor(a) Geral @break
              @case('directorTechnical') Diretor(a) Geral Adjunto para Área Técnica @break
              @case('directorAdministrative') Diretor(a) Geral Adjunta para Área Administrativa @break
            @endswitch
          </h5>

          {{-- Biografia --}}
          <div class="mb-5">
            <h6 class="fw-semibold mb-2">Biografia</h6>
            @if($director->biography)
              <p>{!! nl2br(e($director->biography)) !!}</p>
            @else
              <p class="text-muted">Biografia não disponível.</p>
            @endif
          </div>

          {{-- Botões de ação apenas com ícones --}}
          <div class="d-flex align-items-center gap-3">
            @if($director->linkedin)
              <a href="{{ $director->linkedin }}"
                 target="_blank"
                 class="btn btn-outline-primary btn-icon"
                 aria-label="LinkedIn">
                <i class="fa fa-linkedin"></i>
              </a>
            @endif

            
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
