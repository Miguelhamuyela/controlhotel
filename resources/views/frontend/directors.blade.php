{{-- resources/views/frontend/directors.blade.php --}}
@extends('layouts.site.frontend')

@push('styles')
  <style>
    .director-photo-wrapper {
      overflow: hidden;
      border-radius: 5px;
      display: inline-block;
    }
    .director-photo {
      transition: transform 0.3s ease;
      display: block;
    }
    .director-photo-wrapper:hover .director-photo {
      transform: scale(1.1);
    }
    .director-link {
      cursor: pointer;
      text-decoration: none;
      color: inherit;
    }

    /* Carrossel: scroll horizontal, sem scrollbar visível */
    .carousel-wrapper {
      overflow-x: auto;
      overflow-y: hidden;
      cursor: grab;
      -ms-overflow-style: none;  /* IE */
      scrollbar-width: none;     /* Firefox */
    }
    .carousel-wrapper::-webkit-scrollbar {
      display: none;             /* Chrome/Webkit */
    }

    .carousel-track {
      display: flex;
      align-items: center;       /* verticalmente centralizados */
      flex-wrap: nowrap;
    }
    .carousel-track > .card-item {
      flex: 0 0 calc(100% / 3);
      box-sizing: border-box;
      padding: 0 0.5rem;
    }

    @media (max-width: 768px) {
      .carousel-track > .card-item {
        flex: 0 0 100%;
        padding: 0;
        margin-bottom: 1rem;
      }
    }
  </style>
@endpush

@section('content')
  <div class="page">
    <div class="container my-5">
      <h2 class="text-center fw-bold mb-4">Nossa Diretoria</h2>

      @php
        $general        = $directors->firstWhere('directorType', 'directorGeneral');
        $technical      = $directors->firstWhere('directorType', 'directorTechnical');
        $administrative = $directors->firstWhere('directorType', 'directorAdministrative');
      @endphp

      <div class="carousel-wrapper">
        <div class="carousel-track">
          {{-- bloco original de até 3 cards --}}
          @if($administrative)
            <div class="card-item text-center">
              <a href="{{ route('frontend.directors.show', $administrative->id) }}" class="director-link director-photo-wrapper">
                <img src="{{ asset('frontend/images/directors/' . ($administrative->directorPhoto ?? 'default.jpg')) }}"
                     alt="{{ $administrative->directorName }}"
                     class="director-photo img-fluid mb-3" style="max-width:200px;">
              </a>
              <h5 class="fw-bold mb-1" style="color:#E46705;">{{ $administrative->directorName }}</h5>
              <p class="text-muted mb-0">Diretor(a) Geral Adjunta para Área Administrativa</p>
              <div class="mt-2">
                <a href="{{ route('frontend.directors.show', $administrative->id) }}" class="btn btn-sm btn-primary">Perfil</a>
              </div>
            </div>
          @endif

          @if($general)
            <div class="card-item text-center">
              <a href="{{ route('frontend.directors.show', $general->id) }}" class="director-link director-photo-wrapper">
                <img src="{{ asset('frontend/images/directors/' . ($general->directorPhoto ?? 'default.jpg')) }}"
                     alt="{{ $general->directorName }}"
                     class="director-photo img-fluid mb-3" style="max-width:200px;">
              </a>
              <h5 class="fw-bold mb-1" style="color:#E46705;">{{ $general->directorName }}</h5>
              <p class="text-muted mb-0">Diretor(a) Geral</p>
              <div class="mt-2">
                <a href="{{ route('frontend.directors.show', $general->id) }}" class="btn btn-sm btn-primary">Perfil</a>
              </div>
            </div>
          @endif

          @if($technical)
            <div class="card-item text-center">
              <a href="{{ route('frontend.directors.show', $technical->id) }}" class="director-link director-photo-wrapper">
                <img src="{{ asset('frontend/images/directors/' . ($technical->directorPhoto ?? 'default.jpg')) }}"
                     alt="{{ $technical->directorName }}"
                     class="director-photo img-fluid mb-3" style="max-width:200px;">
              </a>
              <h5 class="fw-bold mb-1" style="color:#E46705;">{{ $technical->directorName }}</h5>
              <p class="text-muted mb-0">Diretor(a) Geral Adjunto para Área Técnica</p>
              <div class="mt-2">
                <a href="{{ route('frontend.directors.show', $technical->id) }}" class="btn btn-sm btn-primary">Perfil</a>
              </div>
            </div>
          @endif
        </div>
      </div>

    </div>
  </div>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const wrapper = document.querySelector('.carousel-wrapper');
      const track   = document.querySelector('.carousel-track');
      const items   = Array.from(track.children);

      // Clona itens nos dois extremos
      items.forEach(item => track.appendChild(item.cloneNode(true)));
      items.slice().reverse().forEach(item => track.insertBefore(item.cloneNode(true), track.firstChild));

      const itemWidth       = items[0].getBoundingClientRect().width;
      const totalOriginal   = itemWidth * items.length;
      // posiciona no meio (início da zona original)
      wrapper.scrollLeft = totalOriginal;

      let speed      = 0.5;   // px por frame
      let animation;          // id de requestAnimationFrame
      let isDragging = false;
      let startX, scrollStart;

      function autoScroll() {
        wrapper.scrollLeft += speed;
        // se sair à direita do bloco original…
        if (wrapper.scrollLeft >= totalOriginal * 2) {
          // …volta silenciosamente para o início do bloco original
          wrapper.scrollLeft = totalOriginal;
        }
        // se sair à esquerda do bloco original…
        if (wrapper.scrollLeft <= 0) {
          // …va para o fim do bloco original
          wrapper.scrollLeft = totalOriginal;
        }
        animation = requestAnimationFrame(autoScroll);
      }

      // inicia scroll automático
      autoScroll();

      // pointer events: drag para controlar
      wrapper.addEventListener('pointerdown', e => {
        cancelAnimationFrame(animation);
        isDragging  = true;
        startX      = e.pageX;
        scrollStart = wrapper.scrollLeft;
        wrapper.style.cursor = 'grabbing';
      });

      wrapper.addEventListener('pointermove', e => {
        if (!isDragging) return;
        const dx = startX - e.pageX;
        wrapper.scrollLeft = scrollStart + dx;
      });

      ['pointerup','pointerleave'].forEach(evt =>
        wrapper.addEventListener(evt, () => {
          if (!isDragging) return;
          isDragging = false;
          wrapper.style.cursor = 'grab';
          autoScroll();
        })
      );
    });
  </script>
@endpush
