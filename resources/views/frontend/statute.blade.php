<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="keywords" content="HTML5 Template" />
  <meta name="description" content="INFOSI Recursos Humanos" />
  <meta name="author" content="INFOSI" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>Nosso Estatuto - INFOSI</title>
  <link rel="shortcut icon" href="{{ asset('auth/img/infosi3.png') }}" />

  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/bootstrap.min.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/animate.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/owl.carousel.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/font-awesome.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/themify-icons.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/flaticon.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/revolution/css/layers.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/revolution/css/settings.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/prettyPhoto.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/shortcodes.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/main.css') }}"/>
  <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/responsive.css') }}"/>

    @stack('styles')

  <!-- ===================== Sobrepor fundo da navbar ===================== -->
  <style>
    /* Aplica a tua imagem em todo o header (barra superior + navbar) */
    header.ttm-header-style-classic {
      background: url('{{ asset("frontend/images/footer-bg-one.jpg") }}') center/cover no-repeat !important;
    }
    /* Garante que wrappers internos não metam branco por cima */
    header.ttm-header-style-classic .ttm-header-wrap,
    .site-navigation,
    .site-navigation nav.menu {
      background: transparent !important;
    }

    /* Também no mobile/sidebar */
    @media screen and (max-width: 1200px) {
      header.ttm-header-style-classic .site-navigation nav.menu,
      header.ttm-header-style-classic .site-navigation nav.menu.active {
        background: url('{{ asset("frontend/images/footer-bg-one.jpg") }}') center/cover no-repeat !important;
      }
    }

    /* Dropdowns transparentes para a imagem aparecer por trás */
    header.ttm-header-style-classic .sub-menu {
      background: transparent !important;
      border: none !important;
      box-shadow: none !important;
    }
  </style>
  <!-- =================================================================== -->

</head>
<body>
  <!-- Preloader -->
  <div id="preloader">
    <div id="status">&nbsp;</div>
  </div>

  <!-- Cabeçalho -->
  @include('layouts.site.header')

  <!-- Conteúdo Principal -->
  <div class="page">
    <div class="container my-4">
      <div class="text-center">
        <h2 style="font-weight: bold;">Nosso Estatuto</h2>
      </div>
      <div class="mt-4">
        @if(isset($statute))
          <h3>{{ $statute->title }}</h3>
          <p>{{ $statute->description }}</p>
          @if($statute->document)
            <a href="{{ asset('uploads/statutes/' . $statute->document) }}" target="_blank" class="btn btn-info">
              Visualizar Documento
            </a>
          @endif
        @else
          <p>Estatuto não cadastrado.</p>
        @endif
      </div>
    </div>
  </div>

  <!-- Rodapé -->
  @include('layouts.site.footer')
  <script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
  <script src="{{ asset('frontend/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('frontend/js/main.js') }}"></script>
  @stack('scripts')
</body>
</html>
